<?php

namespace hcf\rank;

use hcf\HCFPlayer;
use hcf\Loader;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class RankListener implements Listener {

    /** @var Loader */
    private $core;

    /**
     * RankListener constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
    }

    /**
     * @priority HIGHEST
     * @param PlayerChatEvent $event
     */
    public function onPlayerChat(PlayerChatEvent $event): void {
        if($event->isCancelled()) {
            return;
        }
        $player = $event->getPlayer();
        if(!$player instanceof HCFPlayer) {
            return;
        }
        $event->setFormat($player->getRank()->getChatFormatFor($player, $event->getMessage()));
        return;
    }

    /**
     * @priority NORMAL
     * @param EntityRegainHealthEvent $event
     */
    public function onEntityRegainHealth(EntityRegainHealthEvent $event): void {
        if($event->isCancelled()) {
            return;
        }
        $player = $event->getEntity();
        if(!$player instanceof HCFPlayer) {
            return;
        }
        // $player->setScoreTag(HP);
    }

    /**
     * @priority NORMAL
     * @param EntityDamageEvent $event
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        if($event->isCancelled()) {
            return;
        }
        $player = $event->getEntity();
        if(!$player instanceof HCFPlayer) {
            return;
        }
        // $player->setScoreTag(HP);
    }
}