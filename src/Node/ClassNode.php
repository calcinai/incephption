<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

use Calcinai\Incephption\Node\ClassNode\ConstNode;
use Calcinai\Incephption\Node\ClassNode\MethodNode;
use Calcinai\Incephption\Node\ClassNode\PropertyNode;
use Calcinai\Incephption\Node\ClassNode\UseNode;
use Calcinai\Incephption\Node\Traits\AbstractTrait;
use Calcinai\Incephption\Node\Traits\DocTrait;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;

/**
 * Unfortunately can't just call this 'Class'
 *
 * Class ClassNode
 * @package Calcinai\Incephption\Component
 */
class ClassNode extends AbstractNode {

    use AbstractTrait;
    use VisibilityTrait;
    use DocTrait;

    private $extends;

    private $uses = [];
    private $consts = [];
    private $properties = [];
    private $methods = [];


    public function setExtends($class_name) {
        $this->extends = $class_name;
        return $this;
    }

    public function addUse(UseNode $use) {
        $this->uses[] = $use;
        return $this;
    }

    public function addConst(ConstNode $const) {
        $this->consts[] = $const;
        return $this;
    }

    public function addProperty(PropertyNode $property) {
        $this->properties[] = $property;
        return $this;
    }

    public function addMethod(MethodNode $method) {
        $this->methods[] = $method;
        return $this;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}