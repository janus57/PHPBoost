<?php
/*##################################################
 *                         HTMLTableCheckboxFilter.class.php
 *                            -------------------
 *   begin                : April 18, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc
 * @package {@package}
 */
abstract class HTMLTableCheckboxFilter extends AbstractHTMLTableFilter
{
	public function __construct($name, $label, $checked = FormFieldCheckbox::UNCHECKED)
	{
		$input = new FormFieldCheckbox($name, $label, $checked);
		parent::__construct($name, $input);
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_value_allowed($value)
	{
		return true;
	}
}

?>