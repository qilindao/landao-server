<?php
declare (strict_types=1);

namespace App\Services\Repositories\Manage;


use App\Services\Enums\Manage\ManageStatusEnum;
use App\Services\Models\Manage\ManageModel;
use JoyceZ\LaravelLib\Repositories\BaseRepository;

/**
 * 实现管理员接口
 *
 * @author joyecZhang <zhangwei762@163.com>
 *
 * Class ManageRepo
 * @package App\Services\Repositories\Manage
 */
class ManageRepo extends BaseRepository
{
    public function model()
    {
        return ManageModel::class;
    }

    /**
     * 解析数据
     * @param array $row
     * @return array
     */
    // public function parseDataRow(array $row): array
    // {
        //手机号脱敏
//        if (isset($row['phone'])) {
//            $row['phone'] = FiltersHelper::dataDesensitization($row['phone'], 3, 4);
//        }
    //     if (isset($row['manage_status'])) {
    //         $row['manage_status_txt'] = ManageStatusEnum::getValue($row['manage_status']);
    //     }
    //     return (new HashIdsSup())->encode($row);
    // }

    /**
     * 管理员列表
     * @param array $params 查询参数
     * @param string $orderBy 排序字段
     * @param string $sort 排序方式
     * @return array
     */
    public function getList(array $params, string $orderBy = 'updated_at', string $sort = 'desc'): array
    {
        $lists = $this->model->where(function ($query) use ($params) {
            if (isset($params['realname']) && $params['realname'] != '') {
                $query->where('realname', 'like', '%' . $params['realname'] . '%');
            }
            if(isset($params['reg_date']) && is_array($params['reg_date'])){
                $randTime= array_map(function ($value){
                    return strtotime($value);
                },$params['reg_date']);
                $query->whereBetween('reg_date', $randTime);
            }
        })->with('department', 'roles')
            ->orderBy($orderBy, $sort)
            ->paginate(isset($params['page_size']) ? $params['page_size'] : config('landao.paginate.page_size'));
        return $lists->toArray();
    }


    /**
     * 根据登录名获取用户信息
     * @param string $username 登录用户名
     * @return mixed
     */
    public function getInfoByUsername(string $username)
    {
        return $this->model->where('username', $username)->first();
    }


}
