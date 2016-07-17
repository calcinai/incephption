<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\ClassNode\UseNode\InsteadOfNode;
use Calcinai\Incephption\Node\ClassNode\UseNode\AsNode;

/**
 * Class UseNode
 * @package Calcinai\Incephption\Component
 */
class UseNode extends AbstractNode {

    private $insteadofs = [];
    private $overrides = [];

    public function addInsteadOf(InsteadOfNode $insteadof) {
        $this->insteadofs[] = $insteadof;
    }

    public function addOverride(AsNode $override) {
        $this->overrides[] = $override;
    }

}