<?php

namespace hurz\gurk;

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\ul4on\Utils as Utils;
use \com\livinglogic\ul4\LoadInt as LoadInt;
use \com\livinglogic\ul4\Location as Location;

$u = new Utils();

if (gettype($u) == "object")
{
	echo "type: " . \get_class($u) . "\n";
}
else
{
	echo "type: " . \gettype($u) . "\n";
}

?>