<?php

namespace Calcinai\Incephption;

use PhpParser\Node;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */
class CodeEvaluator {

    private $root_block;
    private $printer;

    public function __construct($code) {

        if(substr($code, 0, 5) !== '<?php'){
            $code = '<?php '.$code;
        }

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $nodes = $parser->parse($code);

        $this->printer = new Standard();

        $this->simplifyNodes($nodes);

        echo $this->printer->prettyPrint($nodes);

        exit;
    }



    public function simplifyNodes($nodes){
        foreach($nodes as $node){
            $simplified_nodes = $this->simplifyNode($node);

        }
        return $simplified_nodes;
    }


    public function simplifyNode($node, $static = []) {

        if($node instanceof Node\Stmt\If_){
            return $this->simplifyIf($node);
        } else {
            return $node;
        }


//
//        foreach($nodes as $node_index => $node) {
//
//            if($node instanceof Node\Stmt\If_){
//                $replacement = $this->simplifyIf($node);
//            } else {
//                return $nodes;
//            }
//
//
//
//            array_splice($nodes, $node_index, 1, $replacement);
//
//            //Not sure if this is the best way to do this, but the splice chanes the size and mucks with the pointer.
//            $new_index = $node_index + count($replacement);
//            while($new_index--){
//                next($nodes);
//            }
//
//        }


    }


    public function simplifyIf(Node\Stmt\If_ $if){

        print_r($if);

        return $this->simplifyNodes($if->stmts);
    }


    public static function fromReflection(\ReflectionFunctionAbstract $reflection) {
        $file = new \SplFileObject($reflection->getFileName());
        $file->seek($reflection->getStartLine()-1);

        $lines_remaining = $reflection->getEndLine() - $reflection->getStartLine();
        $code = '';
        while($lines_remaining--){
            $code .= $file->getCurrentLine();
        }

        //Remove leading and trailing braces
        $code = ltrim($code, "\n {");
        $code = substr($code, 0, strrpos($code, '}'));

        return new self($code);
    }


}