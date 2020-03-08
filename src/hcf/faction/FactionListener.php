<?php

namespace hcf\faction;

use hcf\Loader;
use pocketmine\event\Listener;

class FactionListener implements Listener {

    /** @var Loader */
    private $core;

    /**
     * FactionListener constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
    }
}