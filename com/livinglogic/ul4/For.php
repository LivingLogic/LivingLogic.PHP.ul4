<?php

namespace com\livinglogic\ul4;

include_once 'com/livinglogic/ul4/ul4.php';

abstract class _For extends Block
{
	protected $container;

	public function __construct($location, $container)
	{
		parent::__construct($location);
		$this->container = $container;
	}

	public function setContainer($container)
	{
		$this->container = $container;
	}

	public function finish($template, $startLocation, $endLocation)
	{
		$type = trim($endLocation->getCode());

		if (!is_null($type) && strlen($type) != 0 && $type != "for")
			throw new BlockException("for ended by end " . $type);  // TODO
	}

	public function handleLoopControl($name)
	{
		return true;
	}

	abstract protected function unpackLoopVariable($context, $item);

	public function evaluate($context)
	{
		$container = $this->container->evaluate($context);

		$iter = Utils::iterator($container);

		for ($iter = Utils::iterator($container); $iter->valid(); $iter->next())
		{
			$this->unpackLoopVariable($context, $iter->current());

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
		$encoder->dump($this->container);
	}

	public function loadUL4ON($decoder)
	{
		parent::loadUL4ON($decoder);
		$this->container = $decoder->load();
	}
}

// TODO ???
//\com\livinglogic\ul4on\Utils::register("de.livinglogic.ul4.add", "\com\livinglogic\ul4\Add");

?>