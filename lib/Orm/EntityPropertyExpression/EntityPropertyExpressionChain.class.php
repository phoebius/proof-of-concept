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
final class EntityPropertyExpressionChain implements IEntityPropertyExpression
{
	/**
	 * @var ExpressionChainLogicalOperator
	 */
	private $expressionChainLogicalOperator;

	/**
	 * @var array of {@link EntityQuery}
	 */
	private $children = array();

	/**
	 * @return EntityQuery
	 */
	static function create(ExpressionChainLogicalOperator $expressionChainLogicalOperator = null)
	{
		return new self ($expressionChainLogicalOperator);
	}

	function __construct(ExpressionChainLogicalOperator $expressionChainLogicalOperator = null)
	{
		$this->expressionChainLogicalOperator =
			$expressionChainLogicalOperator
				? $expressionChainLogicalOperator
				: ExpressionChainLogicalOperator::conditionAnd();
	}

	/**
	 * @return ExpressionChainLogicalOperator
	 */
	function getLogicalOperator()
	{
		return $this->expressionChainLogicalOperator;
	}

	/**
	 * @return EntityExpressionChain
	 */
	function setAndBlock()
	{
		$this->expressionChainLogicalOperator = ExpressionChainLogicalOperator::conditionAnd();

		return $this;
	}

	/**
	 * @return EntityExpressionChain
	 */
	function setOrBlock()
	{
		$this->expressionChainLogicalOperator = ExpressionChainLogicalOperator::conditionOr();

		return $this;
	}

	function add(IEntityPropertyExpression $entityExpression)
	{
		$this->children[] = $entityExpression;

		return $this;
	}

	/**
	 * @return IDalExpression
	 */
	function toDalExpression()
	{
		$chain = new DalExpressionChain($this->expressionChainLogicalOperator);
		foreach ($this->children as $child) {
			$chain->add($child->toDalExpression());
		}

		return $chain;
	}
}

?>