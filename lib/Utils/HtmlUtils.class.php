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

final class HtmlUtils extends StaticClass
{

	// Version 1.01.
	// Заменяет ссылки на их HTML-эквиваленты ("подчеркивает ссылки").
	// Работает с УЖЕ ПРОКВОЧЕННЫМ HTML-кодом!
	static function hrefActivate($text) {
	  $text = preg_replace_callback(
	    '{
	      (?:
	        # ВНИМАНИЕ: \w+ вместо (?:http|ftp) СИЛЬНО ТОРОМОЗИТ!!!
	        ((?:http|ftp)://)   # протокол с двумя слэшами
	        | www\.             # или просто начинается на www
	      )
	      (?> [a-z0-9_-]+ (?>\.[a-z0-9_-]+)* )   # имя хоста
	      (?: : \d+)?                            # порт
	      (?: &amp; | [^[\]&\s\x00»«"<>])*       # URI (но БЕЗ кавычек)
	      (?:                 # последний символ должен быть...
	          (?<! [[:punct:]] )  # НЕ пунктуацией
	        | (?<= &amp; | [-/&+*]     )  # но допустимо окончание на -/&+*
	      )
	      (?= [^<>]* (?! </a) (?: < | $)) # НЕ внутри тэга
	    }xis',
	    array(__CLASS__,"hrefCallback"),
	    $text
	  );
	  return $text;
	}

	// Функция обратного вызова для preg_replace_callback().
	function hrefCallback($p) {
	  $name = $p[0];
	  // ВНИМАНИЕ!!!
	  // htmlspeicalchars() НЕ ИСПОЛЬЗУЕТСЯ, т.к. функция вызывается в момент,
	  // когда все спецсимволы И ТАК проквочены. Это - специфика phpBB.
	  // Если нет протокола, добавляем его в начало строки.
	  $href = (!empty($p[1])? $name : "http://$name");
	  // Если ссылка на текущий сайт, пробуем преобразовать ее в имя страницы.
	  if (preg_match("{^http://{$_SERVER['SERVER_NAME']}(?::\d+)?(/.+)$}si", $href, $p)) {
	    if (function_exists($getter='getPageTitleByUri')) //#TODO
		// Можно также определить функцию getPageTitleByUri(), возвращающую title страницы по ее URI -
		// тогда она будет использоваться для замены ссылки на имя страницы.
	      $name = $getter($p[1]);
	  }
	  $href = str_replace('"', '&amp;', $href); // на всякий случай
	  if ($name === null) $name = $href;
	  $html = "<a href=\"$href\" target=\"_blank\">$name</a>";
	  return $html;
	}
}

?>