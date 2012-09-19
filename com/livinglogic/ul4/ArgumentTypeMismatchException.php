<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ArgumentTypeMismatchException extends \Exception
{
	public function __construct($template, $args)
	{
		parent::__construct(self::format($template, $args));
	}

	private static function format($template, $args)
	{
		$parts = explode("{}", $template . " not supported!");
		$buffer = "";

		for ($i = 0; $i < count($parts); ++$i)
		{
			if ($i != 0)
				$buffer .= Utils::objectType($args[$i-1]);
			$buffer .= ($parts[$i]);
		}

		return $buffer;
	}
}

?>