<?php

namespace com\livinglogic\ul4;


include_once 'com/livinglogic/ul4/ul4.php';


class TemplateClosure implements UL4CallWithContext, UL4MethodCallWithContext, UL4Name, UL4Type, UL4Attributes
{
	private $template;
	private $variables;

	public function __construct(&$template, &$variables)
	{
		$this->template = $template;
		$this->variables = array();
		// The template (i.e. the closure) itself should be visible in the parent variables
		$this->variables[$template->nameUL4()] = $this;
	}

	public function &getTemplate()
	{
		return template;
	}

	public function nameUL4()
	{
		return $this->template->nameUL4();
	}

	public function callUL4($context, $args, $kwargs)
	{
		if (count($args) > 0)
			throw new PositionalArgumentsNotSupportedException($this->nameUL4());

		return self::call($context, $kwargs);
	}

	public function callMethodUL4($context, $methodName, $args, $kwargs)
	{
		if ("render" == $methodName)
		{
			if (count($args) > 0)
				throw new PositionalArgumentsNotSupportedException($methodName);
			$this->render($context, $kwargs);
			return null;
		}
		else if ("renders" == $methodName)
		{
			if (count($args) > 0)
				throw new PositionalArgumentsNotSupportedException($methodName);
			return $this->renders($context, $kwargs);
		}
		else
			throw new UnknownMethodException($methodName);
	}

	public function call($context, $variables)
	{
		return $this->template->call($context, new MapChain($variables, $this->variables));
	}

	public function render($context, $variables)
	{
		$this->template->render($context, new MapChain($variables, $this->variables));
	}

	public function renders($context, $variables)
	{
		return $this->template->renders($context, new MapChain($variables, $this->variables));
	}

	public function typeUL4()
	{
		return "template";
	}

	protected static $attributes;

	public static function static_init()
	{
		self::$attributes = InterpretedTemplate::$attributes;
	}

	public function getAttributeNamesUL4()
	{
		return self::$attributes;
	}

	public function getItemStringUL4($key)
	{
		return $this->template->getItemStringUL4($key);
	}
}

?>
