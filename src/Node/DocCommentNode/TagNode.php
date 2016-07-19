<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\DocCommentNode;

use Calcinai\Incephption\Node\AbstractNode;

class TagNode extends AbstractNode {

    private $var_type;
    private $var_name;
    private $description;

    public function setDescription($description){
        $this->description = $description;
        return $this;
    }

    public function setVarType($var_type){
        $this->var_type = $var_type;
        return $this;
    }

    public function setVarName($var_name){
        $this->var_name = $var_name;
        return $this;
    }

    public function __toString() {

        $output = sprintf('@%s', $this->name);

        if($this->var_type !== null){
            $output .= sprintf(' %s', $this->var_type);
        }

        if($this->var_name !== null){
            $output .= sprintf(' $%s', $this->var_name);
        }

        if($this->description !== null){
            $output .= sprintf(' %s', $this->description);
        }

        return $output;
    }

    public function prettyPrint($indent_level = 0) {
        // TODO: Implement prettyPrint() method.
    }
}