<?php

declare(strict_types = 1);

namespace HoneyComb\ActivityStats\Http\Controllers\Admin;

use HoneyComb\ActivityStats\Services\Admin\HCActivityStatsTypeService;
use HoneyComb\ActivityStats\Http\Requests\Admin\HCActivityStatsTypeRequest;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HCActivityStatsTypeController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCActivityStatsTypeService
     */
    protected $service;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * HCActivityStatsTypeController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCActivityStatsTypeService $service
     */
    public function __construct(
        Connection $connection,
        HCFrontendResponse $response,
        HCActivityStatsTypeService $service
    ) {
        $this->connection = $connection;
        $this->response = $response;
        $this->service = $service;
    }

    /**
     * Admin panel page view
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('ActivityStats::activity_stats_types.page_title'),
            'url' => route('admin.api.activity.stats.types'),
            'form' => route('admin.api.form-manager', ['activity.stats.types']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_activity_stats_activity_stats_types'),
        ];

        return view('HCCore::admin.service.index', ['config' => $config]);
    }

    /**
     * Get admin page table columns settings
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        $columns = [
            'id' => $this->headerText(trans('ActivityStats::activity_stats_types.id')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return \HoneyComb\ActivityStats\Models\HCActivityStatsType|\HoneyComb\ActivityStats\Repositories\Admin\HCActivityStatsTypeRepository|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById(string $id)
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCActivityStatsTypeRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Create data list
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCActivityStatsTypeRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getOptions($request)
        );
    }

    /**
     * Create record
     *
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(HCActivityStatsTypeRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->create($request->getRecordData());

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            return $this->response->error($e->getMessage());
        }

        return $this->response->success("Created");
    }


    /**
     * Update record
     *
     * @param HCActivityStatsTypeRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCActivityStatsTypeRequest $request, string $id): JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());

        return $this->response->success("Created");
    }


    /**
     * Soft delete record
     *
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteSoft(HCActivityStatsTypeRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }


    /**
     * Restore record
     *
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function restore(HCActivityStatsTypeRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully restored');
    }


    /**
     * Force delete record
     *
     * @param HCActivityStatsTypeRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteForce(HCActivityStatsTypeRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }

}