<?php

declare(strict_types=1);

namespace hcf;

use hcf\rank\Rank;
use pocketmine\Player;

class HCFPlayer extends Player {

    /** @var Loader */
    private $core;
//
//    private $faction;
//
//    /** @var int */
//    private $factionRole;

    /** @var int */
    private $balance;

    /** @var int */
    private $lives;

    /** @var bool */
    private $reclaim;

    /** @var Rank */
    private $rank;

    /**
     * @param Loader $core
     */
    public function load(Loader $core): void {
        $this->core = $core;
        $this->register();
        if($this->checkDeathBan() === true) {
            return;
        }
        $stmt = $this->core->getProvider()->getDatabase()->prepare("SELECT faction, factionRole, balance, lives, reclaim FROM players WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $stmt->bind_result($faction, $factioNRole, $balance, $lives, $reclaim);
        $stmt->fetch();
        $stmt->close();
//        $this->faction =; // TODO
//        $this->factionRole =; // TODO
        $this->balance = $balance;
        $this->lives = $lives;
        $this->reclaim = (bool)$reclaim;

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
        $stmt = $this->core->getProvider()->getDatabase()->prepare("SELECT rankId FROM players WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $stmt->bind_result($rankId);
        $stmt->fetch();
        $stmt->close();
        $this->rank = $this->core->getRankManager()->getRank($rankId);
        $stmt = $this->core->getProvider()->getDatabase()->prepare("SELECT time FROM deathban WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $stmt->bind_result($time);
        $stmt->fetch();
        $stmt->close();
        if((time() - $time) < $this->rank->getDeathBanTime()) {
            $this->close("TODO: Death ban message");
            return true;
        }
        return false;
    }

//    /**
//     * @return mixed|null
//     */
//    public function getFaction() { // TODO
//        return $this->faction;
//    }
//
//    /**
//     * @param mixed|null $faction
//     */
//    public function setFaction(?$faction): void { // TODO: Type hint $faction
//        $this->faction = $faction;
//        $faction = $faction instanceof  ? $faction->getName() : null;
//        $uuid = $this->getRawUniqueId();
//        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET faction = ? WHERE uuid = ?");
//        $stmt->bind_param("ss", $faction, $uuid);
//        $stmt->execute();
//        $stmt->close();
//    }
//
//    /**
//     * @return int|null
//     */
//    public function getFactionRole(): ?int {
//        return $this->factionRole;
//    }
//
//    /**
//     * @param int|null $role
//     */
//    public function setFactionRole(?int $role): void {
//        $this->factionRole = $role;
//        $uuid = $this->getRawUniqueId();
//        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET factionRole = ? WHERE uuid = ?");
//        $stmt->bind_param("is", $role, $uuid);
//        $stmt->execute();
//        $stmt->close();
//    }

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

    /**
     * @return bool
     */
    public function hasReclaimed(): bool {
        return $this->reclaim;
    }

    /**
     * @param bool $value
     */
    public function setReclaimed(bool $value = true): void {
        $this->reclaim = $value;
        $value = (int)$value;
        $uuid = $this->getRawUniqueId();
        $stmt = $this->core->getProvider()->getDatabase()->prepare("UPDATE players SET reclaim = ? WHERE uuid = ?");
        $stmt->bind_param("is", $value, $uuid);
        $stmt->execute();
        $stmt->close();
    }
}