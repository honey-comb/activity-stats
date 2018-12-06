<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Repositories\Admin;

use Carbon\Carbon;
use HoneyComb\ActivityStats\Models\HCActivityStatsDetailed;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class HCActivityStatsDetailedDataRepository
 * @package HoneyComb\ActivityStats\Repositories\Admin
 */
class HCActivityStatsDetailedDataRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCActivityStatsDetailed::class;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     * @return Collection
     */
    public function getEntries(Carbon $from, Carbon $to): Collection
    {
        return $this->makeQuery()
            ->select('type_id', 'amountable_id', 'amountable_type', 'date', 'amount')
            ->whereHas('type', function ($query) {
                $query->where('countable', true);
            })
            ->setEagerLoads([])
            ->with([
                'type' => function ($query) {
                    $query->setEagerLoads([])
                        ->select('id');
                },
            ])
            ->whereDate('date', '<=', $to)
            ->whereDate('date', '>', $from)->get();
    }
}