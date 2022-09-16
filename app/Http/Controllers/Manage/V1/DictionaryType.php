<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\Controller;
use App\Http\ResponseCode;
use App\Services\Repositories\System\DictionaryTypeRepo;
use App\Validators\System\DictTypeRequest;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\ResultHelper;
use JoyceZ\LaravelLib\Helpers\StrHelper;

class DictionaryType extends Controller
{

    public function index(DictionaryTypeRepo $dictRepo)
    {
        $dictRepo->clearCache();
        return ResultHelper::returnFormat('success', ResponseCode::SUCCESS, $dictRepo->lists());
    }

    /**
     * 新增
     * @param DictTypeRequest $request
     * @param DictionaryTypeRepo $dictTypeRepo
     * @return array
     */
    public function store(DictTypeRequest $request, DictionaryTypeRepo $dictTypeRepo)
    {
        $params = $request->all();
        $isJson = StrHelper::isJson(stripslashes($params['expand']), true);
        if (!$isJson) {
            return ResultHelper::returnFormat('扩展条件表单格式有误', ResponseCode::ERROR);
        }
        $data = [
            'type_key' => FiltersHelper::stringSpecialHtmlFilter(trim($params['type_key'])),
            'type_name' => FiltersHelper::stringSpecialHtmlFilter(trim($params['type_name'])),
            'expand' => json_decode(stripslashes($params['expand']), true)
        ];
        $dict = $dictTypeRepo->create($data);
        if ($dict) {
            $dictTypeRepo->clearCache();
            return ResultHelper::returnFormat('新增成功', ResponseCode::SUCCESS, $dict);
        }
        return ResultHelper::returnFormat('新增失败', ResponseCode::ERROR);
    }

    /**
     * 更新
     * @param int $id
     * @param DictTypeRequest $request
     * @param DictionaryTypeRepo $dictTypeRepo
     * @return array
     */
    public function update(int $id, DictTypeRequest $request, DictionaryTypeRepo $dictTypeRepo)
    {
        $dictType = $dictTypeRepo->getByPkId($id);
        if (!$dictType) {
            return ResultHelper::returnFormat('该类型不存在', ResponseCode::ERROR);
        }
        $params = $request->all();
        $isJson = StrHelper::isJson(stripslashes($params['expand']), true);
        if (!$isJson) {
            return ResultHelper::returnFormat('扩展条件表单格式有误', ResponseCode::ERROR);
        }
        $dictType->type_key = FiltersHelper::stringSpecialHtmlFilter(trim($params['type_key']));
        $dictType->type_name = FiltersHelper::stringSpecialHtmlFilter(trim($params['type_name']));
        $dictType->expand = json_decode(stripslashes($params['expand']), true);
        if ($dictType->save()) {
            $dictTypeRepo->clearCache();
            return ResultHelper::returnFormat('更新成功', ResponseCode::SUCCESS, $dictType);
        }
        return ResultHelper::returnFormat('更新失败', ResponseCode::ERROR);
    }


}
