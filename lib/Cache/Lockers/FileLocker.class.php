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

final class FileLocker extends Locker
{
	private $locks = array();
	private $directory;

	function __construct($customDirectory = null)
	{
		if (!$customDirectory)
		{
			$customDirectory = PathResolver::getInstance()->getTmpDir($this);
		}
		$this->directory = $customDirectory;
	}

	function acquire($key)
	{
		if (isset($this->locks[$key]))
		{
			$rp = $this->locks[$key];
		}
		else
		{
			$rp = fopen($this->getFilenameByKey($key), 'w+');
		}

		if (is_resource($rp))
		{
			$this->locks[$key] = $rp;
			$flock = flock($this->locks[$key], LOCK_EX);
		}

		return $flock;
	}

	function drop($key)
	{
		if (is_resource($this->locks[$key]))
		{
			fclose($this->locks[$key]);
			unset($this->locks[$key]);
		}
	}

	function release($key)
	{
		return flock($this->locks[$key], LOCK_UN);
	}

	function dropAll()
	{
		foreach(array_keys($this->locks) as $key)
		{
			$this->drop($key);
		}
	}

	private function getFilenameByKey($key)
	{
		return $this->directory . '/' . sha1($key);
	}

}

?>