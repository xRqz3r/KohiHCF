<?php

declare(strict_types=1);

namespace hcf;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class EventListener implements Listener {

    /** @var Loader */
    private $core;

    /**
     * EventListener constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     *
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $player->load($this->core);
        $event->setJoinMessage(null);
    }

    /**
     * @priority NORMAL
     *
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $event->setQuitMessage(null);
    }

    /**
     * @priority NORMAL
     *
     * @param PlayerCreationEvent $event
     */
    public function onPlayerCreation(PlayerCreationEvent $event): void {
        $event->setPlayerClass(HCFPlayer::class);
    }
}