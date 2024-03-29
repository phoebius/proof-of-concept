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

class SelectFormComponent extends FormComponent
{
	static function create($name)
	{
		return new self($name);
	}

	function getType()
	{
		return "single";
	}

	private $predefinedValues = array();

	/**
	 * Adds the predefined value for this form component, that will represent a component item key
	 * within html element, that is expected in the backend script.
	 *
	 * @param string $value
	 * @return SelectFormComponent
	 */
	function addPredefinedValue($value)
	{
		$this->predefinedValues[] = $value;
		$this->predefinedValues = array_unique($this->predefinedValues);
		return $this;
	}

	/**
	 * Adds the list of predefined values for this form component, that will represent a component
	 * item keys within html element, that is expected in the backend script.
	 *
	 * @param string $value
	 * @return SelectFormComponent
	 */
	function addPredefinedValues(array $predefined_values)
	{
		$this->predefinedValues += $predefined_values;
		$this->predefinedValues = array_unique($this->predefinedValues);
		return $this;
	}

	/**
	 * Drop all predefined values
	 *
	 */
	function dropPredefinedValues()
	{
		$this->predefinedValues = array();
	}

	/**
	 * Drops the specified predefined value
	 *
	 * @param unknown_type $value
	 */
	function dropPredefinedValue($value)
	{
		$idx = array_search($value, $this->predefinedValues, true);
		if ($idx!==false)
		{
			unset($this->predefinedValues[$value]);
		}
	}

	/**
	 * Get all predefined values specified
	 *
	 * @return array
	 */
	function getPredefinedValues()
	{
		return $this->predefinedValues;
	}
}

?>