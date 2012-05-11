#!php
<?php

namespace com\livinglogic\ul4on;

	include 'Decoder.php';
	include 'Encoder.php';
	include 'Utils.php';
	include 'Color.php';
	
	function error($obj1, $obj2)
	{
		echo "ERROR: obj1 != obj2\n";
		echo "obj1: " . \var_export($obj1, true) . "\n";
		echo "obj2: " . \var_export($obj2, true) . "\n";
	}

	function test($obj1)
	{
		$DEBUG = false;
		$e = new Encoder();
		$e->dump($obj1);
		if ($DEBUG)
			echo "e->buffer = " . \var_export($e->buffer, true) . "\n";
		$d = new Decoder($e->buffer);
		if ($DEBUG)
			echo "d->buffer = " . \var_export($d->buffer, true) . "\n";
		$obj2 = $d->load();
		if ($DEBUG)
			echo "obj2 = " . $obj2 . "\n";

		if ($obj1 instanceof \DateTime)
		{
			if ($obj1->getTimestamp() === $obj2->getTimestamp())
				echo "OK: DateTime obj1 === DateTime obj2\n";
			else
				error($obj1, $obj2);
		}
		else if ($obj1 instanceof Color)
		{
			if ($obj1 == $obj2)
				echo "OK: color $obj1 == $obj2\n";
			else
				error($obj1, $obj2);
		}
		else
		{
			if ($obj1 === $obj2)
				echo "OK: $obj1 === $obj2\n";
			else
				error($obj1, $obj2);
		}
	}

	test(NULL);
	test(42);
	test(2.71);
	test(True);
	test(False);
	test("abcxyz");
	test(Utils::makeDate(2012, 8, 10, 15, 0, 20));
	test(new Color(20, 40, 60, 100));
?>