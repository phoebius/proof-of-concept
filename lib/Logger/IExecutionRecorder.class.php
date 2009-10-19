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
interface IExecutionRecorder
{
	/**
	 * @return IExecutionRecorder
	 */
	function putLine();

	/**
	 * White
	 * @return IExecutionRecorder
	 */
	function putInfo($msg);

	/**
	 * White
	 * @return IExecutionRecorder
	 */
	function putInfoLine($msg);

	/**
	 * Blue
	 * @return IExecutionRecorder
	 */
	function putMsg($msg);

	/**
	 * Blue
	 * @return IExecutionRecorder
	 */
	function putMsgLine($msg);

	/**
	 * Yellow
	 * @return IExecutionRecorder
	 */
	function putWarning($msg);

	/**
	 * Yellow
	 * @return IExecutionRecorder
	 */
	function putWarningLine($msg);

	/**
	 * Red
	 * @return IExecutionRecorder
	 */
	function putError($msg);

	/**
	 * Red
	 * @return IExecutionRecorder
	 */
	function putErrorLine($msg);
}

?>