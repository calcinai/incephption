<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */


use Calcinai\Incephption\CodeEvaluator;

include __DIR__.'/../vendor/autoload.php';

$condition = true;

$function = function(DateTime $date, $who) use($condition){
        if($condition == true){
            if($condition == $condition && time()){
                echo $date->format('u');
            }
        } else {
            echo $who;
        }

        switch($condition){
            case true:
                echo $date->format('u');
                break;
            case false:
                echo $who;
        }
    };


print_r(CodeEvaluator::fromReflection(new ReflectionFunction($function)));