<?php

namespace hcf\items\types;

use pocketmine\item\Snowball;
use pocketmine\math\Vector3;
use pocketmine\Player;

class SwitcherBall extends Snowball {

    /**
     * SwitcherBall constructor.
     *
     * @param int $meta
     */
    public function __construct(int $meta = 0) {
        $customName =; // TODO
        $lore = []; // TODO
        $this->setCustomName($customName);
        $this->setLore($lore);
        parent::__construct($meta);
    }

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     *
     * @return bool
     */
    public function onClickAir(Player $player, Vector3 $directionVector): bool {
        return parent::onClickAir($player, $directionVector);
    }

    /**
     * @return string
     */
    public function getProjectileEntityType(): string {
        return "SwitcherBallProjectile";
    }
}