<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionAsUL4ON implements _Function
{
	public function call($context, $args)
	{
      if (count($args) == 1)
         return self::_call($args[0]);
      throw new ArgumentCountMismatchException("function", "asul4on", count($args), 1);
	}

	public function getName()
	{
		return "asul4on";
	}

   public static function _call($obj)
   {
		return \com\livinglogic\ul4on\Utils::dumps($obj);
   }
}

?>