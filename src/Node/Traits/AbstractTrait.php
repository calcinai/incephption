<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\Traits;

trait AbstractTrait {

    private $is_abstract = false;

    public function setIsAbstract($is_abstract) {
        $this->is_abstract = $is_abstract;

        return $this;
    }

}