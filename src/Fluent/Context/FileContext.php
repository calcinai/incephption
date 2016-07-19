<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\DocCommentNode;
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
            DocCommentNode::create()->addDescriptionLine($text)
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
        $this->processQueuedQualifiers($class);

        return new ClassContext($this, $class);
    }

}