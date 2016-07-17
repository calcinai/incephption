<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\FileNode;
use Calcinai\Incephption\Node\FileNode\UseNode;
use Calcinai\Incephption\Node\ClassNode;

class FileContext extends AbstractContext {

    /**
     * @var FileNode
     */
    private $file;

    public function __construct(AbstractContext $parent_context = null, FileNode $file) {
        parent::__construct($parent_context);

        $this->file = $file;
    }

    public function handleDoc($doc){
        return $this;
    }

    public function handleNamespace($namespace){
        return $this;
    }

    public function handleUse($use){
        $use = new UseNode($use);
        $this->file->addUse($use);

        return new FileUseContext($this, $use);
    }

    public function handleClass($name){
        $class = new ClassNode($name);
        $this->file->addClass($class);

        return new ClassContext($this, $class);
    }

}