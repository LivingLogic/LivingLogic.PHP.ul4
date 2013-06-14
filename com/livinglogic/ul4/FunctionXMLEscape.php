<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class FunctionXMLEscape extends _Function
{
	public function nameUL4()
	{
		return "xmlescape";
	}

	protected function makeSignature()
	{
		return new Signature(
				$this->nameUL4(),
				"obj", Signature::$required
		);
	}

	public function evaluate($args)
	{
		return call($args[0]);
	}

	public static function call($obj)
	{
		if (is_null($obj))
			return "";

		$str = FunctionStr::str($obj);
		$length = strlen($str);

		$search = array( "&");
		$replace = array("&amp;");
		$str = str_replace($search, $replace, $str);

		$search = array( "<"   , ">"   , "'"    , '"'     );
		$replace = array("&lt;", "&gt;", "&#39;", '&quot;');
		$str = str_replace($search, $replace, $str);

		$buffer = "";
		for ($i = 0; $i < 32; $i++)
		{
			$c = chr($i);
			if ($c != '\t' && $c != '\n' && $c != '\r')
				$str = str_replace($c, "&#$i;", $str);
		}
		for ($i = 128; $i < 160; $i++)
			$str = str_replace(chr($i), "&#$i;", $str);

		return $str;
	}

}

?>