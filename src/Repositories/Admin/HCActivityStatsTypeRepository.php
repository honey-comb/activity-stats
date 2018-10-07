<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Repositories\Admin;

use HoneyComb\ActivityStats\Models\HCActivityStatsType;
use HoneyComb\ActivityStats\Http\Requests\Admin\HCActivityStatsTypeRequest;
use HoneyComb\Starter\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

class HCActivityStatsTypeRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCActivityStatsType::class;
    }

    /**
     * @param HCActivityStatsTypeRequest $request
     * @return \Illuminate\Support\Collection|static
     */
    public function getOptions(HCActivityStatsTypeRequest $request)
    {
        return $this->createBuilderQuery($request)->get()->map(function($record) {
            return [
                'id' => $record->id,
                'label' => $record->label,
            ];
        });
    }

    /**
     * Soft deleting records
     * @param $ids
     * @throws \Exception
     */
    public function deleteSoft(array $ids): void
    {
        $records = $this->makeQuery()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCActivityStatsType $record */
            $record->delete();
        }
    }

    /**
     * Restore soft deleted records
     *
     * @param array $ids
     * @return void
     */
    public function restore(array $ids): void
    {
        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCActivityStatsType $record */
            $record->restore();
        }
    }

    /**
     * Force delete records by given id
     *
     * @param array $ids
     * @return void
     * @throws \Exception
     */
    public function deleteForce(array $ids): void
    {
        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCActivityStatsType $record */
            $record->forceDelete();
        }
    }
}