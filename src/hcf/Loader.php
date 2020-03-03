<?php

declare(strict_types=1);

namespace hcf;

use hcf\managers\EntityManager;
use hcf\provider\MySQLProvider;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase {

    /** @var Loader */
    private static $instance;

    /** @var MySQLProvider */
    private $provider;

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
    }
    
    public function getManagers(){
        new EntityManager();
        new CommandManager();
    }

    /**
     * @return MySQLProvider
     */
    public function getProvider(): MySQLProvider {
        return $this->provider;
    }
}
