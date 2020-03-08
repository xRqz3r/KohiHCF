<?php

declare(strict_types=1);

namespace hcf;

use hcf\entity\EntityManager;
use hcf\faction\FactionManager;
use hcf\level\LevelManager;
use hcf\provider\MySQLProvider;
use hcf\rank\RankManager;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

    /** @var Loader */
    private static $instance;

    /** @var MySQLProvider */
    private $provider;

    /** @var EntityManager */
    private $entityManager;

    /** @var LevelManager */
    private $levelManager;

    /** @var FactionManager */
    private $factionManager;

    /** @var RankManager */
    private $rankManager;

    /**
     * @return Loader
     */
    public static function getInstance(): self {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
        if(!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveConfig();
    }

    public function onEnable(): void {
        $this->provider = new MySQLProvider($this);
        $this->entityManager = new EntityManager($this);
        $this->levelManager = new LevelManager($this);
        $this->factionManager = new FactionManager($this);
        $this->rankManager = new RankManager($this);
    }

    /**
     * @return MySQLProvider
     */
    public function getProvider(): MySQLProvider {
        return $this->provider;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager {
        return $this->entityManager;
    }

    /**
     * @return LevelManager
     */
    public function getLevelManager(): LevelManager {
        return $this->levelManager;
    }

    /**
     * @return FactionManager
     */
    public function getFactionManager(): FactionManager {
        return $this->factionManager;
    }

    /**
     * @return RankManager
     */
    public function getRankManager(): RankManager {
        return $this->rankManager;
    }
}
