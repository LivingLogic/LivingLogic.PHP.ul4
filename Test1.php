<?php

class test
{
	public $i = 2;
}

function gurk($obj)
{
	echo "gurk: hash = " . spl_object_hash($obj[0]) . "\n";
// 	$obj = new test();
// 	$obj->i = 42;
}

$t = new test();
$a = array(0 => $t);

echo "      hash = " . spl_object_hash($a[0]). "\n";
gurk($a);
is_
//echo $t->i . "\n";
?>