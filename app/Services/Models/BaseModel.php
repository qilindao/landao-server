<?php


namespace App\Services\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use JoyceZ\LaravelLib\Model\BaseModel as JBaseModel;


abstract class BaseModel extends JBaseModel
{
    //自动缓存模型及关联数据
    use Cachable;
}
