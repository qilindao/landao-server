<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Services\Repositories\Manage\LogRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class Log extends ApiController
{

    /**
     * 日志列表
     * @param Request $request
     * @param LogRepo $logRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, LogRepo $logRepo)
    {
        $params = $request->all();
        $ret = $logRepo->getList($params, $params['order'] ?? 'created_at', $params['sort'] ?? 'desc');
        $list = $logRepo->parseDataRows($ret['data']);
        return $this->success([
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $list
        ]);
    }

    /**
     * 删除
     * @param int $logId
     * @param LogRepo $logRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $logId, LogRepo $logRepo)
    {
        $log = $logRepo->getByPkId($logId);
        if (!$log) {
            return $this->badSuccessRequest('日志不存在');
        }
        $logRepo->transaction();
        try {
            if ($log->delete()) {
                $logRepo->commit();
                return $this->successRequest('删除成功');
            }
            $logRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $logRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 批量删除
     * @param Request $request
     * @param LogRepo $logRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDel(Request $request, LogRepo $logRepo)
    {
        if (!$request->all()) {
            return $this->badSuccessRequest('日志不存在');
        }
        $logRepo->transaction();
        try {
            if ($logRepo->deleteBatchById($request->all())) {
                $logRepo->commit();
                return $this->successRequest('删除成功');
            }
            $logRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $logRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }
}
