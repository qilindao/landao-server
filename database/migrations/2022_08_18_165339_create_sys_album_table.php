<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_album', function (Blueprint $table) {
            $table->increments('album_id');
            $table->string('album_name',60)->default('')->comment('相册名称');
            $table->string('album_cover',256)->default('')->comment('相册封面图片');
            $table->unsignedInteger('parent_id')->default(0)->comment('上级相册id');
            $table->unsignedInteger('album_sort')->default(0)->comment('排序');
            $table->unsignedTinyInteger('is_default')->default(0)->comment('是否默认[ 0 否 1 是 ]默认相册不可删除，删除其他分组时会将图片转移到默认分组下');
            $table->unsignedInteger('created_at')->default(0)->comment('创建时间');
            $table->unsignedInteger('updated_at')->default(0)->comment('更新时间');
        });
        //表注释
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_album` comment '附件专辑'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `sys_album` AUTO_INCREMENT=1001");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_album');
    }
}
