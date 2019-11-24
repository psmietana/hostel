<?php

namespace App\Commands\Handlers;

use App\Commands\ReserveMultipleFlatsCommand;
use App\Entity\Flat;
use App\Entity\FlatsReservations;
use App\Exceptions\NegativeReservationsSlotsNotAllowedException;
use Doctrine\ORM\EntityManager;

class ReserveMultipleFlatsHandler
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ReserveMultipleFlatsCommand $command): void
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

        if (true === $command->areAllowedMultipleFlats()) {
            $flats = $this->findMultipleFlats();
        } else {
            $flats = [$this->findSingleFlat()];
        }
    }

    private function findMultipleFlats(int $peopleNumber): array
    {
        return $this->entityManager->getRepository(Flat::class)
            ->createQueryBuilder('f')
            ->select('f, SUM(COALESCE(fr.reservedSlotsNumber, 0)) as flatReservedSlotsNumber')
            ->leftJoin(FlatsReservations::class, 'fr', 'WITH', 'fr.flat = f.id')
            ->groupBy('fr.flat')
            ->orderBy('(f.slotsNumber - flatReservedSlotsNumber) DESC')
            ->getQuery()
            ->getResult();
    }

    private function findSingleFlat(int $peopleNumber)
    {
        return $this->entityManager->getRepository(Flat::class)
            ->createQueryBuilder('f')
            ->select('f, SUM(COALESCE(fr.reservedSlotsNumber, 0)) as flatReservedSlotsNumber')
            ->leftJoin(FlatsReservations::class, 'fr', 'WITH', 'fr.flat = f.id')
            ->groupBy('fr.flat')
            ->having('(f.slotsNumber - flatReservedSlotsNumber) > :neededSlotsNumber')
            ->setParameter('neededSlotsNumber', $peopleNumber)
            ->orderBy('(f.slotsNumber - flatReservedSlotsNumber) DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
