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
 * FIXME: reimplement this class to support multi-field properties
 * Represents binary expression
 * @ingroup OrmExpression
 */
class BinaryEntityPropertyExpression extends SingleRowEntityPropertyExpression
{
	function __construct(EntityProperty $ep, BinaryExpression $expression)
	{
		parent::__construct($ep, $expression);
	}

	/**
	 * @return IDalExpression
	 */
	function toDalExpression()
	{
		return new BinaryDalExpression(
			$this->getSqlColumn(),
			new BinaryExpression(
				$this->getExpression()->getLogicalOperator(),
				$this->getSqlValue($this->getExpression()->getValue())
			)
		);
	}
}

?>