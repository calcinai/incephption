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

}