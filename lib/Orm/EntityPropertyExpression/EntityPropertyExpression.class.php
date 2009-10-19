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
 * @ingroup OrmExpression
 */
abstract class EntityPropertyExpression implements IEntityPropertyExpression
{
	/**
	 * @var EntityProperty
	 */
	private $ep;

	/**
	 * @var OrmProperty
	 */
	private $property;

	/**
	 * @var IExpression
	 */
	private $expression;

	/**
	 * @return EntityExpression
	 */
	static function create(EntityProperty $ep, IExpression $expression)
	{
		return new self ($ep, $expression);
	}

	function __construct(EntityProperty $ep, IExpression $expression)
	{
		$this->ep = $ep;
		$this->expression = $expression;
	}

	/**
	 * @return EntityProperty
	 */
	function getEntityProperty()
	{
		return $this->ep;
	}

	/**
	 * @return OrmProperty
	 */
	function getProperty()
	{
		Return $this->ep->getProperty();
	}

	/**
	 * @return IExpression
	 */
	function getExpression()
	{
		return $this->expression;
	}

	/**
	 * @return string
	 */
	protected function getTableOrAlias()
	{
		return $this->ep->getEntityQuery()->getAlias();
	}

	/**
	 * @return array
	 */
	protected function makeRawValue($value)
	{
		return array_combine(
			$this->getProperty()->getDBFields(),
			$this->getProperty()->getType()->makeRawValue($value)
		);
	}
}

?>