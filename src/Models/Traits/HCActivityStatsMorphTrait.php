<?php

namespace HoneyComb\ActivityStats\Models\Traits;

use HoneyComb\ActivityStats\Models\HCActivityStatsDays;
use HoneyComb\ActivityStats\Models\HCActivityStatsDetailed;
use HoneyComb\ActivityStats\Models\HCActivityStatsMonths;
use HoneyComb\ActivityStats\Models\HCActivityStatsTotal;
use HoneyComb\ActivityStats\Models\HCActivityStatsYears;

trait HCActivityStatsMorphTrait
{
    /**
     * @return mixed
     */
    public function amountDetails()
    {
        return $this->morphMany(HCActivityStatsDetailed::class, 'amountable');
    }

    /**
     * Get all of the post's comments.
     */
    public function amountDays()
    {
        return $this->morphMany(HCActivityStatsDays::class, 'amountable');
    }

    /**
     * @return mixed
     */
    public function amountMonths()
    {
        return $this->morphMany(HCActivityStatsMonths::class, 'amountable');
    }

    /**
     * @return mixed
     */
    public function amountYears()
    {
        return $this->morphMany(HCActivityStatsYears::class, 'amountable');
    }

    /**
     * @return mixed
     */
    public function amountTotal()
    {
        return $this->morphMany(HCActivityStatsTotal::class, 'amountable');
    }
}