<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class JavascriptSource4Template
{
	private $template; // InterpretedTemplate

	public function __construct($template)
	{
		$this->template = $template;
	}

	public function __toString()
	{
		$buffer = "";
		$buffer .= "ul4.Template.loads(";
		$buffer .= FunctionAsJSON.call(template.dumps());
		$buffer .= ")";
		return $buffer;
	}
}

?>