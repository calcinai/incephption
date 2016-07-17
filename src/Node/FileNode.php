<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;
use Calcinai\Incephption\Node\Traits\DocTrait;

/**
 * There's no collision with reserved names but named for consistency.
 *
 * Class FileNode
 * @package Calcinai\Incephption\Component
 */
class FileNode extends AbstractNode {

    use DocTrait;

    private $classes = [];
    private $uses = [];
    private $namespace;

    /**
     * @param ClassNode $class
     * @return $this
     */
    public function addClass(ClassNode $class){
        $this->classes[] = $class;
        return $this;
    }

    public function addUse($use) {
        $this->uses[] = $use;
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }


}