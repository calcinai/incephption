<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\Traits\DocTrait;


/**
 * Class ConstNode
 * @package Calcinai\Incephption\Component
 */
class ConstNode extends AbstractNode {

    use DocTrait;

    private $value;

    public function setValue($value){
        $this->value = $value;

        return $this;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}