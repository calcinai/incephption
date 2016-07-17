<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\ClassNode;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\Traits\AbstractTrait;
use Calcinai\Incephption\Node\Traits\DocTrait;
use Calcinai\Incephption\Node\Traits\FinalTrait;
use Calcinai\Incephption\Node\Traits\StaticTrait;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;

/**
 * Class MethodNode
 * @package Calcinai\Incephption\Component
 */
class MethodNode extends AbstractNode {

    use DocTrait;
    use VisibilityTrait;
    use AbstractTrait;
    use FinalTrait;
    use StaticTrait;

}