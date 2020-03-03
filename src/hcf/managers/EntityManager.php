<?php

namespace hcf\managers;

use hcf\entity\projectile\EnderPearl;
use pocketmine\entity\Entity;

class EntityManager {

    public function __construct(){
        $this->init();
    }

    private function init(): void{
        Entity::registerEntity(EnderPearl::class, false, ['ThrownEnderpearl', 'minecraft:ender_pearl']);
    }
}
