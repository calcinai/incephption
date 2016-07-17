<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\DocNode;
use Calcinai\Incephption\Node\FileNode;
use Calcinai\Incephption\Node\FileNode\UseNode;
use Calcinai\Incephption\Node\ClassNode;
use Calcinai\Incephption\Exception\InvalidQualifierException;

class FileContext extends AbstractContext {

    /**
     * @var FileNode
     */
    private $file;

    public function __construct(AbstractContext $parent_context = null, FileNode $file) {
        parent::__construct($parent_context);

        $this->file = $file;
    }

    public function handleDoc($text){
        $this->file->addDoc(
            DocNode::create()->addLine($text)
        );

        return $this;
    }

    public function handleNamespace($namespace){
        $this->file->setNamespace($namespace);
        return $this;
    }

    public function handleUse($name){

        $use = UseNode::create($name);
        $this->file->addUse($use);

        $this->collectDocs($use);

        return new FileUseContext($this, $use);
    }

    public function handleClass($name){

        $class = ClassNode::create($name);
        $this->file->addClass($class);

        $this->collectDocs($class);

        while($qualifier = array_pop($this->pending_qualifiers)){
            switch($qualifier){
                case 'abstract':
                    $class->setIsAbstract(true);
                    break;

                default:
                    throw new InvalidQualifierException(sprintf('Class nodes do not accept the %s qualifier.', $qualifier));
            }
        }

        return new ClassContext($this, $class);
    }

}