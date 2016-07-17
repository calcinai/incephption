<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\DocNode;
use Calcinai\Incephption\Node\Traits\DocTrait;

abstract class AbstractContext {

    /**
     * @var AbstractContext
     */
    private $parent_context;

    protected $pending_qualifiers;

    public function __construct(AbstractContext $parent_context = null) {
        $this->parent_context = $parent_context;
    }

    /**
     * @return AbstractContext
     */
    public function getParentContext() {
        return $this->parent_context;
    }

    /**
     * This is to allow a context to apply a qualifier. (private, static etc).
     *
     * @param $name
     * @return bool
     */
    public function applyQualifier($name){
        return false;
    }

    public function __call($name, $arguments) {

        $method_name = sprintf('handle%s', ucfirst($name));

        //If it's not at this level, see if the parent context will accept it.
        //There doesn't seem to be a clean way to go up a level in the template.
        if(method_exists($this, $method_name)){
            return call_user_func_array([$this, $method_name], $arguments);
        } elseif(method_exists($this->parent_context, $method_name)){
            return call_user_func_array([$this->parent_context, $method_name], $arguments);
        }

        //Need not be efficient, it's fatal.
        $tested_classes[] = (new \ReflectionClass($this))->getShortName();
        if($this->parent_context !== null){
            $tested_classes[] = (new \ReflectionClass($this->parent_context))->getShortName();
        }

        throw new \Exception(sprintf('%s() is not available in %s.', $name, implode(' or ', $tested_classes)));
    }

    /**
     * Store qualifiers
     *
     * @param $name
     * @return $this
     */
    public function __get($name) {

        //Will return false if it couldn't handle it, or a new context if it did.
        $context = $this->applyQualifier($name);
        if($context !== false){
            return $context;
        }

        $this->pending_qualifiers[] = $name;
        return $this;
    }


    /**
     * Use the backtrace to find doc comments.
     * Not pretty. (at all).
     *
     * @param AbstractNode $node the node to collect for.
     */
    protected function collectDocs(AbstractNode $node){

        /** @var DocTrait $node ...shouldn't have to do this. */

        //Can't really use reflection cause it's completely unstructured
        $backtrace = debug_backtrace();
        while($step = array_shift($backtrace)) {
            //traverse until we're out of this lib.
            if($step['function'] === '__call') {
                break;
            }
        }

        if(!isset($step['file'])){
            return;
        }

        $file = new \SplFileObject($step['file']);
        $line_number = $step['line']-2;

        $file->seek($line_number);
        $line = trim($file->current());

        if(strpos($line, '//') === 0){
            //Single line comment
            $doc = new DocNode();
            $doc->addLine(ltrim($line, '/'));
            $node->addDoc($doc);

            return;
        } else {
            var_dump($file->current());
            //Look for doc comments
            if(strpos($line, '*/') !== false) {
                $parsed_lines = [];
                while($line_number-- > 0){
                    $file->seek($line_number);
                    $line = $file->current();

                    if(strpos($line, '/*') !== false) {
                        //Break out at start of docblock
                        break;
                    }
                    $parsed_lines[] = trim($line, "\n\t* ");
                }
                //Got to put them in backwards
                $doc_node = new DocNode();
                foreach(array_reverse($parsed_lines) as $line){
                    $doc_node->addLine($line);
                }
                $node->addDoc($doc_node);
            }
        }
    }

}