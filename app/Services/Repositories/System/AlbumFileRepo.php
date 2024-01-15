<?php
declare(strict_types=1);

namespace App\Services\Repositories\System;

use App\Services\Enums\System\AlbumFileTypeEnum;
use Illuminate\Support\Facades\Storage;
use JoyceZ\LaravelLib\Helpers\ResultHelper;
use JoyceZ\LaravelLib\Repositories\BaseRepository;
use App\Services\Models\System\AlbumFileModel;

/**
 * 附件信息
 *
 * @author joyecZhang <https://qilindao.github.io/docs/backend/>
 *
 * Class AlbumFileRepo
 * @package App\Services\Repositories\System;
 */
class AlbumFileRepo extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return AlbumFileModel::class;
    }

    /**
     * 上传文件到本地
     * @param $request
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \JoyceZ\LaravelLib\Exceptions\RepositoryException
     */
    public function doLocalUpload($request): array
    {
        $file = $request->file('file');
        $md5 = md5_file($file->getRealPath());
        $name = $file->getClientOriginalName();
        // getClientOriginalExtension 对恶意渗透无效，只要将html的扩展名修改成jpg，还是能绕开验证
//        $ext = $file->getClientOriginalExtension();
        $ext = strtolower($file->guessExtension());
        //getClientMimeType 对恶意渗透无效，只要将html的扩展名修改成jpg，还是能绕开验证
        $mimeType = $file->getClientMimeType();
        $fileTypes = ['jpg','jpeg','gif','bmp','png','doc','docx','xls','xlsx','ppt','pptx','pdf','mp4','3gp','m3u8','webm','wav','mp3','ogg',];
        if (!in_array($ext, $fileTypes)) {
            return ResultHelper::returnFormat('上传的文件格式有误', -1);
        }
        $allowedMimeType = [
            'image/gif', 'image/jpeg', 'image/png', 'image/webp',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.ms-powerpoint', 'application/pdf',
            'video/mpeg', 'video/mp4', 'video/webm', 'video/ogg', 'video/3gpp','audio/mpeg','audio/wav','audio/ogg'];
        if (!in_array($mimeType, $allowedMimeType)) {
            return ResultHelper::returnFormat('请上传正确的文件格式', -1);
        }
//        $hasFile = $this->model->where('file_md5', $md5)->where('original_name', $name)->where('file_ext', $ext)->first();

        // 不存在文件，则插入数据库
//        if (empty($hasFile)) {

            $file_type = $request->file_type ? $request->file_type : 'image';
            $folder = $request->folder ? $request->folder : 'image';
            $now_time = time();
            $path = $file->store("public/uploads/$file_type/$folder/" . date("Y", $now_time) . '/' . date("m", $now_time) . '/' . date("d", $now_time));
            // 获取文件url，用于外部访问
            $url = Storage::url($path);
            // 获取文件大小
            $size = Storage::size($path);
//            if ($file_type === 'image') {
//                $imgSize = getimagesize(storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $path);
//            } else {
//                $imgSize = [0, 0];
//            }
            // 插入数据库
            $picture = $this->create([
                'album_id' => $request->album_id ? $request->album_id : 10001,
                'file_md5' => $md5,
                'file_name' => $name,
                'original_name' => $name,
                'file_path' => $path,
                'file_size' => $size,
                'file_type' => $file_type,
                'mime_type' => $mimeType,
                'file_ip' => $request->ip(),
                'file_ext' => $ext
            ]);
            $fileId = $picture->file_id;
//        } else {
//            $fileId = $hasFile->file_id;
//
//            if (strpos($hasFile->file_path, 'http') !== false) {
//                $url = $hasFile->file_path;
//            } else {
//                // 获取文件url，用于外部访问
//                $url = Storage::url($hasFile->file_path);
//            }
//            $path = $hasFile->file_path;
//            $size = $hasFile->file_size;
//        }

        $result['file_id'] = $fileId;
        $result['file_name'] = $name;
        $result['file_url'] = asset($url);
        $result['file_path'] = $path;
        $result['file_size'] = $size;
        return ResultHelper::returnFormat('上传成功', 200, $result);
    }

    /**
     * 根据指定的条件，获取附件列表
     * @param array $params
     * @return array
     */
    public function getPage(array $params): array
    {
        $lists = $this->model->where(function ($query) use ($params) {
            if (isset($params['search_text']) && $params['search_text'] != '') {
                $query->where('file_name', 'like', '%' . $params['search_text'] . '%');
            }
            if (isset($params['created_time']) && $params['created_time'] != '') {
                $date = explode('至', $params['created_time']);
                $query->where('created_at', '>=', strtotime(trim($date[0])))->where('created_at', '<=', strtotime(trim($date[1])));
            }
            if (isset($params['file_type']) && trim($params['file_type']) !== '') {
                $query->where('file_type', in_array($params['file_type'], AlbumFileTypeEnum::FILE_TYPE) ? $params['file_type'] : 'image');
            }
            if (isset($params['album_id']) && intval($params['album_id']) > 0) {
                $query->where('album_id', $params['album_id']);
            }
        })
            ->select(['file_id', 'file_name', 'file_path', 'file_ext', 'file_type', 'mime_type', 'file_size'])
            ->orderBy('created_at', 'desc')
            ->paginate(isset($params['page_size']) ? $params['page_size'] : config('tianyin.paginate.page_size'));
        return $lists->toArray();
    }
}
