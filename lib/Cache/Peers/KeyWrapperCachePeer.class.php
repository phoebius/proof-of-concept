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
class KeyWrapperCachePeer extends CachePeer
{
	private $marker;

	/**
	 * @var CachePeer
	 */
	private $peer;

	function __construct($marker, CachePeer $peer)
	{
		$this->marker = $marker;
		$this->peer = $peer;
	}

	/**
	 * Returns the value specified by key. If key is not defined, NonexistentCacheKeyException is
	 * thrown
	 * @throws NonexistentCacheKeyException
	 */
	function get($key)
	{
		return $this->peer->get($this->mangleKey($key));
	}

	/**
	 * Returns the key=value hash of found values specified by the list of keys
	 * @return array
	 */
	function getList(array $keys)
	{
		//WOW
		$mappedKeys = array();
		$mangledKeys = array();
		foreach ($keys as $key)
		{
			$mangledKey = $this->mangleKey($key);
			$mappedKeys[$mangledKey] = $key;
			$mangledKeys[] = $mangledKey;
		}

		$fetchedValues = $this->peer->getList($mangledKeys);
		$fetchedDemangledValues = array();
		foreach ($fetchedValues as $fetchedMangledKey => $fetchedValue)
		{
			$fetchedDemangledKey = $mappedKeys[$fetchedMangledKey];
			$fetchedDemangledValues[$fetchedDemangledKey] = $fetchedValue;
		}

		return $fetchedDemangledValues;
	}

	/**
	 * Sets the value
	 * @return CachePeer
	 */
	function set($key, $value, $ttl = CacheTtl::HOUR)
	{
		return $this->peer->set($this->mangleKey($key), $value, $ttl);
	}

	/**
	 * Sets the value only if it is not defined by key inside cache peer
	 * @return CachePeer
	 */
	function add($key, $value, $ttl = CacheTtl::HOUR)
	{
		return $this->peer->add($this->mangleKey($key), $value, $ttl);
	}

	/**
	 * Sets the value only if it is already defined by key
	 * @return CachePeer
	 */
	function replace($key, $value, $ttl = CacheTtl::HOUR)
	{
		return $this->peer->replace($this->mangleKey($key), $value, $ttl);
	}

	/**
	 * Drops the value specified by the key
	 * @return CachePeer
	 */
	function drop($key)
	{
		return $this->peer->drop($this->mangleKey($key));
	}

	private function mangleKey($key)
	{
		return $this->marker . '_' . $key;
	}
}

?>