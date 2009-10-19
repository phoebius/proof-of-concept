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
 * @ingroup Cache
 */
class RuntimeCachePeer extends CachePeer
{
	private $cache = array();

	/**
	 * @return RuntimeCachePeer
	 */
	static function create()
	{
		return new self();
	}

	/**
	 * @see CachePeer::add()
	 * @return RuntimeCachePeer
	 */
	function add($key, $value, $ttl = CacheTtl::HOUR)
	{
		if (!isset($this->cache[$key]))
		{
			$this->cache[$key] = $value;
		}

		return $this;
	}

	/**
	 * @see CachePeer::clean()
	 * @return RuntimeCachePeer
	 */
	function clean()
	{
		$this->cache = array();

		return $this;
	}

	/**
	 * @see CachePeer::drop()
	 * @return RuntimeCachePeer
	 */
	function drop($key)
	{
		unset($this->cache[$key]);
		return $this;
	}

	/**
	 * @see CachePeer::get()
	 * @throws NonexistentCacheKeyException
	 */
	function get($key)
	{
		if (isset($this->cache[$key]))
		{
			return $this->cache[$key];
		}
		else
		{
			throw new NonexistentCacheKeyException($key);
		}
	}

	/**
	 * @see CachePeer::replace()
	 * @return RuntimeCachePeer
	 */
	function replace($key, $value, $ttl = CacheTtl::HOUR)
	{
		if (isset($this->cache[$key]))
		{
			$this->cache[$key] = $value;
		}

		return $this;
	}

	/**
	 * @see CachePeer::set()
	 * @return RuntimeCachePeer
	 */
	function set($key, $value, $ttl = CacheTtl::HOUR)
	{
		$this->cache[$key] = $value;
		return $this;
	}

	/**
	 * @return array
	 */
	protected function getCache()
	{
		return $this->cache;
	}

}

?>