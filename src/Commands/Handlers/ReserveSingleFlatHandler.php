<?php

namespace App\Commands\Handlers;

use App\Commands\ReserveMultipleFlatsCommand;
use App\Commands\ReserveSingleFlatCommand;
use App\Entity\Flat;
use App\Entity\FlatsReservations;
use App\Entity\Reservation;
use App\Exceptions\NegativeReservationsSlotsNotAllowedException;
use Doctrine\ORM\EntityManager;

class ReserveSingleFlatHandler
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ReserveSingleFlatCommand $command): void
    {
        if (0 >= $peopleNumber = $command->getPeopleNumber()) {
            throw new NegativeReservationsSlotsNotAllowedException('Ujemna liczba osób');
        }
        $dateFromString = $command->getDateFrom();
        $dateToString = $command->getDateTo();
        if (empty($dateFromString) || empty($dateToString)) {
            throw new \InvalidArgumentException('Puste dane z datami');
        }
        $dateFrom = (new \DateTimeImmutable($dateFromString))->setTime(0, 0, 0);
        $dateTo = (new \DateTimeImmutable($dateToString))->setTime(23, 59, 59);
        if ($dateFrom >= $dateTo) {
            throw new \InvalidArgumentException('Data końca rezerwacji nie może być wcześniejsza od początku rezerwacji');
        }


        if (null === $flat = $this->findSingleFlat($peopleNumber)) {
            return;
        }

        $reservation = new Reservation($peopleNumber, $dateFrom, $dateTo, false);
        $this->entityManager->persist($reservation);
        $flatReservation = new FlatsReservations($reservation, $flat, $peopleNumber);
        $this->entityManager->persist($flatReservation);
        $this->entityManager->flush();
    }

    private function findSingleFlat(int $peopleNumber): ?Flat
    {
        return $this->getDoctrine()->getManager()->getRepository(Flat::class)
            ->createQueryBuilder('f')
            ->select(
                'f',
                'SUM(COALESCE(fr.reservedSlotsNumber, 0)) as HIDDEN flatReservedSlotsNumber',
                '(f.slotsNumber - SUM(COALESCE(fr.reservedSlotsNumber, 0))) as HIDDEN availableSlots'
            )
            ->leftJoin(FlatsReservations::class, 'fr', 'WITH', 'fr.flat = f.id')
            ->groupBy('f.id')
            ->having('(f.slotsNumber - flatReservedSlotsNumber) > :neededSlotsNumber')
            ->setParameter('neededSlotsNumber', $peopleNumber)
            ->orderBy('availableSlots', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
