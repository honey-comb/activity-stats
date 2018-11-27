<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Repositories\Admin;

use HoneyComb\ActivityStats\Models\HCActivityStatsCron;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

/**
 * Class HCActivityStatsCronRepository
 * @package HoneyComb\ActivityStats\Repositories\Admin
 */
class HCActivityStatsCronRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCActivityStatsCron::class;
    }
}
