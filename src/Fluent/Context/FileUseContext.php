<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\FileNode\UseNode;

class FileUseContext extends AbstractContext{

    public function __construct(UseNode $use, AbstractContext $parent_context = null) {
        parent::__construct($parent_context);

        $this->node = $use;
    }

    public function handleAs($alias){
        $this->node->setAs($alias);
        //this is a terminal handler.
        return $this->getParentContext();
    }
}