<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode\UseNode;

use Calcinai\Incephption\Node\AbstractNode;

class InsteadOfNode extends AbstractNode {

    /**
     * @var string
     */
    private $insteadof;

    public function __construct($name, $insteadof) {
        parent::__construct($name);
        $this->insteadof = $insteadof;
    }
}