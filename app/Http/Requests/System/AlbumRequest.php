<?php


namespace App\Http\Requests\System;


use Illuminate\Validation\Rule;
use JoyceZ\LaravelLib\Validation\BaseRequest;

class AlbumRequest extends BaseRequest
{
    /**
     *  定义针对 AlbumController->store( )的验证规则
     * @return array
     */
    public function getRulesByStore()
    {
        return [
            'album_name' => 'required',
            'parent_id' => 'required',
        ];
    }

    /**
     *  定义针对 AlbumController->store( )的验证规则
     * @return array
     */
    public function getRulesByUpload()
    {
        return [
            'album_name' => 'required',
        ];
    }

    /**
     *  定义针对 AlbumController->modifyFiled( )的验证规则
     * @return array
     */
    public function getRulesByModifyFiled()
    {
        return [
            'file_id' => 'required|numeric|min:0|not_in:0',
            'field_name' => 'required',
            'field_value' => 'required'
        ];
    }


    /**
     * 统一定义验证规则的自定义错误消息。
     * @return array
     */
    public function messages()
    {
        return [
            'album_name.required' => '请输入分区名称',
            'parent_id.required' => '请选择上级分组',
            'file_id.min' => '文件ID格式有误',
            'file_id.not_in' => '文件ID格式有误',
            'field_name.required' => '缺少字段名',
            'field_value.required' => '缺少字段值',
        ];
    }
}
