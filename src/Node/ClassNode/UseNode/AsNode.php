<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode\UseNode;

use Calcinai\Incephption\Node\AbstractNode;

class AsNode extends AbstractNode {

    private $visibility;
    private $as;

    public function setVisibility($visibility) {
        $this->visibility = $visibility;
    }

    public function setAs($name) {
        $this->as = $name;
    }
}