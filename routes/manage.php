<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Manage\\V1',
    'prefix' => 'v1',
    'middleware' => ['manage.log']
], function ($router) {
    $router->get('/passport/captcha', 'Passport@captcha')->name('manage.passport.captcha');
    $router->post('/passport/login', 'Passport@login')->name('manage.passport.login');
    $router->get('/region/build', 'Region@buildLocal')->name('manage.region.buildLocal');//菜单权限列表
    $router->group([
        'middleware' => ['jwt.role:admin'],
    ], function ($router) {
        $router->put('/passport/refreshToken', 'Passport@refreshToken')->name('manage.passport.refreshToken');//刷新token
        $router->post('/passport/logout', 'Passport@logout')->name('manage.passport.logout');//退出登录
        //个人信息
        $router->get('/profile', 'Profile@index')->name('manage.profile.index');
        $router->put('/profile/update', 'Profile@update')->name('manage.profile.update');
        $router->get('/profile/rules', 'Profile@rules')->name('manage.profile.rules');

        //中间件进行权限认证
        $router->group([
            'middleware' => 'rbac.admin.permissiion',
        ], function ($router) {
            //菜单
            $router->get('/menu', 'Menu@index')->name('manage.menu.index');//菜单权限列表
            $router->get('/menu/read/{id}', 'Menu@read')->name('manage.menu.read');//详情
            $router->post('/menu/store', 'Menu@store')->name('manage.menu.store');//提交菜单数据
            $router->put('/menu/update/{id}', 'Menu@update')->name('manage.menu.update');//更新菜单
            $router->delete('/menu/delete/{id}', 'Menu@destroy')->name('manage.menu.destroy');//删除菜单
            $router->get('/menu/power', 'Menu@power')->name('manage.menu.power');//获取route别名权限
            //角色
            $router->get('/role', 'Role@index')->name('manage.role.index');//菜单权限列表
            $router->get('/role/read/{id}', 'Role@read')->name('manage.role.read');//详情
            $router->get('/role/list', 'Role@lists')->name('manage.role.list');//列表
            $router->post('/role/store', 'Role@store')->name('manage.role.store');//提交菜单数据
            $router->put('/role/update/{id}', 'Role@update')->name('manage.role.update');//更新菜单
            $router->delete('/role/delete/{id}', 'Role@destroy')->name('manage.role.destroy');//删除菜单
            $router->post('role/modify', 'Role@modifyFiled')->name('manage.role.modifyFiled');//快捷修改
            //部门
            $router->get('/dept', 'Dept@index')->name('manage.dept.index');//用户列表
            $router->get('/dept/read/{id}', 'Dept@read')->name('manage.dept.read');//详情
            $router->post('/dept/store', 'Dept@store')->name('manage.dept.store');//新增用户
            $router->put('/dept/update/{id}', 'Dept@update')->name('manage.dept.update');//更新用户
            $router->delete('/dept/delete/{id}', 'Dept@destroy')->name('manage.dept.destroy');//删除
            //后台用户
            $router->get('/account', 'Manage@index')->name('manage.account.index');//用户列表
            $router->get('/account/read/{id}', 'Manage@read')->name('manage.account.read');//详情
            $router->post('/account/store', 'Manage@store')->name('manage.account.store');//新增用户
            $router->put('/account/update/{id}', 'Manage@update')->name('manage.account.update');//更新用户
            $router->delete('/account/delete/{id}', 'Manage@destroy')->name('manage.account.destroy');//删除
            //请求日志
            $router->get('/log', 'Log@index')->name('manage.log.index');//请求日志
            $router->delete('/log/delete/{id}', 'Log@destroy')->name('manage.log.destroy');//删除
            $router->post('/log/delete/batch', 'Log@batchDel')->name('manage.log.batchDel');//批量删除
            //附件
            $router->get('/album/category', 'Album@category')->name('manage.album.category');
            $router->post('/album/store', 'Album@store')->name('manage.album.store');//新增分类
            $router->put('/album/update/{id}', 'Album@update')->name('manage.album.update');//新增分类
            $router->delete('/album/delete/{id}', 'Album@destroy')->name('manage.album.destroy');//删除
            $router->post('/album/upload', 'Album@upload')->name('mange.album.upload');//上传
            $router->post('/album/modify', 'Album@modifyFiled')->name('manage.album.modifyFiled');//快捷修改
            $router->get('/album/file/page', 'Album@getFilePage')->name('manage.album.filePage');
            $router->post('/album/file/delete', 'Album@deleteFile')->name('manage.album.deleteFile');//批量删除
            //字典
            $router->get('/dictionary', 'Dictionary@index')->name('manage.dictionary.index');
            $router->post('/dictionary/store', 'Dictionary@store')->name('manage.dictionary.store');
            $router->put('/dictionary/update/{id}', 'Dictionary@update')->name('manage.dictionary.update');
            $router->get('/dictionary/type', 'DictionaryType@index')->name('manage.dictionaryType.index');
            $router->post('/dictionary/type/store', 'DictionaryType@store')->name('manage.dictionaryType.store');//新增分类
            $router->put('/dictionary/type/update/{id}', 'DictionaryType@update')->name('manage.dictionaryType.update');//新增分类

            //系统配置
            $router->get('/routine/config', 'Routine\Config@index')->name('manage.routine.config.index');
            $router->post('/routine/config/update', 'Routine\Config@update')->name('manage.routine.config.update');
            $router->post('/routine/config/store', 'Routine\Config@store')->name('manage.routine.config.store');
            $router->delete('/routine/config/delete/{id}', 'Routine\Config@destroy')->name('manage.routine.config.destroy');//删除

            //前台用户
//        $router->get('/member', 'Member@index')->name('manage.member.index');//前台用户列表
        });

    });
});
Route::fallback(function() {
    return response()->json(['message' => 'Not Found!']);
});
