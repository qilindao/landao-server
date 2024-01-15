<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\ApiController;
use App\Http\Requests\System\AlbumRequest;
use App\Services\Repositories\System\AlbumFileRepo;
use App\Services\Repositories\System\AlbumRepo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use JoyceZ\LaravelLib\Helpers\FiltersHelper;
use JoyceZ\LaravelLib\Helpers\TreeHelper;

/**
 * Class Album
 * @author ZhangWei
 * @copyright 2019-2029 https://github.com/Joycezhangw, 保留所有权利。
 * @inheritDoc https://qilindao.github.io/docs/
 * @package App\Http\Controllers\Manage\V1
 */
class Album extends ApiController
{

    /**
     * 相册分类
     * @param AlbumRepo $albumRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function category(AlbumRepo $albumRepo)
    {
        $lists = $albumRepo->orderBy('created_at', 'asc')
            ->all(['album_id', 'album_name', 'parent_id', 'album_sort', 'is_default'])
            ->toArray();
        array_unshift($lists, ['album_id' => 0, 'album_name' => '全部文件', 'parent_id' => 0, 'album_sort' => 0, 'is_default' => true]);
        return $this->success(TreeHelper::listToTree($lists, 0, 'album_id', 'parent_id'));
    }

    /**
     * 提交相册分类
     * @param AlbumRequest $request
     * @param AlbumRepo $albumRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function store(AlbumRequest $request, AlbumRepo $albumRepo)
    {
        $params = [
            'album_name' => FiltersHelper::stringFilter($request->post('album_name')),
            'parent_id' => intval($request->post('parent_id')),
            'is_default' => 0
        ];
        $albumRepo->transaction();
        try {
            $album = $albumRepo->create($params);
            if ($album) {
                $albumRepo->commit();
                $params['album_id'] = $album->album_id;
                return $this->success($params, '添加分组成功');
            }
            $albumRepo->rollBack();
            return $this->badSuccessRequest('新增失败');
        } catch (QueryException $exception) {
            $albumRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 更新相册分类
     * @param int $id
     * @param AlbumRequest $request
     * @param AlbumRepo $albumRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id, AlbumRequest $request, AlbumRepo $albumRepo)
    {
        if ($id <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $album = $albumRepo->getByPkId($id);
        if (!$album) {
            return $this->badSuccessRequest('相册分组不存在');
        }
        $albumRepo->transaction();
        try {
            $album->album_name = FiltersHelper::stringFilter($request->post('album_name'));
            if ($album->save()) {
                $albumRepo->commit();
                return $this->successRequest('更新成功');
            }
            $albumRepo->rollBack();
            return $this->badSuccessRequest('更新失败');
        } catch (QueryException $exception) {
            $albumRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 删除相册分类
     * @param int $id
     * @param AlbumRepo $albumRepo
     * @param AlbumFileRepo $albumFileRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function destroy(int $id, AlbumRepo $albumRepo, AlbumFileRepo $albumFileRepo)
    {
        $album = $albumRepo->getByPkId($id);
        if (!$album) {
            return $this->badSuccessRequest('该分组不存在');
        }
        $albumRepo->transaction();
        try {
            if ($album->delete()) {
                //将相册下的分类移到默认分类下
                if ($albumFileRepo->count(['album_id' => $id], 'file_id') > 0) {
                    $default = $albumRepo->findByField('is_default', 1, ['album_id']);
                    $albumFileRepo->updateByWhere(['album_id' => $id], ['album_id' => $default->album_id]);
                }
                $album->commit();
                return $this->successRequest('删除成功');
            }
            $albumRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $albumRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 上传文件
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request, AlbumFileRepo $albumFileRepo)
    {
        $ret = $albumFileRepo->doLocalUpload($request);
        if ($ret['code'] == 200) {
            return $this->success($ret['data'], '上传成功');
        }
        return $this->badSuccessRequest($ret['message']);
    }

    /**
     * 快捷修改指定表字段值
     * @param AlbumRequest $request
     * @param AlbumFileRepo $albumFileRepo
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function modifyFiled(AlbumRequest $request, AlbumFileRepo $albumFileRepo)
    {
        $id = intval($request->post('file_id'));
        if ($id <= 0) {
            return $this->badSuccessRequest('缺少必要的参数');
        }
        $fieldName = (string)$request->post('field_name');
        $fieldValue = $request->post('field_value');
        $albumFileRepo->transaction();
        try {
            $ret = $albumFileRepo->updateFieldById($id, $fieldName, $fieldValue);
            if ($ret) {
                $albumFileRepo->commit();
                return $this->successRequest('修改成功');
            }
            $albumFileRepo->rollBack();
            return $this->badSuccessRequest('修改失败');
        } catch (QueryException $exception) {
            $albumFileRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

    /**
     * 获取附件列表
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFilePage(Request $request, AlbumFileRepo $albumFileRepo)
    {
        $ret = $albumFileRepo->getPage($request->all());
        return $this->success([
            'pagination' => [
                'total' => $ret['total'],
                'page_size' => $ret['per_page'],
                'current_page' => $ret['current_page'],
            ],
            'list' => $ret['data']
        ]);
    }

    /**
     * 批量删除图片
     * @param Request $request
     * @param AlbumFileRepo $albumFileRepo
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function deleteFile(Request $request, AlbumFileRepo $albumFileRepo)
    {
        if (!$request->all()) {
            return $this->badSuccessRequest('文件不存在');
        }
        $albumFileRepo->transaction();
        try {
            if ($albumFileRepo->deleteByIds($request->all())) {
                $albumFileRepo->commit();
                return $this->successRequest('删除成功');
            }
            $albumFileRepo->rollBack();
            return $this->badSuccessRequest('删除失败');
        } catch (QueryException $exception) {
            $albumFileRepo->rollBack();
            return $this->badSuccessRequest($exception->getMessage());
        }
    }

}
