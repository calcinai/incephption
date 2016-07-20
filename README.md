# incephption
~PHP code generator with a fluent interface~ There's a better use for this.  Needs work!

## Installation
soon

## Usage
You can use the basic OO implemention or the super-fluent interface (shown below).  The idea is for the template to read as much like PHP as 
possible, while still being valid php.

```php
$template = (new Builder())

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


You can obviously stop the chain at any time to insert application logic, and the builder will retain the current context:

```php
$builder = (new Builder())

->doc('This file is part of incephption package.')
->doc('Check x for copyright info.');


//Do some conditional logic
if($use_namespace){
    $builder->namespace('Calcinai\\Test');
}

//then continue
$builder

->abstract->class('Test')->extends('Base')

...
```


## Function Evaluation

I have now replaced the underlying structure with the AST layer from [nikic/php-parser](https://github.com/nikic/PHP-Parser). This is a bit slower
than the previous implementation, but it gives more flexibility.  The functions which can evaluated in static context will be, enabling you 
to template the method body in PHP.


## Code completion

To benefit most from the super-fluent generator, you need a smart IDE. As you change context in your generation chain, the type hinting will show 
what's available in that specific context.
