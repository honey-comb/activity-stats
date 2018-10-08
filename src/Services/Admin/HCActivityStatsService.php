<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Services\Admin;


use HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsRepository;

class HCActivityStatsService
{
    /**
     * @var \HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsRepository
     */
    private $statsRepository;

    /**
     * HCActivityStatsService constructor.
     * @param \HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsRepository $statsRepository
     */
    public function __construct(HCActivityStatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    /**
     * @return \HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsRepository
     */
    public function getRepository()
    {
        return $this->statsRepository;
    }
}