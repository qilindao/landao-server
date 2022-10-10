<?php


namespace App\Http\Controllers\Manage\V1\Routine;


use App\Http\Controllers\ApiController;
use App\Http\Requests\System\ConfigRequest;
use App\Services\Enums\System\ConfigTypeEnum;
use App\Services\Repositories\System\ConfigRepo;
use App\Services\Repositories\System\RegionRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Config extends ApiController
{
    public function index(ConfigRepo $configRepo)
    {
        $configGroup = $configRepo->getConfigValueByName('config_group');
        $configType = ConfigTypeEnum::getKeys();
        $list = $configRepo->getConfigListByGroup();
        return $this->success(compact('configGroup', 'configType', 'list'));
    }


    /**
     * 添加配置项
     * @param ConfigRequest $request
     * @param ConfigRepo $configRepo
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function store(ConfigRequest $request, ConfigRepo $configRepo)
    {
        $params = $request->all();
        $isExist = $configRepo->findByField('name', trim($params['name']), ['conf_id']);
        if ($isExist) {
            return $this->badSuccessRequest('变量名已存在');
        }
        $configRepo->transaction();
        try {
            $config = $configRepo->create($params);
            if ($config) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return $this->successRequest('添加配置型成功');
            } else {
                $configRepo->rollBack();
                return $this->badSuccessRequest('添加配置项失败');
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新配置项
     * @param Request $request
     * @param ConfigRepo $configRepo
     * @param RegionRepo $regionRepo
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, ConfigRepo $configRepo, RegionRepo $regionRepo)
    {
        $configRepo->clearConfig();
        $params = $request->all();
        if (!$params) {
            return $this->badSuccessRequest('没有修改项');
        }
        $list = $configRepo->getConfigList();
        $updateData = [];
        $regionId = 0;
        $regionData = [];
        foreach ($list as $key => $item) {
            if (array_key_exists($item['name'], $params)) {
                if ($item['type'] == 'region') {
                    $regionId = $item['conf_id'];
                    $region = Arr::flatten($params[$key]);
                    $areaIds = array_unique($region);
                    $regionData = [
                        'value' => $params[$key],//$configRepo->setValueAttributes($key, $params[$key], $item),
                        'content' => $regionRepo->getRegionTreeByIds($areaIds),
                    ];
                } else {
                    $updateData[] = [
                        'conf_id' => $item['conf_id'],
                        'value' => $configRepo->setValueAttributes($key, $params[$key], $item),
                    ];
                }
            }
        }
        if (!$updateData) {
            return $this->badSuccessRequest('没有修改项');
        }

        $configRepo->transaction();
        try {
            $regionFlag = true;
            //TODO：混合修改，这里会出现修改失败
            if ($regionData && $regionId > 0) {
                $regionFlag = $configRepo->updateById($regionData, $regionId);
            }
            $flag = $configRepo->updateBatch($updateData);
            if ($flag || $regionFlag) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return $this->successRequest('修改成功');
            } else {
                $configRepo->rollBack();
                return $this->badSuccessRequest('修改失败或值未变');
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除配置项
     * @param int $confId
     * @param ConfigRepo $configRepo
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function destroy(int $confId, ConfigRepo $configRepo)
    {
        $config = $configRepo->getByPkId($confId);
        if (!$config) {
            return $this->badSuccessRequest('配置信息不存在');
        }
        $configRepo->transaction();
        try {
            if ($config->delete()) {
                $configRepo->commit();
                $configRepo->clearConfig();
                return $this->successRequest('删除成功');
            } else {
                $configRepo->rollBack();
                return $this->badSuccessRequest('删除失败');
            }
        } catch (QueryException $exception) {
            $configRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }
}
