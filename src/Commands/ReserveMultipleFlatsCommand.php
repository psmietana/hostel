<?php
declare(strict_types=1);

namespace App\Commands;

class ReserveMultipleFlatsCommand
{
    private $slotsNumber;
    private $dateFrom;
    private $dateTo;

    public function __construct(
        int $peopleNumber,
        string $dateFrom,
        string $dateTo
    ) {
        $this->slotsNumber = $peopleNumber;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
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
}
