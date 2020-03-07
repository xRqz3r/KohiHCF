<?php

namespace hcf\managers;

use hcf\entity\projectile\EnderPearl;
use hcf\Loader;
use pocketmine\entity\Entity;

class EntityManager {

    /** @var Loader */
    private $core;

    /**
     * EntityManager constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
        $this->init();
    }

    private function init(): void{
        Entity::registerEntity(EnderPearl::class, false, ['ThrownEnderpearl', 'minecraft:ender_pearl']);
    }
}
