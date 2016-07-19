<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;
use Calcinai\Incephption\Node\FileNode\UseNode;
use Calcinai\Incephption\Node\Traits\DocTrait;

/**
 * There's no collision with reserved names but named for consistency.
 *
 * Class FileNode
 * @package Calcinai\Incephption\Component
 */
class FileNode extends AbstractNode {

    use DocTrait;

    /**
     * @var ClassNode[]
     */
    private $classes = [];

    /**
     * @var UseNode[]
     */
    private $uses = [];

    /**
     * @var string
     */
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
        return $this;
    }

    public function setNamespace($namespace) {
        $this->namespace = $namespace;
        return $this;
    }



    function prettyPrint($indent_level = 0) {

        $this->prettyPrintLine('<?php', [], $indent_level);

        if($this->doc !== null){
            $this->doc->prettyPrint($indent_level);
        }

        if($this->namespace !== null){
            $this->prettyPrintLine('namespace %s;', [$this->namespace], $indent_level);
        }

        foreach($this->uses as $use){
            $use->prettyPrint($indent_level);
        }

    }
}