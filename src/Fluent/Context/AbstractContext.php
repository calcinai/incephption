<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Fluent\Context;

abstract class AbstractContext {

    /**
     * @var AbstractContext
     */
    private $parent_context;

    private $pending_qualifiers;

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
        if($context === false){
            $this->pending_qualifiers[] = $name;
        }

        return $context;
    }


}