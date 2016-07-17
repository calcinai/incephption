<?php
/**
 * @package    incephption
 * @author     Michael Calcinai <michael@calcin.ai>
 */

use Calcinai\Incephption\Node\FileNode;
use Calcinai\Incephption\Fluent\Context\FileContext;

include __DIR__.'/../vendor/autoload.php';

$file = new FileNode();
(new FileContext(null, $file))

    ->doc('This file is part of incephption package.')
    ->doc('Check x for copyright info.')

    /**
     * Docblocks are supported
     */
    ->namespace('Calcinai\\Test')

    ->use('Vendor\\Package\\Class')
    ->use('Vendor2\\Package\\Class')->as('NonConflict')

    /**
     * Something important about the class
     */
    ->class('Test')->extends('Base')
    //->abstract->class('Test')->extends('Base')

    ->use('SomeTrait')
    ->use('SomeOtherTrait')
    ->use('SomeOtherTrait::A')->insteadof('SomeTrait::A')
    ->use('SomeOtherTrait::B')->as->private
    ->use('SomeOtherTrait::C')->as('newMethodName')
//
//    //Inline comments, too!
//    ->const('CONSTANT_ONE', 1)
//    ->const('CONSTANT_TWO', 2)
//
//    ->private('private_property', 'default_value')
//
//    ->public('public_property')
//
//    /**
//     * Comment for this function
//     * Params will be automatically appended below, although you can override a specific param if you need to.
//     * the 'use' in the closure will disappear from generated code, with any of its variables evaluated
//     */
//    ->final->protected->function('helloWorld', function(DateTime $date) use($condition){
//        if($condition){
//            echo $date->format('u');
//        }
//    })
//
//    ->public->static->function('secondFunction', function(){
//        return false;
//    })
;

print_r($file);
