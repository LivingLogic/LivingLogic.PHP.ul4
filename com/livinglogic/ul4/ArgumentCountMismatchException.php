<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ArgumentCountMismatchException extends \Exception
{
	public function __construct($type, $name, $given, $requiredMin, $requiredMax=-1)
	{
		if (count(func_get_args()) == 4 || $requiredMax == -1)
			$requiredMax = $requiredMin;

		parent::__construct($type . " " . $name . "() expects " . ($requiredMin == $requiredMax ? "exactly " . $requiredMin . " argument" . ($requiredMin==1 ? "" : "s") : $requiredMin . "-" . $requiredMax . " arguments") . ", " . $given . " given!");
	}
}

?>