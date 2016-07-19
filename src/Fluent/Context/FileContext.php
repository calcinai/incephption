<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\ClassNode;
use Calcinai\Incephption\Node\DocCommentNode;
use Calcinai\Incephption\Node\FileNode\UseNode;
use Calcinai\Incephption\Node\FileNode;

class FileContext extends AbstractContext {

    public function __construct(FileNode $node, AbstractContext $parent_context = null) {
        parent::__construct($parent_context);
        $this->node = $node;
    }


    public function handleDoc($text){
        $this->node->addDoc(
            DocCommentNode::create()->addDescriptionLine($text)
        );

        return $this;
    }

    public function handleNamespace($text){
        $this->node->setNamespace($text);

        return $this;
    }

    public function handleUse($name){

        $use = UseNode::create($name);
        $this->node->addUse($use);

        $this->collectDocs($use);

        return new FileUseContext($use, $this);
    }

    public function handleClass($name){

        $class = ClassNode::create($name);
        $this->node->addClass($class);

        $this->collectDocs($class);
        $this->processQueuedQualifiers($class);

        return new ClassContext($class, $this);
    }

}