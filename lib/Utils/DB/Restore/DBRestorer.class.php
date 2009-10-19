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

class DBRestorer
{
	/**
	 * @var DBRestore
	 */
	private $driver;

	private static $drivers = array
	(
		DBDriver::MYSQL => 'MySqlRestore',
		DBDriver::PGSQL => 'PgSqlRestore'
	);

	function __construct(DBConnector $db)
	{
		Assert::isTrue(
			isset(self::$drivers[$db->getDriver()->getId()]),
			"unknown driver is specified: {$db->getDriver()}"
		);

		$className = self::$drivers[$db->getDriver()->getId()];

		$driver = new ReflectionClass($className);
		Assert::isTrue(
			$driver->isSubclassOf(new ReflectionClass('DBRestore')),
			"{$className} should implement DBRestore"
		);
		$this->driver = $driver->newInstance($db);
	}

	/**
	 * return DBRestorer
	 */
	static function create(DBConnector $db)
	{
		return new self($db);
	}

	function restore($file)
	{
		$this->driver->make($file);
	}
}

?>