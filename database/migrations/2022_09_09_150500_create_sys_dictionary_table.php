<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysDictionaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_dictionary', function (Blueprint $table) {
            $table->id('dict_id');
            $table->unsignedInteger('type_id')->index('idx_dictionary_type_id')->default(0)->comment('字典类型id');
            $table->string('label')->default('')->comment('');
            $table->unsignedTinyInteger('is_enable')->default(1)->comment('是否启用[1:启用;0:停用]');
            $table->unsignedInteger('order_num')->default(0)->comment('排序');
            $table->string('remark')->default('')->comment('备注');
            $table->json('expand')->nullable()->comment('扩展配置');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedInteger('updated_at')->default(0)->comment('更新时间');
            $table->unsignedInteger('deleted_at')->default(0)->comment('删除时间');
        });
        //表注释
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_dictionary` comment '字典类型'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_dictionary` AUTO_INCREMENT=10001");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_dictionary');
    }
}
