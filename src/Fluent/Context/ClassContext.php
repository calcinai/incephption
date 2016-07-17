<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;


use Calcinai\Incephption\Exception\InvalidQualifierException;
use Calcinai\Incephption\Node\AbstractNode;
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
        $use = ClassNode\UseNode::create($class_name);
        $this->class->addUse($use);

        $this->collectDocs($use);

        return new ClassUseContext($this, $use);
    }

    public function handleConst($name, $value){
        $const = ClassNode\ConstNode::create($name)
            ->setValue($value);

        $this->class->addConst($const);

        $this->collectDocs($const);

        return $this;
    }

    public function handlePrivate($name, $default = null){
        return $this->handleVar($name, $default, AbstractNode::VISIBILITY_PRIVATE);
    }

    public function handleProtected($name, $default = null){
        return $this->handleVar($name, $default, AbstractNode::VISIBILITY_PROTECTED);
    }

    public function handlePublic($name, $default = null){
        return $this->handleVar($name, $default, AbstractNode::VISIBILITY_PUBLIC);
    }

    public function handleVar($name, $default, $visibility = null){
        $property = ClassNode\PropertyNode::create($name)
            ->setVisibility($visibility)
            ->setDefaultValue($default);

        $this->collectDocs($property);

        $this->class->addProperty($property);
        return $this;
    }

    public function handleFunction($name, $function){

        $method = ClassNode\MethodNode::create($name);
        $this->class->addMethod($method);

        $this->collectDocs($method);

        while($qualifier = array_pop($this->pending_qualifiers)){
            switch($qualifier){
                case 'abstract':
                    $method->setIsAbstract(true);
                    break;

                case 'static':
                    $method->setIsStatic(true);
                    break;

                case 'final':
                    $method->setIsFinal(true);
                    break;

                case AbstractNode::VISIBILITY_PRIVATE:
                case AbstractNode::VISIBILITY_PROTECTED:
                case AbstractNode::VISIBILITY_PUBLIC:
                    $method->setVisibility($qualifier);
                    break;

                default:
                    throw new InvalidQualifierException(sprintf('Class nodes do not accept the %s qualifier.', $qualifier));
            }
        }

        //Terminal
        return $this;
    }


}

