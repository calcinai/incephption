<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\Traits;

trait FinalTrait {

    private $is_final = false;

    public function setIsFinal($is_final) {
        $this->is_final = $is_final;

        return $this;
    }

}