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

class MySqlRestore extends DBRestore
{
	private $winDir = "Z:\\usr\\local\\mysql5\\bin";
	private $executable = "mysql.exe";

	function make($file)
	{
		$cmd = new ShellCommand($this->executable, $this->winDir);
		$dbc = $this->getDBConnector();

		//force,skip possible errors
		$cmd->addArg(new ShellArg("-f"));

		$cmd->addArg(new ShellArg('-u', $dbc->getUser()));
		$cmd->addArg(new ShellArg('--password', $dbc->getPassword(), '='));
		$cmd->addArg(new ShellArg('-D', $dbc->getDbName()));
		$cmd->addArg(new ShellArg('-h', $dbc->getHost()));
		$cmd->addArg(new ShellArg("-q"));
		$cmd->addArg(new ShellArg("-s"));
		$cmd->addArg(new ShellArg(' < ', $file));

		$cmd->execute(
			PathResolver::getInstance()
				->getLogDir($this, $dbc->getDriver()->getName() . "." . $dbc->getName() . '.log')
		);
	}
}

?>