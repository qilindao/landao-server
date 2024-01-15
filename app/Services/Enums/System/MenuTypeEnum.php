<?php


namespace App\Services\Enums\System;


use JoyceZ\LaravelLib\Enum\BaseEnum;

class MenuTypeEnum extends BaseEnum
{

    const MENU_TYPE_CATALOG = 0;
    const MENU_TYPE_MENU = 1;
    const MENU_TYPE_BUTTON = 2;


    public static function getMap(): array
    {
        return [
            self::MENU_TYPE_CATALOG => '目录',
            self::MENU_TYPE_MENU => '菜单',
            self::MENU_TYPE_BUTTON => '权限'
        ];
    }

}
