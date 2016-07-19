<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode\UseNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;

class AsNode extends AbstractNode {

    use VisibilityTrait;

    private $as;

    public function setAs($name) {
        $this->as = $name;

        return $this;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}