<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class InterpretedTemplate extends Block implements UL4Name, UL4CallWithContext, UL4MethodCallWithContext, UL4Type, UL4Attributes
{
	protected static $VERSION = "25";

	public $source;
	protected $name;
	protected $startdelim;
	protected $enddelim;
	public    $keepWhitespace = true;

	public function handleLoopControl($name)
	{
		// dummy implementation for the moment
	}

	public function evaluate($context)
	{
// 		context.put(name, new TemplateClosure(this, context.getVariables()));
		$context->put($this->name, new TemplateClosure($this, $context->getVariables()));
		return null;
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

	/**
	 * Renders the template and returns the resulting string.
	 * @return The render output as a string.
	 */
	public function &renders()
	{
		if (func_num_args() == 0)
		{
			$context = new EvaluationContext(null, null);
			try
			{
				$result = $this->renders($context);
				$context->close();
				return $result;
			}
			catch (\Exception $ex)
			{
				$context->close();
			}
		}
		else if (func_num_args() == 1)
		{
			$obj = func_get_arg(0);

			if ($obj instanceof EvaluationContext)
			{
				$context = $obj;
				$buffer = "";
				$oldBuffer = &$context->setBuffer($buffer);
				try
				{
					$this->render($context);
					$buffer = &$context->setBuffer($oldBuffer);
				}
				catch (\Exception $ex)
				{
					$buffer = &$context->setBuffer($oldBuffer);
				}
				return $buffer;
			}
			else if (\com\livinglogic\ul4on\Utils::isDict($obj))
			{
				$variables = $obj;
				$buffer = "";
				$this->render($buffer, $variables);
				return $buffer;
			}
		}
		else if (func_num_args() == 2)
		{
			$context = func_get_arg(0);
			$variables = func_get_arg(1);
			$oldVariables = $context->setVariables($variables);
			try
			{
				$result = $this->renders($context);
				$context->setVariables($oldVariables);
				return $result;
			}
			catch (\Exception $ex)
			{
				$context->setVariables($oldVariables);
			}
		}
	}

	/**
	 * Renders the template.
	 * @param context   the EvaluationContext.
	 */
	public function render(&$obj)
	{
		if (func_num_args() == 1)
		{
			$context = $obj;
			$oldTemplate = $context->setTemplate($this);
			try
			{
				parent::evaluate($context);
				$context->setTemplate($oldTemplate);
			}
			catch (BreakException $ex)
			{
				$context->setTemplate($oldTemplate);
				throw $ex;
			}
			catch (ContinueException $ex)
			{
				$context->setTemplate($oldTemplate);
				throw $ex;
			}
			catch (ReturnException $ex)
			{
				// ignore return value and end rendering
				$context->setTemplate($oldTemplate);
			}
			catch (Exception $ex)
			{
				$context->setTemplate($oldTemplate);
				throw new TemplateException($ex, $this);
			}
		}
		else if (func_num_args() == 2)
		{
			$variables = func_get_arg(1);
			if ($obj instanceof EvaluationContext)
			{
				$context = $obj;
				$oldVariables = $context->setVariables($variables);
				try
				{
					$this->render($context);
					$context->setVariables($oldVariables);
				}
				catch (\Exception $ex)
				{
					$context->setVariables($oldVariables);
				}
			}
			else
			{
				$buffer = &$obj;
				$context = new EvaluationContext($buffer, $variables);
				try
				{
					$this->render($context);
					$context->close();
				}
				catch (\Exception $ex)
				{
					$context->close();
				}
			}
		}
	}

	public function dumpUL4ON($encoder)
	{
		$encoder->dump(self::$VERSION);
		$encoder->dump($this->source);
		$encoder->dump($this->name);
		$encoder->dump($this->keepWhitespace);
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
		$this->keepWhitespace = $decoder->load();
		$this->startdelim = $decoder->load();
		$this->enddelim = $decoder->load();
		parent::loadUL4ON($decoder);
	}


	public function nameUL4()
	{
		return $this->name;
	}

	public function callUL4($context, $args, $kwargs)
	{
		if (count($args) > 0)
			throw new PositionalArgumentsNotSupportedException($this->name);

		return $this->call($context, $kwargs); // TODO implement me
	}

	public function callMethodUL4($context, $methodName, $args, $kwargs)
	{
		if ("render" == $methodName)
		{
			if (count($args) > 0)
				throw new PositionalArgumentsNotSupportedException($methodName);
			$this->render($context, $kwargs); // TODO implement me
			return null;
		}
		else if ("renders" == $methodName)
		{
			if (count($args) > 0)
				throw new PositionalArgumentsNotSupportedException($methodName);
			return $this->renders($kwargs);  // TODO implement me
		}
		else
			throw new UnknownMethodException($methodName);
	}

	public function typeUL4()
	{
		return "template";
	}

	/**
	 * Executes the function.
	 * @param context   the EvaluationContext.
	 * @return the return value of the function
	 */
	public function call($context)
	{
		print "InterpretedTemplate.call\n";
		if (func_num_args() == 1)
		{
			if ($arg1 instanceof EvaluationContext)
			{
				$oldTemplate = $context->setTemplate($this);
				try
				{
					parent::evaluate($context);
					$context->setTemplate($oldTemplate);
					return null;
				}
				catch (BreakException $ex)
				{
					$context->setTemplate($oldTemplate);
					throw $ex;
				}
				catch (ContinueException $ex)
				{
					$context->setTemplate($oldTemplate);
					throw $ex;
				}
				catch (ReturnException $ex)
				{
					$context->setTemplate($oldTemplate);
					return $ex->getValue();
				}
				catch (\Exception $ex)
				{
					$context->setTemplate($oldTemplate);
					throw new TemplateException($ex, $this);
				}
			}
			else
			{
				$context = new EvaluationContext(null, $args);

				try
				{
					$result = $this->call($context);
					$context->close();
					return $result;
				}
				catch (\Exception $ex)
				{
					$context->close();
				}

			}
		}
		else if (func_num_args() == 2)
		{
			$context = func_get_arg(0);
			$variables = func_get_arg(1);

			$oldVariables = $context->setVariables($variables);
			try
			{
				$result = call(context);
				$context->setVariables($oldVariables);
				return $result;
			}
			catch (\Exception $ex)
			{
				$context->setVariables($oldVariables);
			}

		}
	}

	/**
	 * Executes the function using the passed in variables.
	 * @param variables a map containing the top level variables that should be
	 *                  available to the function code. May be null.
	 * @return the return value of the function
	 */
	/*
	public Object call(EvaluationContext context, Map<String, Object> variables)
	{
		Map<String, Object> oldVariables = context.setVariables(variables);
		try
		{
			return call(context);
		}
		finally
		{
			context.setVariables(oldVariables);
		}
	}
	*/

	/**
	 * Executes the function.
	 * @param variables a map containing the top level variables that should be
	 *                  available to the function code. May be null.
	 * @return the return value of the function
	 */
	/*
	public Object call(Map<String, Object> variables)
	{
		EvaluationContext context = new EvaluationContext(null, variables);
		try
		{
			return call(context);
		}
		finally
		{
			context.close();
		}
	}
	*/



	private static function removeWhitespace($string)
	{
		$buffer = "";
		$keepWS = true;

		for ($i = 0; $i < mb_strlen($string, \com\livinglogic\ul4on\Utils::$encoding); ++$i)
		{
			$c = mb_substr($string, $i, 1, \com\livinglogic\ul4on\Utils::$encoding);

			if ($c == '\n')
				$keepWS = false;
			else if (ctype_space($c))
			{
				if ($keepWS)
					$buffer .= $c;
			}
			else
			{
				$buffer .= $c;
				$keepWS = true;
			}
		}

		return $buffer;
	}

	public function formatText($text)
	{
		return $this->keepWhitespace ? $text : $this->removeWhitespace($text);
	}




	protected static $attributes = null;

	public static function static_init()
	{
		$a = array_merge(Block::$attributes, array("name", "keepws", "startdelim", "enddelim", "source"));
		$a = array_unique($a);
		$a = array_merge($a);

		self::$attributes = $a;
	}

	public function getAttributeNamesUL4()
	{
		return self::$attributes;
	}

	public function getItemStringUL4($key)
	{
		if ("name" == $key)
			return $this->name;
		else if ("keepws" == $key)
			return $this->keepWhitespace;
		else if ("startdelim" == $key)
			return $this->startdelim;
		else if ("enddelim" == $key)
			return $this->enddelim;
		else if ("source" == $key)
			return $this->source;
		else
			return parent::getItemStringUL4($key);
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.template", "\com\livinglogic\ul4\InterpretedTemplate");
InterpretedTemplate::static_init();

?>
