<?php

namespace ByNamles\Rank\managers;

use ByNamles\Rank\Rank;
use pocketmine\permission\Permission;

class PermissionManager{

    public static function init() : void{
        self::loadPermissions();
    }

    public static function loadPermissions() : void{
        array_map(
            function(string $mine) : void{
                \pocketmine\permission\PermissionManager::getInstance()->addPermission(new Permission(Rank::getInstance()->mine->get($mine)["Perm"]));
            },
            Rank::getInstance()->mine->getAll(true) ?? []
        );
    }
}