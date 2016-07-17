<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\FileNode;

use Calcinai\Incephption\Node\AbstractNode;

/**
 * Class UseNode
 * @package Calcinai\Incephption\Component
 */
class UseNode extends AbstractNode {

    private $as;

    public function setAs($alias) {
        $this->as = $alias;
    }
}