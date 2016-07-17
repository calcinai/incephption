<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;


use Calcinai\Incephption\Node\ClassNode;

class ClassContext extends AbstractContext {

    /**
     * @var ClassNode
     */
    private $class;

    public function __construct(AbstractContext $parent_context, ClassNode $class) {
        parent::__construct($parent_context);

        $this->class = $class;
    }

    public function handleExtends($class_name){
        $this->class->setExtends($class_name);
        return $this;
    }

    public function handleUse($class_name){
        $use = new ClassNode\UseNode($class_name);
        $this->class->addUse($use);

        return new ClassUseContext($this, $use);
    }
}