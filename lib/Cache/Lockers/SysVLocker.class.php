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

final class SysVLocker extends Locker
{
	private $locks = array();

	private function key2int($key)
	{
		return hexdec(substr(md5($key), 0, 8));
	}

	function acquire($key)
	{
		$result = false;

		try
		{
			if (!isset($this->locks[$key]))
			{
				$this->locks[$key] = sem_get($this->key2int($key), 1, 0660, true);
			}

			if ($this->locks[$key])
			{
				$result = sem_acquire($this->locks[$key]);
			}
			else
			{
				unset($this->locks[$key]);
				$result = false;
			}
		}
		catch (ExecutionContextException $e)
		{
			$result = false;
		}

		return $result;
	}

	function drop($key)
	{
		try
		{
			return sem_remove($this->locks[$key]);
		}
		catch (ExecutionContextException $e)
		{
			unset($this->locks[$key]); // already race-removed
			return false;
		}
	}

	function release($key)
	{
		try
		{
			return sem_release($this->locks[$key]);
		}
		catch (ExecutionContextException $e)
		{
			// acquired by another process
			return false;
		}
	}

	function dropAll()
	{
		foreach(array_keys($this->locks) as $key)
		{
			$this->drop($key);
		}
	}

}

?>