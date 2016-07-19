<?php

/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

use Calcinai\Incephption\Fluent\Context\NamespaceContext;

use PhpParser\Builder\Namespace_;

include __DIR__.'/../vendor/autoload.php';

$condition = true;

$namespace = new Namespace_('Calcinai\\Test');

(new NamespaceContext($namespace))

    ->use('Vendor\\Package\\Class')
    ->use('Vendor2\\Package\\Class')->as('NonConflict')

    /**
     * Something important about the class
     * Another line
     */
    ->abstract->class('Test')->extends('Base')

    ->use('SomeTrait')
    ->use('SomeOtherTrait')
    ->use('SomeOtherTrait::A')->insteadof('SomeTrait::A')
    ->use('SomeOtherTrait::B')->as->private
    ->use('SomeOtherTrait::C')->as('newMethodName')

//    //Inline comments, too!
//    ->const('CONSTANT_ONE', 1)
//    ->const('CONSTANT_TWO', 2)
//
//    ->private->static('private_property', 'default_value')
//
//    ->public('public_property')
//
//    /**
//     * Summary for this function
//     *
//     * Params will be automatically appended below, although you can override a specific param if you need to.
//     * If you're using PHP7, you can type hint scalars too.
//     * The 'use' in the closure will disappear from generated code, with any of its variables evaluated
//     *
//     * @param string $who Override automatic doc for this variable
//     */
//    ->final->protected->function('helloWorld', function(DateTime $date, $who) use($condition){
//        if($condition == true){
//            if($condition == $condition){
//                echo $date->format('u');
//            }
//        } else {
//            echo $who;
//        }
//
//        switch($condition){
//            case true:
//                echo $date->format('u');
//                break;
//            case false:
//                echo $who;
//        }
//    })
//
//    ->public->static->function('secondFunction', function(){
//        return false;
//    })
;

$printer = new \PhpParser\PrettyPrinter\Standard();
echo $printer->prettyPrintFile([$namespace->getNode()]);
