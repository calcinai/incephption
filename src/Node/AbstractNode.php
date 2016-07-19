<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;

use Calcinai\Incephption\Exception\InvalidQualifierException;

abstract class AbstractNode {

    const INDENT_STRING = '    ';

    const VISIBILITY_PRIVATE    = 'private';
    const VISIBILITY_PROTECTED  = 'protected';
    const VISIBILITY_PUBLIC     = 'public';

    protected $name;

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
            throw new InvalidQualifierException(sprintf('%s nodes do not use the %s.', get_class($this), (new \ReflectionClass($class))->getShortName()));
        }

        return $this;
    }

    abstract public function prettyPrint($indent_level = 0);

    public static function prettyPrintLine($line, $data = [], $indent_level = 0){
        echo str_repeat(self::INDENT_STRING, $indent_level);
        vprintf($line, $data);
        echo "\n";
    }

    /**
     * Seems ok to do this.
     *
     * @return string
     */
    public function __toString() {
        ob_start();
        $this->prettyPrint();
        return ob_get_clean();
    }

}