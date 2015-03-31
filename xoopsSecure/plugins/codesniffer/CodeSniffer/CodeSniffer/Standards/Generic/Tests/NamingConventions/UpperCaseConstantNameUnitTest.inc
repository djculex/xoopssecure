<?php
use Exception as My_Exception, foo\bar, baz;
namespace foo;
namespace foo\bar;
namespace bar\foo\baz;

define('VALID_NAME', true);
define('invalidName', true);
define("VALID_NAME", true);
define("invalidName", true);

/**
 * Class TestClass
 * @package bar\foo\baz
 */
class TestClass extends MyClass, YourClass
{

    const const1 = 'hello';
    const CONST2 = 'hello';

    function test()
    {
        echo constant('VALID_NAME');
        echo VALID_NAME;
        print VALID_NAME;
        echo(VALID_NAME);
        print(VALID_NAME);
        echo constant('invalidName');
        echo invalidName;
        print invalidName;
        echo(invalidName);
        print(invalidName);

        echo constant("VALID_NAME");
        echo constant("invalidName");

        echo 'Hello', VALID_NAME;
        echo 'Hello', invalidName;

        // These might look like constants to
        // poorly written code.
        echo 'Hello there';
        echo "HELLO";
        echo 'HELLO';
        print 'Hello there';
        print "HELLO";
        print 'HELLO';
    }

    /**
     * @param PHP_CodeSniffer $blah
     */
    function myFunc(PHP_CodeSniffer &$blah) {}

    /**
     * @param PHP_CodeSniffer $blah
     */
    function myFunc(PHP_CodeSniffer $blah) {}

}

/**
 * Interface MyInterface
 * @package bar\foo\baz
 */interface MyInterface
{
}

if (($object instanceof Some_Class) === false) {
    $var = <<<EOF
This is some heredoc text.
This is some heredoc text.
This is some heredoc text.

This is some heredoc text.
This is some heredoc text.
This is some heredoc text.
EOF;
}

$var = <<<EOF
This is some heredoc text.
This is some heredoc text.
This is some heredoc text.

This is some heredoc text.
This is some heredoc text.
This is some heredoc text.
EOF;

throw new InvalidSomethingException;

declare(ticks = 1) {
    foreach ($var as $bit) {
        echo $bit;
    }
}

$binary = (binary) $string;

$foo->define('bar');
$foo->getBar()->define('foo');

// This is allowed as it is required for PHPUnit.
if (PHPUnit_MAIN_METHOD == 'PHP_Shell_AllTests::main') {
    PHP_Shell_AllTests::main();
}

/**
 * Class ConstTest
 * @package bar\foo\baz
 */class ConstTest {
    const TESTER = '1';

    /**
     *
     */
    public function __construct() {
        echo constant('self::TESTER');
        echo constant('self::tester');
    }
}

/**
 * Class A
 * @package bar\foo\baz
 */class A {
    /**
     * @param $x
     */
    public static function constant($x) {}
}

A::constant('anything-not-in-uppercase');

// goto
goto gotolabel;

gototarget1:

function ConstTest() {
    gototarget2:
}

switch($foo)
{
    case $bar:
    gototarget3:
}

$a = 2 * ${x} - ${minus};

?>
