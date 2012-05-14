<?php

namespace com\livinglogic\ul4on;

	include_once 'Decoder.php';
	include_once 'Encoder.php';
	include_once 'Utils.php';
	include_once 'Color.php';
	
	function error($obj1, $obj2)
	{
		echo "ERROR: obj1 != obj2\n";
		echo "obj1: " . \var_export($obj1, true) . "\n";
		echo "obj2: " . \var_export($obj2, true) . "\n";
		return false;
	}

	function test($obj1, $print=false)
	{
		$retVal = false;
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
			{
				echo "OK: DateTime obj1 === DateTime obj2\n";
				return true;
			}
			else
			{
				return error($obj1, $obj2);
			}
		}
		else if ($obj1 instanceof Color)
		{
			if ($obj1 == $obj2)
			{
				echo "OK: color $obj1 == $obj2\n";
				return true;
			}
			else
				return error($obj1, $obj2);
		}
		else if (Utils::isList($obj1))
		{
			if (count($obj1) != count($obj2))
			{
				echo "count(obj1) != count(obj2)\n";
				return false;
			}
			
			for ($i = 0; $i < count($obj1); $i++)
			{
				$retVal = test($obj1[$i]);
				
				if (! $retVal)
					return error($obj1, $obj2);
			}
			
			echo "OK: arrays are identical\n";
		}
		else
		{
			if ($obj1 === $obj2)
			{
				echo "OK: $obj1 === $obj2\n";
				return true;
			}
			else
				return error($obj1, $obj2);
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
	
	$a = array(1, 2.7, "abc", new Color(2, 3, 4, 5));
	test($a);
?>