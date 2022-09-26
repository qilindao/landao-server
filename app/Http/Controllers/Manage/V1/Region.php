<?php


namespace App\Http\Controllers\Manage\V1;


use App\Http\Controllers\Controller;
use App\Services\Repositories\System\RegionRepo;

class Region extends Controller
{

    public function buildLocal(RegionRepo $regionRepo){
        $regionRepo->buildLocal();
    }

}
