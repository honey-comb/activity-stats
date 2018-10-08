<?php

namespace HoneyComb\ActivityStats\Models\Traits;

trait HCActivityStatsTrait
{
    /**
     * @return mixed
     */
    public function amountableDetailed()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function amountableDays()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function amountableMonths()
    {
        return $this->morphTo();
    }

    /**
     * @return mixed
     */
    public function amountableYears()
    {
        return $this->morphTo();
    }
}