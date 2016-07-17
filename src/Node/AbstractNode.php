<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

class AbstractNode {

    const VISIBILITY_PRIVATE    = 'private';
    const VISIBILITY_PROTECTED  = 'protected';
    const VISIBILITY_PUBLIC     = 'private';

    private $name;

    public function __construct($name = null) {
        $this->name = $name;
    }
}