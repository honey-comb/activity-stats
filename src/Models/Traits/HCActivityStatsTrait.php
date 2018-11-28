<?php

namespace HoneyComb\ActivityStats\Models\Traits;

trait HCActivityStatsTrait
{
    /**
     * Get all of the owning amountable models.
     */
    public function amountable()
    {
        return $this->morphTo();
    }
}
