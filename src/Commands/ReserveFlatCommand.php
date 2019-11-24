<?php
declare(strict_types=1);

namespace App\Commands;

class ReserveFlatCommand
{
    private $slotsNumber;
    private $dateFrom;
    private $dateTo;
    private $allowedMultipleFlats;

    public function __construct(
        int $peopleNumber,
        string $dateFrom,
        string $dateTo,
        bool $allowedMultipleFlats = false
    ) {
        $this->slotsNumber = $peopleNumber;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->allowedMultipleFlats = $allowedMultipleFlats;
    }

    public function getPeopleNumber(): int
    {
        return $this->slotsNumber;
    }

    public function getDateFrom(): string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): string
    {
        return $this->dateTo;
    }

    public function areAllowedMultipleFlats(): bool
    {
        return $this->allowedMultipleFlats;
    }
}
