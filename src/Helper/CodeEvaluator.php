<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */
namespace Calcinai\Incephption\Helper;

use PhpParser\Node\Stmt\If_;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;

class CodeEvaluator {

    private $root_block;

    public function __construct($code) {

        if(substr($code, 0, 5) !== '<?php'){
            $code = '<?php '.$code;
        }

        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $nodes = $parser->parse($code);

        $printer = new Standard();
        foreach($nodes as $node){
            //print_r($node);
            echo $printer->prettyPrint([$node]);

        }

        //print_r($nodes);

    }


    public function simplify($array) {
        //new Class_()
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