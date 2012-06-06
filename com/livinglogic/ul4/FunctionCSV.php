<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionCSV implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return Utils::csv($args[0]);
		throw new ArgumentCountMismatchException("function", "csv", count($args), 1);
	}

	public function getName()
	{
		return "csv";
	}
}

?>