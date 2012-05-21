<?php

namespace com\livinglogic\ul4on;

include_once 'com/livinglogic/ul4/ul4.php';

use com\livinglogic\ul4on\Encoder as Encoder;
use com\livinglogic\ul4on\Decoder as Decoder;

class Utils
{
	public static $registry = array();

	public static function register($name, $factory)
	{
		self::$registry[$name] = $factory;
	}

	/**
	 * Return the serialized output of the object <code>data</code>.
	 * @param data the object to be dumped.
	 * @return the serialized object
	 */
	public static function dumps($data)
	{
		$encoder = new Encoder();
		$encoder->dump($data);

		return $encoder->buffer;
	}

	/**
	 * Load an object by reading in the UL4ON object serialization format reader <code>reader</code>.
	 * @param reader The Reader from which to read the object
	 * @return the deserialized object
	 */
	/*
	public static function load(Reader reader) throws IOException
	{
		try
		{
			return new Decoder(reader).load();
		}
		catch (IOException e)
		{
			// can't happen
			return null; // keeps the compiler happy
		}
	}
	*/

	/**
	 * Load an object by reading in the UL4ON object serialization format from the string <code>s</code>.
	 * @param s The object in serialized form
	 * @return the deserialized object
	 */
	/*
	public static Object loads(String s)
	{
		try
		{
			return load(new StringReader(s));
		}
		catch (IOException e)
		{
			// can only happen on short reads
			throw new RuntimeException(e);
		}
	}
	*/

	/**
	 * Load an object by reading in the UL4ON object serialization format from the CLOB <code>clob</code>.
	 * @param clob The CLOB that contains the object in serialized form
	 * @return the deserialized object
	 */
	/*
	public static Object load(Clob clob) throws IOException, SQLException
	{
		return load(clob.getCharacterStream());
	}
	*/


	public static function makeDate($year, $month, $day, $hour=0, $minute=0, $second=0, $microsecond=0)
	{
		$dt = new \DateTime();
		$dt->setDate($year, $month, $day);
		$dt->setTime($hour, $minute, $second);

		return $dt;
	}

	public static function isList($obj)
	{
		if (! is_array($obj))
			return false;

		$keys = array_keys($obj);
		for ($i = 0; $i < count($keys); $i++)
		{
			$key = $keys[$i];
			if (! is_int($key))
				return false;
		}

		return true;
	}

	public static function isDict($obj)
	{
		if (! is_array($obj))
			return false;

		$keys = array_keys($obj);
		for ($i = 0; $i < count($keys); $i++)
		{
			$key = $keys[$i];
			if (! is_string($key))
				return false;
		}

		return true;
	}

}
?>