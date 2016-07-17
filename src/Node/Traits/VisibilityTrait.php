<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\Traits;

trait VisibilityTrait {

    private $visibility;

    public function setVisibility($visibility){
        $this->visibility = $visibility;
    }

}