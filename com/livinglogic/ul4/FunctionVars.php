<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionVars implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 0)
			return $context->getVariables();
		throw new ArgumentCountMismatchException("function", "vars", count($args), 0);
	}

	public function getName()
	{
		return "vars";
	}
}

?>