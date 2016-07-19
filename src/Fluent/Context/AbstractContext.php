<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

use Calcinai\Incephption\Node\AbstractNode;
use Calcinai\Incephption\Node\DocCommentNode;
use Calcinai\Incephption\Node\Traits\AbstractTrait;
use Calcinai\Incephption\Node\Traits\DocTrait;
use Calcinai\Incephption\Node\Traits\FinalTrait;
use Calcinai\Incephption\Node\Traits\StaticTrait;
use Calcinai\Incephption\Node\Traits\VisibilityTrait;
use Calcinai\Incephption\Exception\InvalidQualifierException;

abstract class AbstractContext {

    /**
     * @var AbstractContext
     */
    protected $parent_context;

    /**
     * @var AbstractNode
     */
    protected $node;


    protected $pending_qualifiers;


    public function __construct(AbstractContext $parent_context = null) {
        $this->parent_context = $parent_context;
        $this->pending_qualifiers = [];
    }

    /**
     * @return AbstractContext
     */
    public function getParentContext() {
        return $this->parent_context;
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

        //Try to apply the qualifier to the current node
        try {
            return $this->applyQualifier($this->node, $name);
        } catch (InvalidQualifierException $e){
            $this->pending_qualifiers[] = $name;
        }

        return $this;
    }


    /**
     * This is to allow a context to apply a qualifier. (private, static etc).
     *
     * @param AbstractNode $node
     * @param $qualifier
     * @return $this
     * @throws InvalidQualifierException
     */
    public function applyQualifier(AbstractNode $node, $qualifier){

        switch($qualifier){
            case 'abstract':
                /** @var AbstractNode|AbstractTrait $node */
                $node->assertTrait(AbstractTrait::class);
                $node->setIsAbstract(true);
                break;

            case 'static':
                /** @var AbstractNode|StaticTrait $node */
                $node->assertTrait(StaticTrait::class);
                $node->setIsStatic(true);
                break;

            case 'final':
                /** @var AbstractNode|FinalTrait $node */
                $node->assertTrait(FinalTrait::class);
                $node->setIsFinal(true);
                break;

            case 'as':
                break;

            case AbstractNode::VISIBILITY_PRIVATE:
            case AbstractNode::VISIBILITY_PROTECTED:
            case AbstractNode::VISIBILITY_PUBLIC:
                /** @var AbstractNode|VisibilityTrait $node */
                $node->assertTrait(VisibilityTrait::class);
                $node->setVisibility($qualifier);
                break;

            default:
                throw new InvalidQualifierException(sprintf('%s do not accept the %s qualifier.', get_class($node), $qualifier));
        }

        return $this;
    }


    /**
     * Process the qualifier queue on a particular node
     *
     * @param AbstractNode $node
     * @return $this
     * @throws InvalidQualifierException
     */
    protected function processQueuedQualifiers(AbstractNode $node){
        while($qualifier = array_pop($this->pending_qualifiers)){
            $this->applyQualifier($node, $qualifier);
        }

        return $this;
    }


    /**
     * Use the backtrace to find doc comments.
     * Not pretty. (at all).
     *
     * @param AbstractNode $node the node to collect for.
     */
    protected function collectDocs(AbstractNode $node){

        /** @var DocTrait $node -  ugh. */

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
            $doc = new DocCommentNode();
            $doc->setSummary(ltrim($line, '/'));
            $node->addDoc($doc);

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
                $node->addDoc(DocCommentNode::createFromDocComment($block));
            }
        }
    }

}