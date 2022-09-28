<?php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SysConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //绑定角色
        DB::table('sys_config')->insert([
            [
                'name' => 'config_group',
                'group' => 'basics',
                'title' => '配置分组',
                'tip' => '',
                'type' => 'array',
                'value' => '[{"key":"basics","value":"\u57fa\u7840\u914d\u7f6e","is_del":false}]',
                'content' => '',
                'rule' => 'required',
                'extend' => '',
                'is_del' => 0,
                'weigh' => 0
            ],
            [
                'name' => 'site_name',
                'group' => 'basics',
                'title' => '站点名称',
                'tip' => '',
                'type' => 'string',
                'value' => 'Landao Admin',
                'content' => '',
                'rule' => 'required',
                'extend' => '',
                'is_del' => 0,
                'weigh' => 1
            ],
            [
                'name' => 'record_number',
                'group' => 'basics',
                'title' => '域名备案号',
                'tip' => '',
                'type' => 'string',
                'value' => '',
                'content' => '',
                'rule' => 'required',
                'extend' => '',
                'is_del' => 0,
                'weigh' => 1
            ]
        ]);
    }
}
