<?php
/* ***********************************************************************************************
 *
 * Phoebius Framework
 *
 * **********************************************************************************************
 *
 * Copyright (c) 2009 phoebius.org
 *
 * This program is free software; you can redistribute it and/or modify it under the terms
 * of the GNU Lesser General Public License as published by the Free Software Foundation;
 * either version 3 of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU Lesser General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses/>.
 *
 ************************************************************************************************/

final class ShellArg implements IStringCastable, IFactory
{
	private $value;
	private $delimiter;
	private $name;

	/**
	 * @return ShellArg
	 */
	static function create($name = null, $value = null, $delimiter = ' ')
	{
		return new self($name, $value, $delimiter);
	}

	function __construct($name = null, $value = null, $delimiter = ' ')
	{
		if ($name)
		{
			$this->setName($name);
		}

		if ($value)
		{
			if ($name)
			{
				$this->setDelimiter($delimiter);
			}
			$this->setValue($value);
		}
	}

	function getDelimiter()
	{
		return $this->delimiter;
	}

	function getName()
	{
		return $this->name;
	}

	function getValue()
	{
		return $this->value;
	}

	/**
	 * @return ShellArg
	 */
	function setDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;

		return $this;
	}

	/**
	 * @return ShellArg
	 */
	function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return ShellArg
	 */
	function setValue($value)
	{
		$this->value = $value;

		return $this;
	}

	function toString()
	{
		$out = array();

		$out[] = $this->name;

		if ($this->value)
		{
			$out[] = $this->delimiter;
			$out[] = escapeshellarg($this->value);
		}

		return join("", $out);
	}

	function __toString()
	{
		return $this->toString();
	}
}

?>