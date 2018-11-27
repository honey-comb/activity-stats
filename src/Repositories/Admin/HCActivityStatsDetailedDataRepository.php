<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Repositories\Admin;

use HoneyComb\ActivityStats\Models\HCActivityStatsDetailed;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

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
}