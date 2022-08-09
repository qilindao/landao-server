<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * 基础菜单
 * Class SysManageMenuSeeder
 * @package Database\Seeders
 */
class SysManageMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10001', '0', 'systemManage', '系统管理', '/sys', '0', 'icon-system', '0', '1', '1', '', '', '', '1630393533', '1630393533');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10002', '10001', 'authManage', '权限管理', '/sys/auth', '0', 'icon-auth', '0', '1', '1', '', '', '', '1630393533', '1630393533');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10003', '10002', 'menuManage', '菜单列表', '/sys/menu', '1', 'icon-menu', '0', '1', '1', 'views/system/menu/index.vue', '', '', '1630393533', '1631238025');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10004', '10003', 'addMenu', '新增', '', '2', '', '0', '1', '1', '', 'POST', 'manage.menu.store', '1630393533', '1630393533');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10005', '10003', 'updateMenu', '更新', '', '2', '', '0', '1', '1', '', 'PUT', 'manage.menu.update', '1630393533', '1630393533');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10006', '10003', 'viewMenu', '查询', '', '2', '', '0', '1', '1', '', 'GET', 'manage.menu.index', '1630393533', '1631240047');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10007', '10003', 'deleteMenu', '删除', '', '2', '', '0', '1', '1', '', 'DELETE', 'manage.menu.destroy', '1630393533', '1630393533');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10008', '10003', 'sysMenuPower', '权限标识', '', '2', '', '0', '1', '1', '', '', 'manage.menu.power', '1631181677', '1631181677');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10009', '10003', 'menuInfo', '详情', '', '2', '', '0', '0', '0', '', '', 'manage.menu.read', '1631238171', '1631517251');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10010', '10002', 'roleManage', '角色列表', '/sys/role', '1', 'icon-common', '0', '1', '1', 'views/system/role/index.vue', '', '', '1631238171', '1631238171');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10011', '10010', 'roleList', '列表', '', '2', '', '0', '1', '1', '', '', 'manage.role.index', '1631517900', '1631517900');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10012', '10010', 'addRole', '新增', '', '2', '', '0', '1', '1', '', '', 'manage.role.store', '1631517952', '1631517952');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10013', '10010', 'updateRole', '更新', '', '2', '', '0', '1', '1', '', '', 'manage.role.update', '1631518020', '1631518020');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10014', '10010', 'deleteRole', '删除', '', '2', '', '0', '1', '1', '', '', 'manage.role.destroy', '1631518049', '1631518049');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10015', '10010', 'roleInfo', '详情', '', '2', '', '0', '1', '1', '', '', 'manage.role.read', '1631518081', '1631518081');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10016', '10002', 'Dept', '部门列表', '/sys/dept', '1', 'icon-dept', '0', '1', '1', 'views/system/dept/index.vue', '', '', '1631520065', '1631522214');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10017', '10016', 'deptList', '列表', '', '2', '', '0', '1', '1', '', '', 'manage.dept.index', '1631517900', '1631517900');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10018', '10016', 'addDept', '新增', '', '2', '', '0', '1', '1', '', '', 'manage.dept.store', '1631517952', '1631517952');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10019', '10016', 'updateDept', '更新', '', '2', '', '0', '1', '1', '', '', 'manage.dept.update', '1631518020', '1631518020');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10020', '10016', 'deleteDept', '删除', '', '2', '', '0', '1', '1', '', '', 'manage.dept.destroy', '1631518049', '1631518049');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10021', '10016', 'deptInfo', '详情', '', '2', '', '0', '1', '1', '', '', 'manage.dept.read', '1631518081', '1631518081');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10022', '10002', 'User', '用户列表', '/sys/user', '1', 'icon-user', '0', '1', '1', 'views/system/user/index.vue', '', '', '1631608271', '1631608271');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10023', '10022', 'userList', '列表', '', '2', '', '0', '1', '1', '', '', 'manage.account.index', '1631517900', '1631517900');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10024', '10022', 'addUser', '新增', '', '2', '', '0', '1', '1', '', '', 'manage.account.store', '1631517952', '1631517952');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10025', '10022', 'updateUser', '更新', '', '2', '', '0', '1', '1', '', '', 'manage.account.update', '1631518020', '1631518020');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10026', '10022', 'deleteUser', '删除', '', '2', '', '0', '1', '1', '', '', 'manage.account.destroy', '1631518049', '1631518049');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10027', '10022', 'userInfo', '详情', '', '2', '', '0', '1', '1', '', '', 'manage.account.read', '1631518081', '1631518081');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10028', '10010', 'roleAllList', '全部角色', '', '2', '', '0', '1', '1', '', '', 'manage.role.list', '1631674800', '1631674800');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10029', '10001', 'monitoringManage', '监控管理', '', '0', 'icon-rank', '0', '1', '1', '', '', '', '1631785357', '1631785357');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10030', '10029', 'LogList', '请求日志', '/sys/log', '1', 'icon-log', '0', '1', '1', 'views/system/log/index.vue', '', '', '1631867319', '1631867319');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10031', '10030', 'ManageLogList', '查询', '', '2', '', '0', '1', '1', '', '', 'manage.log.index', '1631868008', '1631868008');");
        DB::insert("INSERT INTO `sys_manage_menu` (`menu_id`, `parent_id`, `menu_name`, `menu_title`, `menu_router`, `menu_type`, `menu_icon`, `menu_order`, `keep_alive`, `is_show`, `menu_component`, `api_method`, `api_path`, `created_at`, `updated_at`) VALUES ('10032', '10030', 'delManageLog', '删除', '', '2', '', '0', '1', '1', '', '', 'manage.log.destroy', '1631868040', '1631868040');");

    }
}
