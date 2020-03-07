<?php

namespace hcf\level\generator;

use pocketmine\block\Block;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\InvalidGeneratorOptionsException;
use pocketmine\math\Vector3;

class NetherGenerator extends Generator {

    /**
     * @param array $options
     *
     * @throws InvalidGeneratorOptionsException
     */
    public function __construct(array $options = []) {
    }

    /**
     * @return string
     */
    public function getName(): string {
        return "nether";
    }

    /**
     * @return array
     */
    public function getSettings(): array {
        return [];
    }

    /**
     * @param int $chunkX
     * @param int $chunkZ
     */
    public function generateChunk(int $chunkX, int $chunkZ): void {
        $chunk = $this->level->getChunk($chunkX, $chunkX);
        for($x = 0; $x < 16; ++$x) {
            for($z = 0; $z < 16; ++$z) {
                for($y = 0; $y <= 60; ++$y) {
                    if($y === 0) {
                        $chunk->setBlockId($x, $y, $z, Block::BEDROCK);
                        continue;
                    }
                    $chunk->setBlockId($x, $y, $z, Block::NETHERRACK);
                }
            }
        }
        $this->level->setChunk($chunkX, $chunkZ, $chunk);
    }

    /**
     * @param int $chunkX
     * @param int $chunkZ
     */
    public function populateChunk(int $chunkX, int $chunkZ): void {
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
    }

    /**
     * @return Vector3
     */
    public function getSpawn(): Vector3 {
        return new Vector3(0, 60, 0);
    }
}