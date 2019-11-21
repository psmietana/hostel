<?php

namespace App\Entity;

use App\Exceptions\NegativeDiscountNotAllowedException;

class Flat
{
    private $id;
    private $code;
    private $slotsNumber;
    private $singleSlotPrice;
    private $discountAboveSevenDays;

    public function __construct(string $code, int $slotsNumber, float $singleSlotPrice, int $discountAboveSevenDays = null)
    {
        $this->code = $code;
        $this->slotsNumber = $slotsNumber;
        $this->singleSlotPrice = $singleSlotPrice;
        $this->discountAboveSevenDays = $discountAboveSevenDays;
    }

    public function getSlotsNumber(): int
    {
        return $this->slotsNumber;
    }

    public function getDiscount(): ?int
    {
        return $this->discountAboveSevenDays;
    }

    public function getSingleSlotPrice(): float
    {
        return $this->singleSlotPrice;
    }

    public function isDiscountEnabled(): bool
    {
        return null !== $this->discountAboveSevenDays;
    }

    public function disableDiscount(): void
    {
        $this->discountAboveSevenDays = null;
    }

    public function setDiscount(int $discount): void
    {
        if ($discount <= 0) {
            throw new NegativeDiscountNotAllowedException();
        }

        $this->discountAboveSevenDays = $discount;
    }
}
