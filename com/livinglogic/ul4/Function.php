<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

interface _Function
{
	public function call($context, $args);

	public function getName();
}

?>