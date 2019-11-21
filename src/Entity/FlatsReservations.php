<?php

namespace App\Entity;

class FlatsReservations
{
    private $reservation;
    private $flat;
    private $reservedSlots;

    public function __construct(Reservation $reservation, Flat $flat, int $reservedSlots)
    {
        $this->reservation= $reservation;
        $this->flat = $flat;
        $this->reservedSlots = $reservedSlots;
    }
}
