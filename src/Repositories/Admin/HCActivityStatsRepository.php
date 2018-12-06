<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Repositories\Admin;

use HoneyComb\ActivityStats\DTO\HCActivityStatsDTO;
use HoneyComb\ActivityStats\Models\HCActivityStatsDays;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Model;

class HCActivityStatsRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCActivityStatsDays::class;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \HoneyComb\ActivityStats\DTO\HCActivityStatsDTO $data
     */
    public function changeAmount(Model $model, HCActivityStatsDTO $data): void
    {
        $query = $this->getAmount($data->getDateType(), $model, $data->getAmountableId());
        $query->updateOrCreate($data->getUnique(get_class($model)), $data->getParams());
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param \HoneyComb\ActivityStats\DTO\HCActivityStatsDTO $data
     */
    public function updateAmount(Model $model, HCActivityStatsDTO $data): void
    {
        $query = $this->getAmount($data->getDateType(), $model, $data->getAmountableId());

        $existing = $query->where($data->getUnique(get_class($model)))->first();
        $existingAmount = $existing ? $existing->amount : 0;

        $query->updateOrCreate($data->getUnique(get_class($model)), $data->getParams($existingAmount));
    }

    /**
     * @param int $dateType
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $amountableId
     * @return mixed
     */
    public function getAmount(int $dateType, Model $model, string $amountableId)
    {
        $query = null;
        $model = $model->all()->where('id', $amountableId)->first();

        switch ($dateType) {
            case HCActivityStatsDTO::DETAILS :

                $query = $model->amountDetails();
                break;

            case HCActivityStatsDTO::DAYS :

                $query = $model->amountDays();
                break;

            case HCActivityStatsDTO::MONTHS :

                $query = $model->amountMonths();
                break;

            case HCActivityStatsDTO::YEARS :

                $query = $model->amountYears();
                break;

            case HCActivityStatsDTO::TOTAL :

                $query = $model->amountTotal();
                break;
        }

        return $query;
    }
}