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
        \DateTime $dateFrom,
        \DateTime $dateTo,
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

    public function getDateFrom(): \DateTime
    {
        return $this->dateFrom;
    }

    public function getDateTo(): \DateTime
    {
        return $this->dateTo;
    }

    public function areMultipleFlatsAllowed(): bool
    {
        return true === $this->allowedMultipleFlats;
    }
}
