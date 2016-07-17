<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

use Calcinai\Incephption\Node\ClassNode\UseNode;

/**
 * Unfortunately can't just call this 'Class'
 *
 * Class ClassNode
 * @package Calcinai\Incephption\Component
 */
class ClassNode extends AbstractNode {

    private $extends;

    private $uses = [];

    public function setExtends($class_name) {
        $this->extends = $class_name;
    }

    public function addUse(UseNode $use) {
        $this->uses[] = $use;
    }
}