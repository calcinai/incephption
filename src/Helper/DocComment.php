<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace Calcinai\Incephption\Helper;

use Calcinai\Incephption\Helper\DocComment\Tag;

class DocComment {

    private $summary;
    private $lines = [];
    private $tags = [];


    public function setSummary($summary){
        $this->summary = $summary;
        return $this;
    }

    public function getSummary() {
        return $this->summary;
    }

    public function setDescriptionLines(array $lines){
        $this->lines = $lines;
        return $this;
    }

    public function addDescriptionLine($text) {
        $this->lines[] = $text;
        return $this;
    }

    public function getDescriptionLines(){
        return $this->lines;
    }

    public function addTag(Tag $tag){
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * merge two doc nodes
     *
     * @param DocComment $new
     * @return $this
     */
    public function merge(DocComment $new){
        foreach($new->lines as $line){
            $this->addDescriptionLine($line);
        }
        return $this;
    }


    /**
     * Very quick impl.  Needs revisit.
     *
     * @param $block
     * @return static
     */
    public static function createFromDocComment($block) {
        // split at each line
        $in_description = true;
        $self = new self();
        foreach(preg_split("/(\r?\n)/", $block) as $line) {

            //Parse the bits - obviously redundant for description lines, but that's ok.
            //What a horrible regex.
            if(!preg_match('/^\s*\*\s*(?<line>(\@(?<tag>\w+)\s*)?(?<all_after_tag>((?<var_type>[\w\\_]+)\s*)?(\$(?<var_name>[\w_]+)\s*)?(?<description>.*[^\/])?))?$/i', $line, $matches)) {
                continue;
            }

            if($in_description && !empty($matches['tag'])){
                $in_description = false;
                //If the last line is blank, remove it.
                if(empty(end($self->lines))){
                    array_pop($self->lines);
                }
            }

            if($in_description){
                //On an empty line, if the description only has one line and there's no summary, move it to the summary.
                if(empty($matches['line']) && !isset($self->summary) && !isset($self->lines[1])){
                    $self->summary = array_pop($self->lines);
                    continue;
                }

                //Allow blank ones to be added
                $self->lines[] = $matches['line'];

            } elseif(!empty($matches['tag'])) {
                //The following logic makes some small assumptions, ultimately, it wasn't worth defining a handler per tag.
                $tag = new Tag($matches['tag']);

                if(!empty($matches['var_name'])){
                    $tag->setVarName($matches['var_name']);

                    if(isset($matches['var_type'])){
                        $tag->setVarType($matches['var_type']);
                    }

                    if(isset($matches['description'])){
                        $tag->setDescription($matches['description']);
                    }
                } elseif(!isset($matches['description']) && isset($matches['var_type'])) {
                    $tag->setVarType($matches['var_type']);
                } elseif(isset($matches['all_after_tag'])) {
                    $tag->setDescription($matches['all_after_tag']);
                }

                $self->addTag($tag);
            }
        }

        return $self;
    }


    public function __toString() {

        $output = sprintf("/**\n");

        if($this->summary !== null){
            $output .= sprintf(" * %s\n", $this->summary);
            $output .= sprintf(" *\n");
        }

        foreach($this->lines as $line){
            $output .= sprintf(" * %s\n", $line);
        }

        if(!empty($this->tags)){
            $output .= sprintf(" *\n");
        }

        foreach($this->tags as $tag) {
            $output .= sprintf(" * %s\n", (string) $tag);
        }

        $output .= sprintf(" */");

        return $output;
    }

}