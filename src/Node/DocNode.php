<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Node;


class DocNode extends AbstractNode {

    private $lines = [];

    public function addLine($text) {
        $this->lines[] = $text;
        return $this;
    }

    /**
     * merge two doc nodes
     *
     * @param DocNode $new
     */
    public function merge(DocNode $new){
        foreach($new->lines as $line){
            $this->addLine($line);
        }
    }
}