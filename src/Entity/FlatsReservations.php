<?php

namespace App\Entity;

class FlatsReservations
{
    private $id;
    private $reservation;
    private $flat;
    private $reservedSlotsNumber;
    private $price;

    public function __construct(Reservation $reservation, Flat $flat, int $reservedSlotsNumber, float $price = null)
    {
        $this->reservation= $reservation;
        $this->flat = $flat;
        $this->reservedSlotsNumber = $reservedSlotsNumber;
        $this->price = $price;
    }
}
