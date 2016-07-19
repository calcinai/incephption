<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use PhpParser\Builder\Use_;

class NamespaceUseContext extends AbstractContext{

    private $use;

    public function __construct(AbstractContext $parent_context, Use_ $use) {
        parent::__construct($parent_context);

        $this->use = $use;
    }

    public function handleAs($alias){
        $this->use->as($alias);
        //this is a terminal handler.
        return $this->getParentContext();
    }
}