<?php

declare(strict_types=1);

namespace hcf\level;

use hcf\level\generator\EndGenerator;
use hcf\level\generator\NetherGenerator;
use hcf\Loader;
use pocketmine\level\generator\GeneratorManager;

class LevelManager {

    /** @var Loader */
    private $core;

    /**
     * LevelManager constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
        $this->init();
    }

    public function init(): void {
        $server = $this->core->getServer();
        GeneratorManager::addGenerator(NetherGenerator::class, "nether", true);
        GeneratorManager::addGenerator(EndGenerator::class, "ender");
        if(!$server->loadLevel("nether")) {
            $server->generateLevel("nether", time(), GeneratorManager::getGenerator("nether"));
        }
        if(!$server->loadLevel("ender")) {
            $server->generateLevel("ender", time(), GeneratorManager::getGenerator("ender"));
        }
    }
}