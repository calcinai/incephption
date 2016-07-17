<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

class AbstractNode {

    const VISIBILITY_PRIVATE    = 'private';
    const VISIBILITY_PROTECTED  = 'protected';
    const VISIBILITY_PUBLIC     = 'public';

    private $name;

    public function __construct($name = null) {
        $this->name = $name;
    }

    /**
     * @param null $name
     * @return static
     */
    public static function create($name = null){
        return new static($name);
    }

}