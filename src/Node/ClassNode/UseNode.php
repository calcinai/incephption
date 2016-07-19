<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\ClassNode\UseNode\InsteadOfNode;
use Calcinai\Incephption\Node\ClassNode\UseNode\AsNode;
use Calcinai\Incephption\Node\Traits\DocTrait;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;

/**
 * Class UseNode
 * @package Calcinai\Incephption\Component
 */
class UseNode extends AbstractNode {

    use DocTrait;

    private $insteadofs = [];
    private $overrides = [];

    public function addInsteadOf(InsteadOfNode $insteadof) {
        $this->insteadofs[] = $insteadof;
        return $this;
    }

    public function addOverride(AsNode $override) {
        $this->overrides[] = $override;
        return $this;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}