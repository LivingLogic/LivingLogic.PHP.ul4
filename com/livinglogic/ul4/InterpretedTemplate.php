<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class InterpretedTemplate extends Block /*implements Template*/
{
	protected static $VERSION = "21";
	protected $source;
	protected $name;
	protected $startdelim;
	protected $enddelim;

	public function handleLoopControl($name)
	{
		// dummy implementation for the moment
	}

	public function evaluate($context)
	{
		$context->put($this->name, $this);
	}

	public function getType()
	{
		// dummy implementation for the moment
	}

	public function toString($indent)
	{
		// dummy implementation for the moment
	}

	public static function loads($str)
	{
		return \com\livinglogic\ul4on\Utils::loads($str);
	}

	public function renders($variables)
	{
		$context = new EvaluationContext($variables);
		parent::evaluate($context);
		return $context->getOutput();
	}

	public function dumpUL4ON($encoder)
	{
		$encoder->dump(self::$VERSION);
		$encoder->dump($this->source);
		$encoder->dump($this->name);
		$encoder->dump($this->startdelim);
		$encoder->dump($this->enddelim);
		parent::dumpUL4ON($encoder);
	}

	public function loadUL4ON($decoder)
	{
		$version = $decoder->load();
		if (self::$VERSION != $version)
		{
			throw new \Exception("Invalid version, expected " . self::$VERSION . ", got " . $version);
		}
		$this->source = $decoder->load();
		$this->name = $decoder->load();
		$this->startdelim = $decoder->load();
		$this->enddelim = $decoder->load();
		parent::loadUL4ON($decoder);
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.template", "\com\livinglogic\ul4\InterpretedTemplate");

?>