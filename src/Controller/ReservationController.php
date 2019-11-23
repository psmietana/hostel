<?php

namespace App\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation", name="app.reservation.index")
     */
    public function index(Request $request, CommandBus $commandBus): JsonResponse
    {
        return JsonResponse::create();
    }
}
