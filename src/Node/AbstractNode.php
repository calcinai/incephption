<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

use Calcinai\Incephption\Exception\InvalidQualifierException;

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

    public function assertTrait($class) {
        if(!in_array($class, class_uses($this))){
            throw new InvalidQualifierException(sprintf('Class nodes do not use the %s.', (new \ReflectionClass($class))->getShortName()));
        }

        return $this;
    }

}