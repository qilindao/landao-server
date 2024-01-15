<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Manage\\V1',
    'prefix' => 'v1',
    'name' => 'manage.',
    'middleware' => ['manage.log', 'api.case.converter']//, 'api.case.converter' 在控制器中实现：蛇形命名自动转换驼峰命名
], function ($router) {
    $router->get('/passport/captcha', 'Passport@captcha')->name('passport.captcha');
    $router->post('/passport/login', 'Passport@login')->name('passport.login');
    $router->get('/region/build', 'Region@buildLocal')->name('region.buildLocal');//菜单权限列表
    $router->group([
        'middleware' => ['jwt.role:admin'],
    ], function ($router) {
        $router->put('/passport/refreshToken', 'Passport@refreshToken')->name('passport.refreshToken');//刷新token
        $router->post('/passport/logout', 'Passport@logout')->name('passport.logout');//退出登录
        //个人信息
        $router->get('/profile', 'Profile@index')->name('profile.index');
        $router->put('/profile/update', 'Profile@update')->name('profile.update');
        $router->get('/profile/rules', 'Profile@rules')->name('profile.rules');

        //中间件进行权限认证
        $router->group([
            'middleware' => 'rbac.admin.permissiion',
        ], function ($router) {
            //菜单
            $router->get('/menu', 'Menu@index')->name('menu.index');//菜单权限列表
            $router->get('/menu/read/{id}', 'Menu@read')->name('menu.read');//详情
            $router->post('/menu/store', 'Menu@store')->name('menu.store');//提交菜单数据
            $router->put('/menu/update/{id}', 'Menu@update')->name('menu.update');//更新菜单
            $router->delete('/menu/delete/{id}', 'Menu@destroy')->name('menu.destroy');//删除菜单
            $router->get('/menu/power', 'Menu@power')->name('menu.power');//获取route别名权限
            $router->post('/menu/modify/{id}', 'Menu@modifyFiled')->where('id', '[0-9]+')->name('menu.modifyFiled');//快捷修改
            //角色
            $router->get('/role', 'Role@index')->name('role.index');//菜单权限列表
            $router->get('/role/read/{id}', 'Role@read')->name('role.read');//详情
            $router->get('/role/list', 'Role@lists')->name('role.list');//列表
            $router->post('/role/store', 'Role@store')->name('role.store');//提交菜单数据
            $router->put('/role/update/{id}', 'Role@update')->name('role.update');//更新菜单
            $router->delete('/role/delete/{id}', 'Role@destroy')->name('role.destroy');//删除菜单
            $router->post('/role/modify/{id}', 'Role@modifyFiled')->where('id', '[0-9]+')->name('role.modifyFiled');//快捷修改
            //部门
            $router->get('/dept', 'Dept@index')->name('dept.index');//用户列表
            $router->get('/dept/read/{id}', 'Dept@read')->name('dept.read');//详情
            $router->post('/dept/store', 'Dept@store')->name('dept.store');//新增用户
            $router->put('/dept/update/{id}', 'Dept@update')->name('dept.update');//更新用户
            $router->delete('/dept/delete/{id}', 'Dept@destroy')->name('dept.destroy');//删除
            $router->post('/dept/modify/{id}', 'Dept@modifyFiled')->where('id', '[0-9]+')->name('dept.modifyFiled');//快捷修改
            //后台用户
            $router->get('/account', 'Manage@index')->name('account.index');//用户列表
            $router->get('/account/read/{id}', 'Manage@read')->name('account.read');//详情
            $router->post('/account/store', 'Manage@store')->name('account.store');//新增用户
            $router->put('/account/update/{id}', 'Manage@update')->name('account.update');//更新用户
            $router->delete('/account/delete/{id}', 'Manage@destroy')->name('account.destroy');//删除
            $router->post('/account/modify/{id}', 'Manage@modifyFiled')->where('id', '[0-9]+')->name('account.modifyFiled');//快捷修改
            //请求日志
            $router->get('/log', 'Log@index')->name('log.index');//请求日志
            $router->delete('/log/delete/{id}', 'Log@destroy')->name('log.destroy');//删除
            $router->post('/log/delete/batch', 'Log@batchDel')->name('log.batchDel');//批量删除
            //附件
            $router->get('/album/category', 'Album@category')->name('album.category');
            $router->post('/album/store', 'Album@store')->name('album.store');//新增分类
            $router->put('/album/update/{id}', 'Album@update')->name('album.update');//新增分类
            $router->delete('/album/delete/{id}', 'Album@destroy')->name('album.destroy');//删除
            $router->post('/album/upload', 'Album@upload')->name('album.upload');//上传
            $router->post('/album/modify', 'Album@modifyFiled')->name('album.modifyFiled');//快捷修改
            $router->get('/album/file/page', 'Album@getFilePage')->name('album.filePage');
            $router->post('/album/file/delete', 'Album@deleteFile')->name('album.deleteFile');//批量删除
            //字典
            $router->get('/dictionary', 'Dictionary@index')->name('dictionary.index');
            $router->post('/dictionary/store', 'Dictionary@store')->name('dictionary.store');
            $router->put('/dictionary/update/{id}', 'Dictionary@update')->name('dictionary.update');
            $router->get('/dictionary/type', 'DictionaryType@index')->name('dictionaryType.index');
            $router->post('/dictionary/type/store', 'DictionaryType@store')->name('dictionaryType.store');//新增分类
            $router->put('/dictionary/type/update/{id}', 'DictionaryType@update')->name('dictionaryType.update');//新增分类

            //系统配置
            $router->get('/routine/config', 'Routine\Config@index')->name('routine.config.index');
            $router->post('/routine/config/update', 'Routine\Config@update')->name('routine.config.update');
            $router->post('/routine/config/store', 'Routine\Config@store')->name('routine.config.store');
            $router->delete('/routine/config/delete/{id}', 'Routine\Config@destroy')->name('routine.config.destroy');//删除

            //前台用户
//        $router->get('/member', 'Member@index')->name('manage.member.index');//前台用户列表
        });

    });
});
Route::fallback(function () {
    return response()->json(['message' => 'Not Found!']);
});
