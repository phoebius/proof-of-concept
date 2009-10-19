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
 * Query workbench implements all the features of the generic queries but is used to fetch
 * the direct results of that queries (not the OO queries as-is)
 * @ingroup PlainQuery
 */
class QueryWorkbench extends ResultWorkbench
{
	/**
	 * @return QueryWorkbench
	 */
	static function create(DB $db)
	{
		return new self($db);
	}

	function select(
			SqlSelectiveFieldSet $fields,
			$table,
			IDalExpression $condition = null,
			SqlOrderChain $orderBy = null,
			$limit = 0,
			$offset = 0
		)
	{
		$query = $this->makeSelectQuery(
			$fields,
			$table,
			$condition,
			$orderBy,
			$limit,
			$offset);

		return $this->getDB()->rawGetRowSet($query);
	}

	function selectJoin(
			SqlSelectiveFieldSet $fields,
			array $tables,
			IDalExpression $condition = null,
			SqlOrderChain $orderBy = null,
			$limit = 0,
			$offset = 0
		)
	{
		$query = $this->makeSelectQuery(
			$fields,
			$$tableNameList,
			$condition,
			$orderBy,
			$limit,
			$offset);

		return $this->getDB()->rawGetRowSet($query);
	}

	function selectRow(SqlSelectiveFieldSet $fields, $table, IDalExpression $condition = null)
	{
		$query = $this->makeSelectQuery(
			$fields,
			$table,
			$condition);

		return $this->getDB()->rawGetRow($query);
	}

	function selectColumn(
			ISelectiveField $field,
			$table,
			IDalExpression $condition = null,
			SqlOrderChain $orderBy = null,
			$limit = 0,
			$offset = 0
		)
	{
		$query = $this->makeSelectQuery(
			SqlSelectiveFieldSet::create()->add($field),
			$table,
			$condition,
			$orderBy,
			$limit,
			$offset
		);

		return $this->getDB()->rawGetColumn($query);
	}

	function selectCell(ISelectiveField $field, $table, IDalExpression $condition = null)
	{
		$query = $this->makeSelectQuery(
			SqlSelectiveFieldSet::create()->add($field),
			$table,
			$condition
		);

		return $this->getDB()->rawGetCell($query);
	}

	private function makeSelectQuery(
			SqlSelectiveFieldSet $fields,
			$source,
			IDalExpression $condition = null,
			SqlOrderChain $orderBy = null,
			$limit = 0,
			$offset = 0
		)
	{
		Assert::isInteger($limit);
		Assert::isInteger($offset);

		$dialect = $this->getDB()->getDialect();
		$query = array();

		$query[] = 'SELECT ' . $fields->toDialectString($dialect);

		if (is_scalar($source))
		{
			$source = $dialect->quoteIdentifier($source);
		}
		else
		{
			Assert::isTrue(is_array($source));

			$tables = array();
			foreach ($source as $table)
			{
				$tables[] = $dialect->quoteIdentifier($table);
			}
			$source = join(", ", $tables);
		}
		$query[] = 'FROM ' . $source;

		if ($condition)
		{
			$query[] = 'WHERE ' . $condition->toDialectString($dialect);
		}

		if ($orderBy)
		{
			$query[] = $orderBy->toDialectString($dialect);
		}

		if ($limit)
		{
			$query[] = 'LIMIT ' . ((int)$limit);
		}

		if ($offset)
		{
			$query[] = 'OFFSET ' . ((int)$offset);
		}

		$query = join("\r\n", $query);

		return $query;
	}

	function insert($table, SqlColumnValueSet $fieldHash)
	{
		$query = array();
		$dialect = $this->getDB()->getDialect();
		$query[] = 'INSERT INTO';
		$query[] = $dialect->quoteIdentifier($table);
		$query[] = $fieldHash->getFields()->toDialectString($dialect);
		$query[] = 'VALUES';
		$query[] = $fieldHash->getValues()->toDialectString($dialect);

		$query = join("\r\n", $query);
		return $this->getDB()->rawQuery($query);
	}

	function update($table, SqlColumnValueSet $fieldHash, IDalExpression $condition)
	{
		$query = array();
		$dialect = $this->getDB()->getDialect();
		$query[] = 'UPDATE';
		$query[] = $dialect->quoteIdentifier($table);
		$query[] = 'SET';
		$query[] = $fieldHash->toDialectString($dialect);
		$query[] = 'WHERE ' . $condition->toDialectString($dialect);

		$query = join("\r\n", $query);
		return $this->getDB()->rawQuery($query);
	}

	function delete($table, IDalExpression $condition)
	{
		$query = array();
		$dialect = $this->getDB()->getDialect();
		$query[] = 'DELETE FROM';
		$query[] = $dialect->quoteIdentifier($table);
		$query[] = 'WHERE ' . $condition->toDialectString($dialect);

		$query = join("\r\n", $query);

		$db = $this->getDB();
		$deleteResourceId = $db->rawQuery($query);
		$affected = $db->getAffectedRowsNumber($deleteResourceId);

		return $affected;
	}
}

?>