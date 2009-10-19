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

/**
 * Represents the OO layer for php filters to provide a type-safe usage of filter callbacks
 *
 */
final class Filter implements IFilter
{
	private static $filtersTypes = array();

	/**
	 * Gets all possible filter types
	 *
	 * @return array
	 */
	static function getRegisteredFilters()
	{
		if (empty(self::$filtersTypes))
		{
			foreach(filter_list() as $filter_name)
			{
				self::$filtersTypes[ filter_id($filter_name) ] = $filter_name;
			}
		}
		return self::$filtersTypes;
	}

	private $type;
	private $options;

	/**
	 * Creates a new filter of the specified type with the specified options
	 *
	 * @param int $type
	 * @param array $options
	 */
	function __construct($type, array $options = array())
	{
		Assert::isTrue(
			array_key_exists($type,self::getRegisteredFilters()),
			"Undefined filter: {$type}"
		);

		$this->type = $type;
		$this->options = $options;
	}

	/**
	 * Passes the variable throgh the filter and determines whether it passed or failed to pass
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	function pass($value)
	{
		$result = filter_var($value,$this->type,$this->options);
		return $result === false ? false : true;
	}
}

?>