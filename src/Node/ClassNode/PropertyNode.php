<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\Traits\DocTrait;
use Calcinai\Incephption\Node\Traits\FinalTrait;
use Calcinai\Incephption\Node\Traits\StaticTrait;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;

/**
 * Class PropertyNode
 * @package Calcinai\Incephption\Component
 */
class PropertyNode extends AbstractNode {

    use DocTrait;
    use VisibilityTrait;
    use StaticTrait;
    use FinalTrait;

    private $default_value;

    public function setDefaultValue($value){
        $this->default_value = $value;

        return $this;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}