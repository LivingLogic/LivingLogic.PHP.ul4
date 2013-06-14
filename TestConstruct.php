<?php

include_once 'com/livinglogic/ul4/ul4.php';

/*
$e = new com\livinglogic\ul4\RemainingKeywordArgumentsException("abc");
$e = new com\livinglogic\ul4\RemainingKeywordArgumentsException(new com\livinglogic\ul4\Signature("gurk", array()));

$s1 = new com\livinglogic\ul4\Signature("required", array());
$s2 = new com\livinglogic\ul4\Signature("required", array());

if ($s1 === $s2)
	print "sind identisch\n";
else
	print "sind nicht identisch\n";

print $e->getMessage() . "\n";

class gurk
{
	public static $functions;

	public static function static_init()
	{
		self::$functions = array("gurk", "hurz");
	}
}

gurk::statci_init();

print_r(gurk::$functions);
*/

class gurk
{
	public function hurz()
	{
		self::print_static();
	}

	private static function print_static()
	{
		print "print_static was called\n";
	}
}

$g = new gurk();
$g->hurz();


?>
