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
 * Represents the hidden field of the form
 *
 */
class HiddenFormComponent extends FormComponent
{
	function getType()
	{
		return "text";
	}

	private $defaultValue = null;

	/**
	 * Sets the default value for the hidden field.
	 *
	 * @param string $value
	 */
	function setDefaultValue($value)
	{
		$this->defaultValue = $value;
	}

	/**
	 * Gets the default value for the hidden field. If the value has not been set, NULL is return
	 *
	 * @return string
	 */
	function getDefaultvalue()
	{
		return $this->defaultValue;
	}
}

?>