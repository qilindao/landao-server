<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_menu', function (Blueprint $table) {
            $table->increments('menu_id');
            $table->string('name', 124)->default('')->comment('目录、菜单英文释义');
            $table->string('title', 124)->default('')->comment(' 菜单、按钮中文释义');
            $table->string('icon', 64)->default('')->comment('目录、菜单前置图标');
            $table->string('module', 10)->default('ADMIN')->comment('所属前端');
            $table->unsignedTinyInteger('type')->default('0')->comment('菜单类型[0:目录;1:菜单;2:权限]');
            $table->string('redirect')->default('')->comment('重定向路径');
            $table->string('path')->default('')->comment('菜单路由路径');
            $table->unsignedInteger('parent_id')->default(0)->comment('上级ID');
            $table->string('component')->default('')->comment('组件路径');
            $table->string('auth_code')->default('')->comment('权限编码，主要用途是按钮级别权限配置');
            $table->unsignedMediumInteger('order_no')->default(0)->comment('展示先后顺序，数字越小越靠前，默认为零');
            $table->unsignedTinyInteger('keep_alive')->default(0)->comment('决定路由是否开启keep-alive，默认开启[0:否;1:是]');
            $table->unsignedTinyInteger('hidden')->default(0)->comment('决定该路由是否在菜单上进行展示');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedInteger('updated_at')->default(0)->comment('更新时间');

            //路由名唯一索引
            $table->unique('name', 'uk_sys_menu_name');
        });
        //表注释
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_menu` comment 'B端菜单权限表'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_menu` AUTO_INCREMENT=1001");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_menu');
    }
}
