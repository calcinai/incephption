<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\ClassNode\UseNode;
use Calcinai\Incephption\Node\ClassNode\UseNode\InsteadOfNode;

class ClassUseContext extends AbstractContext {

    private $pending_override;

    public function __construct(UseNode $use, AbstractContext $parent_context = null) {
        parent::__construct($parent_context);

        $this->node = $use;
    }


    public function handleInsteadof($insteadof){

        $insteadof = new InsteadOfNode($this->pending_override, $insteadof);
        $this->node->addInsteadOf($insteadof);

        $this->pending_override = null;

        return $this;
    }

    public function handleMethod($name){
        $this->pending_override = $name;
        return $this;
    }

    public function handleAs($name){
        return $this->handleVar($name);
    }

    public function handlePrivate($name = null){
        return $this->handleVar($name, AbstractNode::VISIBILITY_PRIVATE);
    }

    public function handleProtected($name = null){
        return $this->handleVar($name, AbstractNode::VISIBILITY_PROTECTED);
    }

    public function handlePublic($name = null){
        return $this->handleVar($name, AbstractNode::VISIBILITY_PUBLIC);
    }

    public function handleVar($name = null, $visibility = null){

        $override = UseNode\AsNode::create($this->pending_override)
            ->setVisibility($visibility)
            ->setAs($name);

        $this->node->addOverride($override);

        return $this;
    }
}