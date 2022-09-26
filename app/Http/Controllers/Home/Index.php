<?php


namespace App\Http\Controllers\Home;


use App\Http\Controllers\Controller;
use App\Services\Repositories\Manage\LogRepo;
use App\Services\Repositories\Manage\ManageRepo;
use App\Services\Repositories\Manage\MenuRepo;

class Index extends Controller
{
    public function index(MenuRepo $menuRepo, LogRepo $logRepo, ManageRepo $manageRepo)
    {

//        dd($menuRepo->count([['menu_id', 'In', [10002, 10016]]]));
        dd($logRepo->column('log_id,manage_id,log_action',[['log_id', 'IN', [10008,10011]]],'log_id'));
//        dd($manageRepo->with(['roles']));
//        dd($manageRepo->where(function ($query) {
//            $query->whereIn('manage_id', [10001]);
//        })->paginate()->toArray());
//        $menuRepo->getQuerySql(function () use ($menuRepo) {
//            $menuRepo->where(['menu_type' => 2])->limit(5)->orderBy('menu_id', 'desc')->orderBy('parent_id', 'asc')->all()->toArray();
//        });
        dd($menuRepo->where(['menu_type'=>2])->limit(5)->orderBy('menu_id','desc')->orderBy('parent_id','asc')->all()->toArray());
    }
}
