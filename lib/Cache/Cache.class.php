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
final class Cache extends Pool
{
	private $peers = array();
	private $default;

	/**
	 * @return Cache
	 */
	static function getInstance()
	{
		return LazySingleton::instance(__CLASS__);
	}

	/**
	 * @return CachePeer
	 */
	static function getDefault()
	{
		return self::getInstance()->getDefaultPeer();
	}

	/**
	 * @return Cache
	 */
	static function setAsDefault()
	{
		return self::getInstance()->setLastPeerAsDefault();
	}

	/**
	 * @return Cache
	 */
	static function add($peerId, CachePeer $peer)
	{
		self::getInstance()->addPeer($peerId, $peer);
	}

	/**
	 * @return CachePeer
	 */
	static function get($peerId)
	{
		return self::getInstance()->getPeer($peerId);
	}

	/**
	 * @return Cache
	 */
	function addPeer($peerId, CachePeer $peer)
	{
		Assert::isTrue(
			!isset($this->peers[$peerId]),
			"cache peer identified by {$peerId} already defined"
		);

		$this->peers[$peerId] = $peer;
		if (!$this->default)
		{
			$this->default = $peer;
		}

		return $this;
	}

	/**
	 * @return CachePeer
	 */
	function getDefaultPeer()
	{
		Assert::isTrue(
			sizeof($this->peers) > 0,
			'no peers defined, so no default peer inside'
		);

		return $this->default;
	}

	/**
	 * @return Cache
	 */
	function setLastPeerAsDefault()
	{
		Assert::isTrue(
			sizeof($this->peers) > 0,
			'no peers defined, so no default peer inside'
		);

		$this->default = end($this->peers);

		return $this;
	}

	/**
	 * @return CachePeer
	 */
	function getPeer($peerId)
	{
		Assert::isTrue(
			isset($this->peers[$peerId]),
			"unknown cache peer identified by {$peerId}"
		);

		return $this->peers[$peerId];
	}
}

?>