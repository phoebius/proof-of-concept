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

abstract class FullCacheTag
{
	private $peer;
	private $tagId;

	function __construct($tagId, CachePeer $peer = null)
	{
		$this->tagId = $tagId;
		$this->peer = $peer;
	}

	protected function getTagId()
	{
		Assert::isNotEmpty(
			$this->tagId,
			sprintf(
				'Your descendant %s should call %s::__construct() to initialize the object',
				get_class($this), __CLASS__
			)
		);

		return $this->tagId;
	}

	/**
	 * @return CachePeer
	 */
	protected function getCachePeer()
	{
		if ($this->peer)
		{
			return $this->peer;
		}
		else
		{
			return parent::getCachePeer();
		}
	}
}

?>