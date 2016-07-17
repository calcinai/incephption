<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\ClassNode\UseNode;

class ClassUseContext extends AbstractContext{

    private $use;

    private $pending_override;

    public function __construct(AbstractContext $parent_context, UseNode $use) {
        parent::__construct($parent_context);

        $this->use = $use;
    }


    public function handleInsteadof($insteadof){
        //var_dump($expression);
        $insteadof = new UseNode\InsteadOfNode($this->pending_override, $insteadof);
        $this->use->addInsteadOf($insteadof);

        $this->pending_override = null;

        return $this;
    }


    /**
     * Hijack a subsequent use in case it's renaming a function or resolving conflicts.
     *
     * @param $name
     * @return $this
     */
    public function handleUse($name){
        if(false === strpos($name, '::')){
            /** @var ClassContext $parent_context */
            $parent_context = $this->getParentContext();
            $parent_context->handleUse($name);
        }

        $this->pending_override = $name;

        return $this;
    }

    public function handleAs($name){
        $override = UseNode\AsNode::create($this->pending_override)
            ->setAs($name);

        $this->use->addOverride($override);

        return $this;
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

        $this->use->addOverride($override);

        return $this;
    }



    public function applyQualifier($name){
        switch($name){
            case 'private':
                return $this->handlePrivate();
            case 'protected':
                return $this->handleProtected();
            case 'public':
                return $this->handlePublic();

            case 'as': //as really does nothing - it's just a joining word
                return $this;
            default:
                return false;
        }

    }
}