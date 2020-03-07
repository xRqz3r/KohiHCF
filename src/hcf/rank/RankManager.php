<?php

namespace hcf\rank;

use hcf\Loader;
use pocketmine\utils\TextFormat;

class RankManager implements RankIdentifiers {

    /** @var Loader */
    private $core;

    /** @var Rank[] */
    private $ranks = [];

    /**
     * RankManager constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new RankListener($core), $core);
        $this->init();
    }

    public function init(): void {
        $this->addRank(new Rank("Player", TextFormat::GOLD . TextFormat::BOLD . "PLAYER", self::PLAYER, 1800,
            TextFormat::GOLD . TextFormat::BOLD . "PLAYER" . TextFormat::RESET . TextFormat::WHITE . " {player}" . TextFormat::GRAY . ": {message}",
            TextFormat::GOLD . TextFormat::BOLD . "Player" . TextFormat::RESET . TextFormat::WHITE . " {player}", [
            ]));
    }

    /**
     * @return Rank[]
     */
    public function getRanks(): array {
        return $this->ranks;
    }

    /**
     * @param string|int $identifier
     *
     * @return Rank|null
     */
    public function getRank($identifier): ?Rank {
        return $this->ranks[$identifier] ??null;
    }

    /**
     * @param Rank $rank
     */
    public function addRank(Rank $rank): void {
        $this->ranks[$rank->getIdentifier()] = $rank;
        $this->ranks[$rank->getName()] = $rank;
    }
}