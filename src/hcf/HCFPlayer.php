<?php

declare(strict_types=1);

namespace hcf;

use pocketmine\Player;

class HCFPlayer extends Player {

    /** @var Loader */
    private $core;

    /** @var int */
    private $balance;

    /** @var int */
    private $lives;

    /**
     * @param Loader $core
     */
    public function load(Loader $core): void {
        $this->core = $core;
        $this->register();
        if($this->checkDeathBan() === true) {
            return;
        }
        $stmt = $this->core->getProvider()->getDatabase()->prepare("SELECT balance, lives FROM players WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $stmt->bind_result($balance, $lives);
        $stmt->fetch();
        $stmt->close();
        $this->balance = $balance;
        $this->lives = $lives;
    }

    public function register(): void {
        $tables = [
            "players",
            "deathban"
        ];
        $uuid = $this->getRawUniqueId();
        $username = $this->getName();
        foreach($tables as $table) {
            $stmt = $this->core->getProvider()->getDatabase()->prepare("SELECT username FROM $table WHERE uuid = ?");
            $stmt->bind_param("s", $uuid);
            $stmt->execute();
            $stmt->bind_result($result);
            $stmt->fetch();
            $stmt->close();
            if($result === null) {
                $stmt = $this->core->getProvider()->getDatabase()->prepare("INSERT INTO $table(uuid, username) VALUES(?, ?)");
                $stmt->bind_param("ss", $uuid, $username);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    public function checkDeathBan(): bool {
        // TODO: Get death ban time... Check if available for deathban
        // After ranks are made
        // Return true if player is death banned, else return false
    }

    /**
     * @return int
     */
    public function getBalance(): int {
        return $this->balance;
    }

    /**
     * @param int $amount
     */
    public function addToBalance(int $amount): void {
        $this->balance += $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET balance = balance + ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param int $amount
     */
    public function subtractFromBalance(int $amount): void {
        $this->balance -= $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET balance = balance - ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param int $amount
     */
    public function setBalance(int $amount): void {
        $this->balance = $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET balance = ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @return int
     */
    public function getLives(): int {
        return $this->lives;
    }

    /**
     * @param int $amount
     */
    public function addLives(int $amount): void {
        $this->lives += $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET lives = lives + ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param int $amount
     */
    public function takeLives(int $amount): void {
        $this->lives -= $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET lives = lives - ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * @param int $amount
     */
    public function setLives(int $amount): void {
        $this->lives = $amount;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET lives = ? WHERE uuid = ?");
        $stmt->bind_param("is", $amount, $uuid);
        $stmt->execute();
        $stmt->close();
    }
}