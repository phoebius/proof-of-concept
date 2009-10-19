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
 * Represents a unary prefix expression
 * @ingroup OrmExpression
 */
class PrefixUnaryEntityPropertyExpression extends SingleRowEntityPropertyExpression
{
	function __construct($table, OrmProperty $property, PrefixUnaryExpression $expression)
	{
		parent::__construct($table, $property, $expression);
	}

	/**
	 * @return IDalExpression
	 */
	function toDalExpression()
	{
		return new PrefixUnaryDalExpression(
			new PrefixUnaryExpression(
				$this->getExpression()->getLogicalOperator(),
				$this->getSqlColumn()
			)
		);
	}
}

?>