<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\System\DictTypeRequest;
use App\Services\Repositories\System\DictionaryTypeRepo;
use Illuminate\Database\QueryException;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\StrHelper;

/**
 * 数据字典
 * Class DictionaryType
 * @package App\Http\Controllers\Manage\V1
 */
class DictionaryType extends ApiController
{

    /**
     * 列表
     * @param DictionaryTypeRepo $dictRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DictionaryTypeRepo $dictRepo)
    {
        return $this->success($dictRepo->lists());
    }

    /**
     * 新增
     * @param DictTypeRequest $request
     * @param DictionaryTypeRepo $dictTypeRepo
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function store(DictTypeRequest $request, DictionaryTypeRepo $dictTypeRepo)
    {
        $params = $request->all();
        $isJson = StrHelper::isJson(stripslashes($params['expand']), true);
        if (!$isJson) {
            return $this->badSuccessRequest('扩展条件表单格式有误');
        }
        $data = [
            'type_key' => FiltersHelper::stringSpecialHtmlFilter(trim($params['type_key'])),
            'type_name' => FiltersHelper::stringSpecialHtmlFilter(trim($params['type_name'])),
            'expand' => json_decode(stripslashes($params['expand']), true)
        ];

        $dictTypeRepo->transaction();
        try {
            $dict = $dictTypeRepo->create($data);
            if ($dict) {
                $dictTypeRepo->clearCache();
                $dictTypeRepo->commit();
                return $this->success($dict,'新增成功');
            }
            $dictTypeRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $dictTypeRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新
     * @param int $id
     * @param DictTypeRequest $request
     * @param DictionaryTypeRepo $dictTypeRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, DictTypeRequest $request, DictionaryTypeRepo $dictTypeRepo)
    {
        $dictType = $dictTypeRepo->getByPkId($id);
        if (!$dictType) {
            return $this->badSuccessRequest('该类型不存在');
        }
        $params = $request->all();
        $isJson = StrHelper::isJson(stripslashes($params['expand']), true);
        if (!$isJson) {
            return $this->badSuccessRequest('扩展条件表单格式有误');
        }
        $dictType->type_key = FiltersHelper::stringSpecialHtmlFilter(trim($params['type_key']));
        $dictType->type_name = FiltersHelper::stringSpecialHtmlFilter(trim($params['type_name']));
        $dictType->expand = json_decode(stripslashes($params['expand']), true);


        $dictTypeRepo->transaction();
        try {
            if ($dictType->save()) {
                $dictTypeRepo->clearCache();
                $dictTypeRepo->commit();
                return $this->successRequest('更新成功');
            }
            $dictTypeRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $dictTypeRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }


}
