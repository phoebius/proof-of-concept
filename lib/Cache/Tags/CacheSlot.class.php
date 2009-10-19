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

abstract class CacheSlot
{
	private $tags = array();

	abstract protected function getSlotId();

	function save($data)
	{
		Assert::notImplemented();
	}

	function get($data)
	{
		Assert::notImplemented();
	}

	function drop()
	{
		Assert::notImplemented();
	}

	/**
	 * @return CacheSlot
	 */
	function addTag(CacheTag $tag)
	{
		$this->tags[] = $tag;

		return $this;
	}

	/**
	 * @return CacheSlot
	 */
	function addTags(array $tags)
	{
		foreach($tags as $tag)
		{
			$this->addTag($tag);
		}

		return $this;
	}

	/**
	 * @return CachePeer
	 */
	protected function getCachePeer()
	{
		return Cache::getDefaultPeer();
	}
}

?>