<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionGet implements _Function
{
	public function call($context, $args)
	{
		if (count($args) == 1)
			return self::_call($context->getVariables(), $args[0]);
		else if (count($args) == 2)
			return self::_call($context->getVariables(), $args[0], $args[1]);
		throw new ArgumentCountMismatchException("function", "get", count($args), 1, 2);
	}

	public function getName()
	{
		return "get";
	}

	public static function _call($variables, $key)
	{
		if (func_num_args() == 2)
         $defaultValue = null;
		else if (func_num_args() == 3)
		   $defaultValue = func_get_arg(2);
		else
			throw new ArgumentCountMismatchException("function", "get", func_num_args(), 1, 2);

      if (array_key_exists($key, $variables))
         return $variables[$key];
      else
         return $defaultValue;
	}
}

?>