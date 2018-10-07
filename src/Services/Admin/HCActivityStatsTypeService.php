<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Services\Admin;

use HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsTypeRepository;

class HCActivityStatsTypeService
{
    const TYPE_VISITS = 'visits';

    /**
     * @var HCActivityStatsTypeRepository
     */
    private $repository;

    /**
     * HCActivityStatsTypeService constructor.
     * @param HCActivityStatsTypeRepository $repository
     */
    public function __construct(HCActivityStatsTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCActivityStatsTypeRepository
     */
    public function getRepository(): HCActivityStatsTypeRepository
    {
        return $this->repository;
    }
}