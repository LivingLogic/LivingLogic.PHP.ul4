<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

class _For extends Block
{
	protected $varname;
	protected $container;

	public function __construct($location=null, $start=0, $end=0, $varname=null, $container=null)
	{
		parent::__construct($location, $start, $end);
		$this->varname = $varname;
		$this->container = $container;
	}

	public function getType()
	{
		return "for";
	}

	/*
public function toString($indent)
    27 	{
    28 		$buffer = "";
    29 		for ($i = 0; $i < $indent; ++$i)
    30 			$buffer .= "\t";
    31 		$buffer .= "for (";
    32 		$count = 0;
    33 		foreach ($his->iternames as $itername)
    34 		{
    35 			++$count;
    36 			$buffer .= $itername;
    37 			if ($count == 1 || $count != count($this->iternames))
    38 				$buffer .= ", ";
    39 		}
    40 		$buffer .= ") in ";
    41 		$buffer .= $this->container->toString($indent);
    42 		$buffer .= "\n";
    43 		for ($i = 0; $i < $indent; ++$i)
    44 			$buffer .= "\t";
    45 		$buffer .= "{\n";
    46 		++$indent;
    47 		foreach ($this->content as $item)
    48 			$buffer .= $item->toString($indent);
    49 		--$indent;
    50 		for ($i = 0; $i < $indent; ++$i)
    51 			$buffer .= "\t";
    52 		$buffer .= "}\n";
    53 		return $buffer;
    54 	}
    55
	*/

	public function toString($indent)
	{
		$buffer = "";
		for ($i = 0; $i < $indent; ++$i)
			$buffer .= "\t";
		$buffer .=  "for (";
		Utils::formatVarname($buffer, $varname);
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

	public function setContainer($container)
	{
		$this->container = $container;
	}

	public function finish($endlocation)
	{
		parent::finish($endlocation);
		$type = $endlocation->getCode()->strip();

		if (!is_null($type) && mb_strlen($type, \com\livinglogic\ul4on\Utils::$encoding) != 0 && $type != "for")
			throw new BlockException("for ended by end " . $type);
	}

	public function handleLoopControl($name)
	{
		return true;
	}

	public function evaluate($context)
	{
		$container = $this->container->evaluate($context);

		for ($iter = Utils::iterator($container); $iter->valid(); $iter->next())
		{
			$variables = &$context->getVariables();
			Utils::unpackVariable($variables, $this->varname, $iter->current());

			try
			{
				foreach ($this->content as $item)
					$item->evaluate($context);
			}
			catch (BreakException $bex)
			{
				break; // breaking this while loop breaks the evaluated for loop
			}
			catch (ContinueException $cex)
			{
				// doing nothing here does exactly what we need ;)
			}
		}
		return null;
	}

	public function dumpUL4ON($encoder)
	{
		parent::dumpUL4ON($encoder);
		$encoder->dump($this->varname);
		$encoder->dump($this->container);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->varname = $decoder->load();
		$this->container = $decoder->load();
	}
}

\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.for", "\com\livinglogic\ul4\_For");

?>
