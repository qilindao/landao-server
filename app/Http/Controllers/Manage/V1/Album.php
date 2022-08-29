<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\Controller;
use App\Http\ResponseCode;
use App\Services\Repositories\System\AlbumFileRepo;
use App\Services\Repositories\System\AlbumRepo;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\ResultHelper;
use JoyceZ\LaravelLib\Helpers\TreeHelper;

class Album extends Controller
{
    /**
     * 相册分类
     * @param AlbumRepo $albumRepo
     * @return array
     */
    public function category(AlbumRepo $albumRepo)
    {
        $lists = $albumRepo->orderBy('created_at', 'asc')
            ->all(['album_id', 'album_name', 'parent_id', 'album_sort', 'is_default'])
            ->toArray();
        array_unshift($lists, ['album_id' => 0, 'album_name' => '全部文件', 'parent_id' => 0, 'album_sort' => 0, 'is_default' => true]);
        return ResultHelper::returnFormat('success', ResponseCode::SUCCESS, TreeHelper::listToTree($lists, 0, 'album_id', 'parent_id'));
    }

    /**
     * 提交相册分类
     * @param Request $request
     * @param AlbumRepo $albumRepo
     * @return array
     */
    public function store(Request $request, AlbumRepo $albumRepo)
    {
        $params = [
            'album_name' => FiltersHelper::stringFilter($request->post('album_name')),
            'parent_id' => intval($request->post('parent_id')),
            'is_default' => 0
        ];
        $album = $albumRepo->create($params);
        if ($album) {
            $params['album_id'] = $album->album_id;
            return ResultHelper::returnFormat('添加分组成功', 200, $params);
        }
        return ResultHelper::returnFormat('网络繁忙，请稍后再试', -1);
    }

    /**
     * 更新相册分类
     * @param int $id
     * @param Request $request
     * @param AlbumRepo $albumRepo
     * @return array
     */
    public function update(int $id, Request $request, AlbumRepo $albumRepo)
    {
        if ($id <= 0) {
            return ResultHelper::returnFormat('缺少必要的参数', -1);
        }
        $album = $albumRepo->getByPkId($id);
        if (empty($album)) {
            return ResultHelper::returnFormat('该分组不存在', -1);
        }
        $album->album_name = FiltersHelper::stringFilter($request->post('album_name'));
        if ($album->save()) {
            return ResultHelper::returnFormat('修改分组名称成功', 200, $album);
        }
        return ResultHelper::returnFormat('网络繁忙，请稍后再试', -1);
    }

    /**
     * 删除相册分类
     * @param int $id
     * @param Request $request
     * @param AlbumRepo $albumRepo
     * @param AlbumFileRepo $albumFileRepo
     * @return array
     */
    public function destroy(int $id, Request $request, AlbumRepo $albumRepo, AlbumFileRepo $albumFileRepo)
    {
        if ($id <= 0) {
            return ResultHelper::returnFormat('缺少必要的参数', -1);
        }
        $album = $albumRepo->getByPkId($id);
        if (empty($album)) {
            return ResultHelper::returnFormat('该分组不存在', -1);
        }
        if ($album->delete()) {
            //将相册下的分类移到默认分类下
            if ($albumFileRepo->count(['album_id' => $id], 'file_id') > 0) {
                $default = $albumRepo->findByField('is_default', 1, ['album_id']);
                $albumFileRepo->updateByWhere(['album_id' => $id], ['album_id' => $default->album_id]);
            }
            return ResultHelper::returnFormat('删除分组成功', 200);
        }
        return ResultHelper::returnFormat('网络繁忙，请稍后再试', -1);
    }

    /**
     * 上传文件
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return array
     */
    public function upload(Request $request, AlbumFileRepo $albumFileRepo)
    {
        return $albumFileRepo->doLocalUpload($request);
    }

    /**
     * 快捷修改指定表字段值
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo )
     * @return array|mixed
     */
    public function modifyFiled(Request $request, AlbumFileRepo $albumFileRepo)
    {
        $id = intval($request->post('file_id'));
        if ($id <= 0) {
            return ResultHelper::returnFormat('缺少必要的参数', ResponseCode::ERROR);
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $ret = $albumFileRepo->updateFieldById($id, $fieldName, $fieldValue);
        if ($ret) {
            return ResultHelper::returnFormat('修改成功', ResponseCode::SUCCESS);
        }
        return ResultHelper::returnFormat('服务器繁忙，请稍后再试', ResponseCode::ERROR);
    }

    /**
     * 获取附件列表
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return array
     */
    public function getFilePage(Request $request, AlbumFileRepo $albumFileRepo)
    {
        $ret = $albumFileRepo->getPage($request->all());
        return ResultHelper::returnFormat('success', ResponseCode::SUCCESS, [
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $ret['data']
        ]);
    }

    /**
     * 批量删除
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return array
     */
    public function deleteFile(Request $request,AlbumFileRepo $albumFileRepo){
        if (!$request->all()) {
            return ResultHelper::returnFormat('文件不存在',ResponseCode::ERROR);
        }
        if ($albumFileRepo->deleteByIds($request->all())) {
            return ResultHelper::returnFormat('删除成功');
        }
        return ResultHelper::returnFormat('网络繁忙，请稍后再试...', ResponseCode::ERROR);
    }

}
