#!php
<?php
	include 'Decoder.php';
	include 'Encoder.php';
	
	function test($obj)
	{
		$DEBUG = false;
		$e = new Encoder();
		$e->dump($obj);
		if ($DEBUG)
			echo "e->buffer = " . var_export($e->buffer, true) . "\n";
		$d = new Decoder($e->buffer);
		$obj2 = $d->load();
		if ($DEBUG)
			echo "obj2 = " . $obj2 . "\n";
		
		if ($obj ===  $obj2)
			echo "OK: $obj === $obj2\n";
		else
		{
			echo "ERROR: $obj != $obj2\n";
			echo "obj: " . var_export($obj, true) . "\n";
			echo "obj2: " . var_export($obj2, true) . "\n";
		}
	}
	
// 	$e = new Encoder();
// 	$e->dump(42);
// 	echo "buffer = '" . $e->buffer . "'\n";

	test(NULL);
	test(42);
	test(2.71);
	test(True);
	test(False);
	test("abcxyz");
	
// 	echo sprintf("%F", 1000234.235354) . "\n";
?>