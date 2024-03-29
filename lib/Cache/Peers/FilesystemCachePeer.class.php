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

class FilesystemCachePeer extends CachePeer
{
	private $directory;

	function __construct($directory = null)
	{
		if (!$directory || !is_dir($directory))
		{
			$this->directory = PathResolver::getInstance()->getTmpDir($this, $directory);
		}

		$this->directory = $directory;
	}

	/**
	 * @see CachePeer::add()
	 * @return FilesystemCachePeer
	 */
	function add($key, $value, $ttl)
	{
		if (!$this->isKeyValid($key))
		{
			$this->set($key, $value, $ttl);
		}

		return $this;
	}

	/**
	 * @see CachePeer::replace()
	 * @return FilesystemCachePeer
	 */
	function replace($key, $value, $ttl)
	{
		if ($this->isKeyValid($key))
		{
			$this->set($key, $value, $ttl);
		}

		return $this;
	}

	/**
	 * @see CachePeer::set()
	 * @return FilesystemCachePeer
	 */
	function set($key, $value, $ttl)
	{
		$filename = $this->getFilenameByKey($key);
		file_put_contents(
			$filename,
			$this->packValue($value)
		);
		touch($filename, time() + $ttl);

		return $this;
	}

	/**
	 * @see CachePeer::drop()
	 * @return FilesystemCachePeer
	 */
	function drop($key)
	{
		try
		{
			unlink($this->getFilenameByKey($key));
		}
		catch (ExecutionContextException $e)
		{
			//skip
		}

		return $this;
	}

	/**
	 * @see CachePeer::get()
	 * @throws NonexistentCacheKeyException
	 */
	function get($key)
	{
		if ($this->isKeyValid($key))
		{
			$data = file_get_contents($this->getFilenameByKey($key));
			$value = $this->unpackData($data);
			return $value;
		}
		else
		{
			throw new NonexistentCacheKeyException($key);
		}
	}

	/**
	 * @see CachePeer::clean()
	 * @return FilesystemCachePeer
	 */
	function clean()
	{
		FSUtils::cleanDirectory($this->directory);
		return $this;
	}

	/**
	 * @see CachePeer::isAlive()
	 * @return boolean
	 */
	function isAlive()
	{
		return true;
	}

	private function getFilenameByKey($key)
	{
		$filename = $this->directory . DIRECTORY_SEPARATOR . sha1($key);
		return $filename;
	}

	private function isKeyValid($key)
	{
		$filename = $this->getFilenameByKey($key);
		return
			   file_exists($filename)
			&& filemtime($filename) >= time();
	}
}

?>