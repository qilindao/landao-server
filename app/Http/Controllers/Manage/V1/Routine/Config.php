<?php


namespace App\Http\Controllers\Manage\V1\Routine;


use App\Http\Controllers\Controller;
use App\Http\Requests\System\ConfigRequest;
use App\Http\ResponseCode;
use App\Services\Enums\System\ConfigTypeEnum;
use App\Services\Repositories\System\ConfigRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\ResultHelper;

class Config extends Controller
{
    public function index(ConfigRepo $configRepo)
    {
        $configGroup = $configRepo->getConfigValueByName('config_group');
        $configType = ConfigTypeEnum::getKeys();
        $list = $configRepo->getConfigListByGroup();
        return ResultHelper::returnFormat('', ResponseCode::SUCCESS, compact('configGroup', 'configType', 'list'));
    }

    /**
     * 添加配置项
     * @param ConfigRequest $request
     * @param ConfigRepo $configRepo
     * @return array
     */
    public function store(ConfigRequest $request, ConfigRepo $configRepo)
    {
        $params = $request->all();
        $isExist = $configRepo->findByField('name', trim($params['name']), ['conf_id']);
        if ($isExist) {
            return ResultHelper::returnFormat('变量名已存在', ResponseCode::ERROR);
        }
        try {
            $config = $configRepo->create($params);
            if ($config) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return ResultHelper::returnFormat('添加配置型成功', ResponseCode::SUCCESS);
            } else {
                $configRepo->rollBack();
                return ResultHelper::returnFormat('添加配置项失败', ResponseCode::ERROR);
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return ResultHelper::returnFormat($exception->getMessage(), ResponseCode::ERROR);
        }
    }

    /**
     * 更新配置项
     * @param Request $request
     * @param ConfigRepo $configRepo
     * @return array
     */
    public function update(Request $request, ConfigRepo $configRepo)
    {
        $configRepo->clearConfig();
        $params = $request->all();
        if (!$params) {
            return ResultHelper::returnFormat('没有修改项', ResponseCode::ERROR);
        }
        $list = $configRepo->getConfigList();
        $updateData = [];
        foreach ($list as $key => $item) {
            if (array_key_exists($item['name'], $params)) {
                $updateData[] = [
                    'conf_id' => $item['conf_id'],
                    'value' => $configRepo->setValueAttributes($key, $params[$key], $item),
                ];
            }
        }
        if (!$updateData) {
            return ResultHelper::returnFormat('没有修改项', ResponseCode::ERROR);
        }
        try {
            $flag = $configRepo->updateBatch($updateData);
            if ($flag) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return ResultHelper::returnFormat('修改成功', ResponseCode::SUCCESS);
            } else {
                $configRepo->rollBack();
                return ResultHelper::returnFormat('修改失败', ResponseCode::ERROR);
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return ResultHelper::returnFormat($exception->getMessage(), ResponseCode::ERROR);
        }
    }

    /**
     * 删除配置项
     * @param int $confId
     * @param ConfigRepo $configRepo
     * @return array
     */
    public function destroy(int $confId, ConfigRepo $configRepo)
    {
        $config = $configRepo->getByPkId($confId);
        if (!$config) {
            return ResultHelper::returnFormat('配置信息不存在', ResponseCode::ERROR);
        }
        try {
            if ($config->delete()) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return ResultHelper::returnFormat('删除成功');
            } else {
                $configRepo->rollBack();
                return ResultHelper::returnFormat('删除失败', ResponseCode::ERROR);
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return ResultHelper::returnFormat($exception->getMessage(), ResponseCode::ERROR);
        }
    }
}
