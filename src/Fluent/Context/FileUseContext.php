<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;


use Calcinai\Incephption\Node\FileNode\UseNode;

class FileUseContext extends AbstractContext{

    private $use;

    public function __construct(AbstractContext $parent_context, UseNode $use) {
        parent::__construct($parent_context);

        $this->use = $use;
    }

    public function handleAs($alias){
        $this->use->setAs($alias);
        //this is a terminal handler.
        return $this->getParentContext();
    }
}