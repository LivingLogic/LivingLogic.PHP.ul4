<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class ForNormal extends _For
{
	protected $itername;

	public function __construct($location=null, $container=null, $itername=null)
	{
		parent::__construct($location, $container);
		$this->itername = $itername;
	}

	public function getType()
	{
		return "for";
	}

	public function toString($indent)
	{
		$buffer = "";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";

		$buffer .= "for ";
		$buffer .= $this->itername;
		$buffer .= " in ";
		$buffer .= $this->container->toString($indent);
		$buffer .= "\n";

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";

		$buffer .= "{\n";
		++$indent;
		foreach ($this->content as $item)
			$buffer .= $item->toString($indent);
		--$indent;

		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";

		$buffer .= "}\n";

		return $buffer;
	}

	protected function unpackLoopVariable($context, $item)
	{
		$context->put($this->itername, $item);
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->itername);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->itername = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.for", "\com\livinglogic\ul4\ForNormal");

?>