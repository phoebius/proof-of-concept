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
 * @ingroup SysLoggers
 */
final class DummyExecutionRecorder implements IExecutionRecorder
{
	/**
	 * @return IExecutionRecorder
	 */
	function putLine()
	{
		return $this;
	}

	/**
	 * White
	 * @return IExecutionRecorder
	 */
	function putInfo($msg)
	{
		return $this;
	}

	/**
	 * White
	 * @return IExecutionRecorder
	 */
	function putInfoLine($msg)
	{
		return $this;
	}

	/**
	 * Blue
	 * @return IExecutionRecorder
	 */
	function putMsg($msg)
	{
		return $this;
	}

	/**
	 * Blue
	 * @return IExecutionRecorder
	 */
	function putMsgLine($msg)
	{
		return $this;
	}

	/**
	 * Yellow
	 * @return IExecutionRecorder
	 */
	function putWarning($msg)
	{
		return $this;
	}

	/**
	 * Yellow
	 * @return IExecutionRecorder
	 */
	function putWarningLine($msg)
	{
		return $this;
	}

	/**
	 * Red
	 * @return IExecutionRecorder
	 */
	function putError($msg)
	{
		return $this;
	}

	/**
	 * Red
	 * @return IExecutionRecorder
	 */
	function putErrorLine($msg)
	{
		return $this;
	}
}

?>