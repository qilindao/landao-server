<?php
declare (strict_types=1);

namespace App\Services\Repositories\Manage;


use App\Services\Models\Manage\LogModel;
use JoyceZ\LaravelLib\Helpers\StrHelper;
use JoyceZ\LaravelLib\Repositories\BaseRepository;

/**
 * 实现管理端日志接口
 * Class LogRepo
 * @package App\Services\Repositories\Manage
 */
class LogRepo extends BaseRepository
{

    public function model()
    {
        return LogModel::class;
    }

    /**
     * 写入操作日志
     * @param array $params
     * @return mixed|void
     */
    public function record(array $params)
    {
        $params['log_params'] = json_encode($params['log_params'], JSON_UNESCAPED_SLASHES);
        $data = array_merge([
            'log_method' => strtoupper(request()->getMethod()),
            'log_action' => urldecode(request()->url()),
            'log_ip' => StrHelper::ip2long(request()->ip()),
            'useragent' => request()->server('HTTP_USER_AGENT'),
        ], $params);
        $this->model->create($data);
    }

    /**
     * 后台用户请求日志
     * @param array $params 搜索参数
     * @param string $orderBy 排序
     * @param string $sort 排序方式
     * @return array
     */
    public function getList(array $params, string $orderBy = 'created_at', string $sort = 'desc'): array
    {
        $lists = $this->model->where(function ($query) use ($params) {
            if (isset($params['search_text']) && $params['search_text'] != '') {
                $query->where('manage_username', 'like', '%' . $params['search_text'] . '%');
            }
        })
            ->orderBy($orderBy, $sort)
            ->paginate(isset($params['page_size']) ? $params['page_size'] : config('landao.paginate.page_size'));
        return $lists->toArray();
    }

    /**
     * 批量删除
     * @param array $logIds
     * @return array
     */
    public function deleteBatchById(array $logIds): bool
    {
        if (!$logIds) return false;
        $ret = $this->model->whereIn('log_id', $logIds)->delete();
        return $ret ? true : false;
    }


}
