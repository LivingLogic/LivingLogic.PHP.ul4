<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

interface Method
{
	public function evaluate($context, $obj, $args);

	public function getName();
}

?>