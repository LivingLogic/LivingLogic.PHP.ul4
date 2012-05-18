<?php

include_once 'com/livinglogic/ul4/Color.php';

use \com\livinglogic\ul4\Color as Color;


$c = Color::fromhsv(0.5, 0.5, 0.5, 0.3);
echo "c = " . $c->__toString() . "\n";
echo "c = " . $c->repr() . "\n";


?>