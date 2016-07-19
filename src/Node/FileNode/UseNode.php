<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\FileNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\Traits\DocTrait;

/**
 * Class UseNode
 * @package Calcinai\Incephption\Component
 */
class UseNode extends AbstractNode {

    use DocTrait;

    private $as;

    public function setAs($alias) {
        $this->as = $alias;
        return $this;
    }
}