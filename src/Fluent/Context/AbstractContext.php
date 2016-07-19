<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Exception\InvalidQualifierException;
use Calcinai\Incephption\Helper\DocComment;
use PhpParser\Builder\Class_;
use PhpParser\Builder\Declaration;
use PhpParser\Builder\Property;
use PhpParser\BuilderAbstract;

abstract class AbstractContext {

    /**
     * @var AbstractContext
     */
    protected $parent_context;


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
     * Process the qualifier queue on a particular node
     *
     * @param Declaration $node
     * @throws InvalidQualifierException
     */
    protected function processQueuedQualifiers(Declaration $node){

        while($qualifier = array_pop($this->pending_qualifiers)){
            switch($qualifier){
                case 'abstract':
                    /** @var Class_ $node */
                    $node->makeAbstract();
                    break;

                case 'static':
                    /** @var Property $node */
                    $node->makeStatic();
                    break;

                case 'final':
                    /** @var Class_|Property $node */
                    $node->makeFinal();
                    break;

                case 'private':
                    /** @var Class_|Property $node */
                    $node->makePrivate();
                    break;

                case 'protected':
                    /** @var Class_|Property $node */
                    $node->makeProtected();
                    break;

                case 'public':
                    /** @var Class_|Property $node */
                    $node->makePublic();
                    break;

                default:
                    throw new InvalidQualifierException(sprintf('Class nodes do not accept the %s qualifier.', $qualifier));
            }
        }
    }


    /**
     * Use the backtrace to find doc comments.
     * Not pretty. (at all).
     *
     * @param Declaration $node the node to collect for.
     */
    protected function collectDocs(Declaration $node){

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
            $doc = new DocComment();
            $doc->setSummary(ltrim($line, '/'));
            $node->setDocComment((string) $doc);

            return;
        } else {
            //Look for doc comments, (this is parsing backwards)
            if(strpos($line, '*/') !== false) {
                $block = '';

                while($line_number-- > 0){
                    $line = $file->current();
                    $block = $line.$block;

                    if(strpos($line, '/**') !== false) {
                        //Break out at start of docblock
                        break;
                    }
                    $file->seek($line_number);
                }
                $node->setDocComment((string) DocComment::createFromDocComment($block));
            }
        }
    }

}