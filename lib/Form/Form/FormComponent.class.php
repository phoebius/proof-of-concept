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
 * Represents a form component
 */
abstract class FormComponent implements  IFactory
{
	private $name;
	private static $types = array
	(
		"single", "multiple", "action", "file", "text"
	);

	/**
	 * Gets the list of supported form component types
	 * @return array
	 */
	static function getComponentTypes()
	{
		return self::$types;
	}

	final function __construct($name)
	{
		$this->name = $name;
		Assert::isTrue(
			in_array($this->getType(), self::getComponentTypes()),
			sprintf(
				'%s implements undefined form component type (%s). Consider using '.
				'the types, defined by Form_Input::GetFormatInputTypes()',
				get_class($this), $this->getType()
			)
		);

	}

	final function getName()
	{
		return $this->name;
	}

	final function validate(Form $owner)
	{
		$value = @$_REQUEST[$this->GetName()];
		if ($this->getFilterContainer()->validate($value))
		{
			$errors = $this->getFilterContainer()->getErrors();
			foreach($errors as $error)
			{
				$owner->addError($error,true);
			}
		}
	}

	/**
	 * @var FilterContainer
	 */
	private $validator = null;

	/**
	 * @return FilterContainer
	 */
	private function getFilterContainer()
	{
		if (is_null($this->validator))
		{
			$this->validator = new FilterContainer();
		}
		return $this->validator;
	}

	/**
	 * @return FormComponent
	 */
	final function addFilter($filter_id, IFilter $filter)
	{
		$this->getFilterContainer()->addFilter($filter_id,$filter);
		return $this;
	}

	/**
	 * @return FormComponent
	 */
	final function dropFilter($filter_id)
	{
		unset($this->filters[ $filter_id ]);
		return $this;
	}

	/**
	 * @return FormComponent
	 */
	final function getFilterIds()
	{
		return array_keys($this->filters);
		return $this;
	}

	/**
	 * @return FormComponent
	 */
	final function dropFilters()
	{
		$this->filters = array();
		return $this;
	}

	abstract function getType();
}

?>