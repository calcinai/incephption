<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Name\Relative;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;

class ClassUseContext extends AbstractContext{

    private $use;

    private $pending_override;

    public function __construct(AbstractContext $parent_context, TraitUse $use) {
        parent::__construct($parent_context);

        $this->use = $use;
    }


    public function handleInsteadof($insteadof){
        //var_dump($expression);

        list($trait, $method) = explode('::', $this->pending_override, 2);

        $this->use->adaptations[] = new Precedence(new Relative($trait), $method, explode(',', $insteadof));;
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
        return $this;

        $override = UseNode\AsNode::create($this->pending_override)
            ->setAs($name);

        $this->use->addOverride($override);

        return $this;
    }

    public function handlePrivate($name = null){
        return $this;//->handleVar($name, AbstractNode::VISIBILITY_PRIVATE);
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