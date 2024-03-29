<?php

declare(strict_types=1);

namespace hcf\provider;

use hcf\Loader;
use mysqli;

class MySQLProvider {

    /** @var Loader */
    private $core;

    /** @var mysqli */
    private $mysqli;

    public function __construct(Loader $core) {
        $this->core = $core;
        $data = $core->getConfig()->get("mysql");
        $this->mysqli = new mysqli((string)$data["host"], (string)$data["username"], (string)$data["password"], (string)$data["schema"], (int)$data["port"]);
        $this->init();
    }

    public function init(): void {
        // UUID is better for getting data because usernames can change but UUIDs never change
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS players(uuid VARCHAR(36) PRIMARY KEY, username VARCHAR(20), faction VARCHAR(16) DEFAULT NULL, factionRole TINYINT DEFAULT NULL, balance INT DEFAULT 0, lives INT DEFAULT 0, reclaim TINYINT DEFAULT 0, rankId TINYINT DEFAULT 0)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS deathban(uuid VARCHAR(36) PRIMARY KEY, username VARCHAR(20), time INT DEFAULT 0)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS factions(name VARCHAR(30) NOT NULL, x SMALLINT DEFAULT NULL, y SMALLINT DEFAULT NULL, z SMALLINT DEFAULT NULL, minX SMALLINT DEFAULT NULL, minZ SMALLINT DEFAULT NULL, maxX SMALLINT DEFAULT NULL, maxZ SMALLINT DEFAULT NULL, level VARCHAR(30) DEFAULT NULL, members VARCHAR(200) NOT NULL, allies VARCHAR(200) DEFAULT NULL, balance BIGINT DEFAULT 0 NOT NULL, dtr DOUBLE DEFAULT 1 NOT NULL);");
    }

    /**
     * @return mysqli
     */
    public function getDatabase(): mysqli {
        return $this->mysqli;
    }
}