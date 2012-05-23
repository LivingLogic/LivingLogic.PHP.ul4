<?php

include_once 'com/livinglogic/ul4/ul4.php';

use \com\livinglogic\ul4on\Utils as Utils;
use \com\livinglogic\ul4\LoadInt as LoadInt;
use \com\livinglogic\ul4\Location as Location;

// $li = new LoadInt(new Location("", "", 0, 0, 42, 42), 23);
// $bo = Utils::dumps($li);
// $obj2 = Utils::loads($bo);

$s1 = "OS24|de.livinglogic.ul4.printOS27|de.livinglogic.ul4.locationS12|<?print 42?>S5|printi0|i12|i8|i10|OS22|de.livinglogic.ul4.int^2|i42|";
$p = \com\livinglogic\ul4on\Utils::loads($s1);
// echo "p = $p\n";
echo "p.location = " . $p->getLocation() . "\n";
// echo "p.location.source = " . $p->getLocation()->source . "\n";
// echo "p.location.type = " . $p->getLocation()->getType() . "\n";
// echo "p.location.starttag = " . $p->getLocation()->starttag . "\n";
// echo "p.location.endtag = " . $p->getLocation()->endtag . "\n";
// echo "p.location.startcode = " . $p->getLocation()->startcode . "\n";
// echo "p.location.endcode = " . $p->getLocation()->endcode . "\n";

?>