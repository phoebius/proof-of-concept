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
abstract class SingleRowEntityPropertyExpression extends EntityPropertyExpression
{
	/**
	 * @return IDalExpression
	 */
	protected function getSqlColumn()
	{
		$columns = $this->getEntityProperty()->getDBFields();

		Assert::isTrue(sizeof($columns) == 1);

		return new SqlColumn(
			reset($columns),
			$this->getTable()
		);
	}

	/**
	 * @return ISqlValueExpression
	 */
	protected function getSqlValue($value)
	{
		if ($value instanceof ISqlValueExpression) {
			return $value;
		}

		if ($value instanceof EntityProperty) {
			return reset($value->getSqlColumns());
		}

		return reset($this->makeRawValue($value));
	}
}

?>