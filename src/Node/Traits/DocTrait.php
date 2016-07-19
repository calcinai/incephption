<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node\Traits;


use Calcinai\Incephption\Node\DocCommentNode;

trait DocTrait {

    /**
     * @var DocCommentNode
     */
    private $doc;

    public function addDoc(DocCommentNode $doc){

        //If there is already one, use it.
        if($this->doc !== null){
            $this->doc->merge($doc);
        } else {
            $this->doc = $doc;
        }

        return $this;
    }
}