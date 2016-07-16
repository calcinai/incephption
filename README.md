# incephption
PHP code generator with a fluent interface

## Installation
soon

## Usage
You can use the basic OO implemention or the super-fluent interface (shown below).  The idea is for the template to read as much like PHP as possible, while still being valid php.

```php
$template = (new Fluent())

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
->abstract->class('Test')->extends('Base')

    ->use('SomeTrait')
    ->use('SomeOtherTrait')
    ->use('SomeOtherTrait::A')->insteadof('SomeTrait::A')
    ->use('SomeOtherTrait::B')->as->private

    //Inline comments, too!
    ->const('CONSTANT_ONE', 1)
    ->const('CONSTANT_TWO', 2)

    ->private('private_property', 'default_value')

    ->public('public_property')

    /**
     * Comment for this function
     * Params will be automatically appended below, although you can override a specific param if you need to.
     * the 'use' in the closure will disappear from generated code, with any of its variables evaluated
     */
    ->final->protected->function('helloWorld', function(DateTime $date) use($condition){
        if($condition){
            echo $date->format('u');
        }
    })

    ->public->static->function('secondFunction', function(){
        return false;
    })

->compile();

echo $template;
```

Will Result in the code:

```php
<?php
/**
 * This file is part of incephption package.
 * Check x for copyright info.
 */

/**
* Docblocks are supported
*/
namespace Calcinai\Test;

use Vendor\Package\ClassName;
use Vendor2\Package\ClassName as NonConflict;

/**
* Something important about the class
*/
abstract class Test extends Base {

    use SomeTrait;
    use SomeOtherTrait {
        SomeOtherTrait::A insteadof SomeTrait::A;
        B as private;
    }

    //Inline comments, too!
    const CONSTANT_ONE = 1;
    const CONSTANT_TWO = 2;

    /**
     * @var string
     */
    private $private_property = 'default_value';

    /**
     * @var
     */
    public $public_property;

    /**
     * Comment for this function
     * Params will be automatically appended below, although you can override a specific param if you need to.
     * the 'use' in the closure will disappear from generated code, with any of its variables evaluated
     *
     * @param \DateTime $date
     */
    final protected function helloWorld(\DateTime $date){
        echo $date->format('u');
    }

    /**
     * @return bool
     */
    public static function secondFunction(){
        return false;
    }

}
```

More to come!
