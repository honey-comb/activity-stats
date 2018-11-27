<?php

namespace HoneyComb\ActivityStats\DTO;


use Carbon\Carbon;

class HCActivityStatsDTO
{
    /**
     * @var int
     */
    const DETAILS = 0;

    /**
     * @var int
     */
    const DAYS = 1;

    /**
     * @var int
     */
    const MONTHS = 2;

    /**
     * @var int
     */
    const YEARS = 3;

    /**
     * @var \Carbon\Carbon
     */
    private $date;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $dateType;
    /**
     * @var string
     */
    private $amountableId;


    /**
     * HCActivityStatsDTO constructor.
     * @param int $dateType
     * @param \Carbon\Carbon $date
     * @param int $amount
     * @param string $type
     * @param string $amountableId
     */
    public function __construct(int $dateType, Carbon $date, int $amount, string $type, string $amountableId)
    {
        $this->amount = $amount;
        $this->type = $type;
        $this->dateType = $dateType;
        $this->amountableId = $amountableId;

        switch ($dateType) {
            case self::DETAILS:

                $this->date = $date->toDateTimeString();
                break;

            case self::DAYS:
                $this->date = $date->toDateString();
                break;

            case self::MONTHS:

                $this->date = $date->format('Y-m');
                break;

            case self::YEARS:

                $this->date = $date->year;
                break;
        }
    }

    /**
     * @return array
     */
    public function getUnique(): array
    {
        return [
            'type_id' => $this->type,
            'date' => $this->date,
            'amountable_id' => $this->amountableId,
        ];
    }

    /**
     * @param int $amount
     * @return array
     */
    public function getParams(int $amount = 0): array
    {
        $data = [
            'amount' => $this->amount + $amount,
        ];

        return $data;
    }

    /**
     * @return int
     */
    public function getDateType(): int
    {
        return $this->dateType;
    }
}