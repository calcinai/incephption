<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Namespace_;
use PhpParser\Builder\Use_;

class NamespaceContext extends AbstractContext {

    /**
     * @var Namespace_
     */
    private $namespace;

    public function __construct(Namespace_ $namespace, AbstractContext $parent_context = null) {
        parent::__construct($parent_context);
        $this->namespace = $namespace;
    }

    public function handleDoc($text){
//        $this->namespace->addDoc(
//            DocCommentNode::create()->addDescriptionLine($text)
//        );

        return $this;
    }

    public function handleUse($name){

        $use = new Use_($name, \PhpParser\Node\Stmt\Use_::TYPE_NORMAL);
        $this->namespace->addStmt($use);

        return new NamespaceUseContext($this, $use);
    }

    public function handleClass($name){

        $class = new Class_($name);
        $this->namespace->addStmt($class);

        $this->collectDocs($class);
        $this->processQueuedQualifiers($class);

        return new ClassContext($this, $class);
    }

}