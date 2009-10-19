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
final class SysVCachePeer extends CachePeer
{
	const FIELD_EXPIRES = 'f0:expires';
	const FIELD_DATA = 'f1:data';

	private $size;
	private $peerId;
	private $segmentPtr;

	/**
	 * @return SysVCachePeer
	 */
	static function create($peerId, $size = 1048576)
	{
		return new self($peerId, $size);
	}

	function __construct($peerId, $size = 1048576)
	{
		Assert::isNumeric($size);
		$this->peerId = $peerId;
		$this->size = $size;
	}

	/**
	 * @see CachePeer::add()
	 * @return SysVCachePeer
	 */
	function add($key, $value, $ttl = CacheTtl::HOUR)
	{
		if ($this->isAlive())
		{
			try
			{
				$this->get($key);
			}
			catch (NonexistentCacheKeyException $e)
			{
				//good work, key does not exist, add the tuple
				$this->set($key, $value, $ttl);
			}
		}

		return $this;
	}

	/**
	 * @see CachePeer::replace()
	 * @return SysVCachePeer
	 */
	function replace($key, $value, $ttl = CacheTtl::HOUR)
	{
		if ($this->isAlive())
		{
			try
			{
				$this->get($key);
				$this->set($key, $value, $ttl);
			}
			catch (NonexistentCacheKeyException $e)
			{
				//skip this, the replacement failed
			}
		}

		return $this;
	}

	/**
	 * @see CachePeer::set()
	 *
	 * @return SysVCachePeer
	 */
	function set($key, $value, $ttl = CacheTtl::HOUR)
	{
		Assert::isNumeric($ttl);

		if ($this->isAlive())
		{
			shm_put_var(
				$this->getSegmentPtr(),
				$this->key2int($key),
				array(
					self::FIELD_EXPIRES => time() + $ttl,
					self::FIELD_DATA => $value
				)
			);
		}

		return $this;
	}

	/**
	 * @see CachePeer::drop()
	 *
	 * @return SysVCachePeer
	 */
	function drop($key)
	{
		if ($this->isAlive())
		{
			try
			{
				shm_remove_var($this->getSegmentPtr(), $this->key2int($key));
			}
			catch (ExecutionContextException $e)
			{
			}
		}

		return $this;
	}

	/**
	 * @see CachePeer::get()
	 * @throws NonexistentCacheKeyException
	 *
	 */
	function get($key)
	{
		if ($this->isAlive())
		{
			try
			{
				$value = shm_get_var($this->getSegmentPtr(), $this->key2int($key));
				if (
						   isset($value[self::FIELD_EXPIRES])
						&& isset($value[self::FIELD_DATA])
						&& $value[self::FIELD_EXPIRES] < time()
					)
				{
					return $value[self::FIELD_DATA];
				}
			}
			catch (ExecutionContextException $e)
			{
				throw new NonexistentCacheKeyException($key);
			}
		}
	}

	/**
	 * @see CachePeer::clean()
	 *
	 * @return SysVCachePeer
	 */
	function clean()
	{
		shm_remove($this->getSegmentPtr());
		$this->segmentPtr = null;

		return $this;
	}

	/**
	 * @see CachePeer::isAlive()
	 *
	 * @return boolean
	 */
	function isAlive()
	{
		return !!$this->getSegmentPtr();
	}

	function __destruct()
	{
		if ($this->segmentPtr)
		{
			try
			{
				shm_detach($this->segmentPtr);
			}
			catch (ExecutionContextException $e)
			{
				//Oo
			}
		}
	}

	/**
	 * @see CachePeer::packValue()
	 */
	protected function packValue($value)
	{
		return $value;
	}

	/**
	 * @see CachePeer::unpackData()
	 */
	protected function unpackData($data)
	{
		return $data;
	}

	private function key2int($key)
	{
		return hexdec(substr(md5($key), 0, 8));
	}

	private function getSegmentPtr()
	{
		if (!$this->segmentPtr)
		{
			$id = $this->key2int(__CLASS__ . $this->peerId);
			try
			{
				$this->segmentPtr = shm_attach($id, $this->size);
			}
			catch (ExecutionContextException $e)
			{
				trigger_error(
					sprintf(
						'%s cannot attach shm: %s',
						__CLASS__, $e->getMessage()
					),
					E_USER_ERROR
				);
			}
		}

		return $this->segmentPtr;
	}

}

?>