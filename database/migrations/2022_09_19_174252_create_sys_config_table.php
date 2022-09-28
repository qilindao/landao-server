<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_config', function (Blueprint $table) {
            $table->increments('conf_id');
            $table->string('name', 30)->default('')->comment('变量名');
            $table->string('group', 30)->default('')->comment('分组');
            $table->string('title', 100)->default('')->comment('变量标题');
            $table->string('tip', 100)->default('')->comment('变量描述');
            $table->string('type', 30)->default('')->comment('类型:string,number,radio,checkbox,switch,textarea,array,datetime,date,select,selects');
            $table->text('value')->nullable()->comment('变量值');
            $table->text('content')->nullable()->comment('字典数据');
            $table->string('rule', 100)->default('')->comment('验证规则');
            $table->string('extend')->default('')->comment('扩展属性');
            $table->unsignedTinyInteger('is_del')->default(1)->comment('允许删除:0=否,1=是');
            $table->unsignedInteger('weigh')->default(1)->comment('权重');
            $table->unique('name', 'uk_config_name');
        });
        //表注释
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_config` comment '系统配置'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_config` AUTO_INCREMENT=1001");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_config');
    }
}
