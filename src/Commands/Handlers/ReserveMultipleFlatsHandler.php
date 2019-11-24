<?php

namespace App\Commands\Handlers;

use App\Commands\ReserveMultipleFlatsCommand;
use App\Entity\Flat;
use App\Entity\FlatsReservations;
use App\Entity\Reservation;
use App\Exceptions\NegativeReservationsSlotsNotAllowedException;
use Doctrine\Common\Collections\Collection;
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
            throw new NegativeReservationsSlotsNotAllowedException('Rezerwacja dla co najmniej 1 osoby');
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

        $flats = new \ArrayIterator($this->findMultipleFlats());
        while ($peopleNumber > 0) {
            $flat = $flats->current();
            $availableSlots = $flat[0]->getSlotsNumber() - $flat['flatReservedSlotsNumber'];
            $peopleNumber -= min($availableSlots, $peopleNumber);

            $reservation = new Reservation($peopleNumber, $dateFrom, $dateTo, true);
            $this->entityManager->persist($reservation);
            $flatReservation = new FlatsReservations($reservation, $flat, $availableSlots);
            $this->entityManager->persist($flatReservation);

            $flats->next();
            if (false === $flats->valid() && $peopleNumber > 0) {
                throw new \LogicException('Za mało wolnych slotów');
            }
        }
        $this->entityManager->flush();
    }

    /**
     * @return Flat[]
     */
    private function findMultipleFlats(): array
    {
        return $this->entityManager->getRepository(Flat::class)
            ->createQueryBuilder('f')
            ->select(
                'f',
                '(f.slotsNumber - SUM(COALESCE(fr.reservedSlotsNumber, 0))) as HIDDEN availableSlots'
            )
            ->leftJoin(FlatsReservations::class, 'fr', 'WITH', 'fr.flat = f.id')
            ->groupBy('f.id')
            ->orderBy('availableSlots', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
