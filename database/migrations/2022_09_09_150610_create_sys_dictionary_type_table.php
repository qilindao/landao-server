<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysDictionaryTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_dictionary_type', function (Blueprint $table) {
            $table->increments('dict_tid');
            $table->string('type_key')->default('')->comment('标识');
            $table->string('type_name')->default('')->comment('名称');
            $table->json('expand')->nullable()->comment('扩展配置');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedInteger('updated_at')->default(0)->comment('更新时间');

            $table->unique('type_key', 'uk_dict_type_key');
        });
        //表注释
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_dictionary_type` comment '字典类型'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_dictionary_type` AUTO_INCREMENT=1001");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_dictionary_type');
    }
}
