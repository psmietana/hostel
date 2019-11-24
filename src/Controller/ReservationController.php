<?php

namespace App\Controller;

use App\Commands\ReserveMultipleFlatsCommand;
use App\Commands\ReserveSingleFlatCommand;
use Doctrine\DBAL\Driver\Connection;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="app.reservation.add-reservation", methods={"GET"})
     */
    public function addReservation(Request $request, CommandBus $commandBus, Connection $connection): JsonResponse
    {
        $connection->beginTransaction();
        try {
            $allowedMultipleFlats = (bool) $request->get('allowedMultipleFlats');
            if ($allowedMultipleFlats) {
                $commandBus->handle(new ReserveMultipleFlatsCommand(
                    (int) $request->get('peopleNumber'),
                    (string) $request->get('dateFrom'),
                    (string) $request->get('dateTo')
                ));
            } else {
                $commandBus->handle(new ReserveSingleFlatCommand(
                    (int) $request->get('peopleNumber'),
                    (string) $request->get('dateFrom'),
                    (string) $request->get('dateTo')
                ));
            }

            $connection->commit();

            return JsonResponse::create([], Response::HTTP_CREATED);
        } catch (\InvalidArgumentException $exception) {
            $connection->rollback();

            return JsonResponse::create([
                'error' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            $connection->rollback();

            return JsonResponse::create([
                'error' => 'Internal error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
