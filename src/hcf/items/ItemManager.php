<?php

declare(strict_types=1);

namespace hcf\items;

use hcf\Loader;

class ItemManager {

    /** @var Loader */
    private $core;

    /**
     * ItemManager constructor.
     *
     * @param Loader $core
     */
    public function __construct(Loader $core) {
        $this->core = $core;
    }
}