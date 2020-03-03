<?php

namespace hcf\entity\projectile;

use pocketmine\block\Block;
use pocketmine\block\FenceGate;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;

class EnderPearl extends \pocketmine\item\EnderPearl {

    public $width = 0.01;
    public $height = 0.01;

    protected function calculateInterceptWithBlock(Block $block, Vector3 $start, Vector3 $end): ?RayTraceResult {
        if(
            $block instanceof FenceGate &&
            ($block->getDamage() & 0x04) > 0 &&
            in_array(($block->getDamage() & 0x03), [0, 2])
        ) {
            return null;
        } else {
            return $block->calculateIntercept($start, $end);
        }
    }
}
