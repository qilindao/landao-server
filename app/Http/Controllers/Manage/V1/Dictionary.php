<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\System\DictRequest;
use App\Services\Repositories\System\DictionaryRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;

class Dictionary extends ApiController
{
    public function index(Request $request, DictionaryRepo $dictionaryRepo)
    {
        $params = $request->all();
        $ret = $dictionaryRepo->where(function ($query) use ($params) {
            $query->where('type_id', intval($params['type_id']));

        })->paginate(['*'], isset($params['page_size']) ? $params['page_size'] : config('landao.paginate.page_size'))->toArray();
        return $this->success([
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $ret['data']
        ]);
    }

    public function store(DictRequest $request, DictionaryRepo $dictRepo)
    {
        $params = $request->all();
        $data = [
            'label' => FiltersHelper::filterXSS(trim($params['label'])),
            'type_id' => FiltersHelper::filterXSS(trim($params['type_id'])),
            'is_enable' => intval($params['is_enable']),
            'expand' => isset($params['expand']) ? $params['expand'] : []
        ];
        $dictRepo->transaction();
        try {
            if ($dictRepo->create($data)) {
                $dictRepo->commit();
                return $this->successRequest('新增成功');
            }
            $dictRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $dictRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    public function update(int $dictId, DictRequest $request, DictionaryRepo $dictRepo)
    {
        $dict = $dictRepo->getByPkId($dictId);
        if (!$dict) {
            return $this->badSuccessRequest('字典不存在');
        }
        $params = $request->all();

        $dict->label = FiltersHelper::stringSpecialHtmlFilter(trim($params['label']));
        $dict->is_enable = $params['is_enable'];
        $dict->expand = isset($params['expand']) ? $params['expand'] : [];

        $dictRepo->transaction();
        try {
            if ($dict->save()) {
                $dictRepo->commit();
                return $this->successRequest('更新成功');
            }
            $dictRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $dictRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }
}
