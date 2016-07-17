<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\Traits;

trait StaticTrait {

    private $is_static = false;

    public function setIsStatic($is_static) {
        $this->is_static = $is_static;

        return $this;
    }

}