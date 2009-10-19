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
 * Represents the wrapper for callback filter
 *
 */
class CallbackFilter implements IFilter
{
	/**
	 * @var IDelegate
	 */
	private $callback;

	/**
	 * Creates a new wrapper for the callback that is to be used as a filter
	 *
	 * @param IDelegate $callback
	 */
	function __construct(IDelegate $callback)
	{
		$this->callback = $callback;
	}

	/**
	 * Passes the variable throgh the filter and determines whether it passed or failed to pass
	 *
	 * @param mixed $value
	 * @return boolean
	 */
	function filter($value)
	{
		return (boolean)$this->callback->invokeArgs(array($value));
	}

}
?>