<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class DictItem
{
	abstract function addTo($context, &$dict);

	abstract function object4UL4ON();
}

?>