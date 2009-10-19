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

final class Semaphore extends Pool
{
	/**
	 * @var Locker
	 */
	private $locker = null;

	/**
	 * @return Semaphore
	 */
	static function getInstance()
	{
		return LazySingleton::instance(__CLASS__);
	}

	/**
	 * @return Semaphore
	 */
	static function set(Locker $locker)
	{
		return self::getInstance()->setLocker($locker);
	}

	static function acquire($key)
	{
		return self::getInstance()->getLocker()->acquire($key);
	}

	static function release($key)
	{
		return self::getInstance()->getLocker()->release($key);
	}

	static function drop($key)
	{
		return self::getInstance()->getLocker()->drop($key);
	}

	/**
	 * @return Locker
	 */
	function getLocker()
	{
		Assert::isNotNull(
			$this->locker,
			'locker is not set'
		);

		return $this->locker;
	}

	/**
	 * @return Semaphore
	 */
	function setLocker(Locker $locker)
	{
		$this->locker = $locker;

		return $this;
	}
}

?>