<?php

namespace App\Entity;

class FlatsReservations
{
    private $id;
    private $reservation;
    private $flat;
    private $reservedSlotsNumber;

    public function __construct(Reservation $reservation, Flat $flat, int $reservedSlotsNumber)
    {
        $this->reservation= $reservation;
        $this->flat = $flat;
        $this->reservedSlotsNumber = $reservedSlotsNumber;
    }
}
