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
 * Организует автоматизированный регистратор входящих/выходящих форм, зарегистрированных в
 * системе: подписывает формы для того чтобы избежать их подделки, вызывает объекты входящих форм
 */
final class FormObserver extends StaticClass
{
	private static $forms = array();
	private static $signer = null;

	/**
	 * @return FormSigner
	 */
	static function getSigner()
	{
		if (is_null(self::$signer))
		{
			self::$signer = new FormSigner();
		}
		return self::$signer;
	}

	static function signHtmlForm($s, $substitute_values = false)
	{
		if ( $substitute_values )
		{
			$s = HTML_FormPersister::ob_formPersisterHandler($s);
		}
		return self::getSigner()->process($s);
	}

	static function registerForm(Form $form)
	{
		self::$forms[ $form->getName() ] = $form;
		if ( @$_REQUEST[FormSigner::ID_ELT] === $form->getName() )
		{
			$form->call(self::getSigner());
			return true;
		}
		else
		{
			$form->init();
		}
		return false;
	}

	/**
	 * @return Form
	 */
	static function getForm($formName)
	{
		Assert::isTrue(
			isset(self::$forms[ $formName ]),
			"Form with name '{$formName}'' is not registered in global form pool"
		);

		return self::$forms[$formName];
	}

}

?>