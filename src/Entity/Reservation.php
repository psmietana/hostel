<?php

namespace App\Entity;

class Reservation
{
    private $id;
    private $reservedSlotsNumber;
    private $dateFrom;
    private $dateTo;
    private $allowedMultipleFlats;

    public function __construct(
        int $reservedSlotsNumber,
        \DateTimeImmutable $dateFrom,
        \DateTimeImmutable $dateTo,
        bool $allowedMultipleFlats = false
    ) {
        $this->reservedSlotsNumber = $reservedSlotsNumber;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->allowedMultipleFlats = $allowedMultipleFlats;
    }

    public function getReservedSlotsNumber(): int
    {
        return $this->reservedSlotsNumber;
    }

    public function getDateFrom(): \DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): \DateTimeImmutable
    {
        return $this->dateTo;
    }

    public function areMultipleFlatsAllowed(): bool
    {
        return true === $this->allowedMultipleFlats;
    }
}
