<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\Controller;
use App\Http\ResponseCode;
use App\Services\Repositories\System\DictionaryRepo;
use App\Validators\System\DictRequest;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\ResultHelper;

class Dictionary extends Controller
{
    public function index(Request $request, DictionaryRepo $dictionaryRepo)
    {
        $params = $request->all();
        $ret = $dictionaryRepo->where(function ($query) use ($params) {
            $query->where('type_id', intval($params['type_id']));

        })->paginate(['*'], isset($params['page_size']) ? $params['page_size'] : config('landao.paginate.page_size'))->toArray();
        return ResultHelper::returnFormat('success', 200, [
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
        $dict = $dictRepo->create($data);
        if ($dict) {
            return ResultHelper::returnFormat('新增字典成功', ResponseCode::SUCCESS, $dict);
        }
        return ResultHelper::returnFormat('新增字典失败，稍后再试', ResponseCode::ERROR, $dict);
    }

    public function update(int $dictId, DictRequest $request, DictionaryRepo $dictRepo)
    {
        $dict = $dictRepo->getByPkId($dictId);
        if (!$dict) {
            return ResultHelper::returnFormat('字典不存在', ResponseCode::ERROR);
        }
        $params = $request->all();

        $dict->label = FiltersHelper::stringSpecialHtmlFilter(trim($params['label']));
        $dict->is_enable = $params['is_enable'];
        $dict->expand = isset($params['expand']) ? $params['expand'] : [];
        if ($dict->save()) {
            return ResultHelper::returnFormat('更新成功', ResponseCode::SUCCESS, $dict);
        }
        return ResultHelper::returnFormat('更新失败', ResponseCode::ERROR);
    }
}
