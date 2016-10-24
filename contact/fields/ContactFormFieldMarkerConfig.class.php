<?php
/*##################################################
 *		      ContacusFormFieldMarkerConfig.class.php
 *                            -------------------
 *   begin                : April 15, 2016
 *   copyright            : (C) 2016 Sebastien Lartigue
 *   email                : babso@web33.fr
 *
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
 * @author Sebastien Lartigue <babso@web33.fr>
 */

class ContactFormFieldMarkerConfig extends AbstractFormField
{
	private $max_input = 200;
	
	public function __construct($id, $label, array $value = array(), array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}

	function display()
	{
		$template = $this->get_template_to_use();

		$tpl = new FileTemplate('contact/ContactFormFieldMarkerConfig.tpl');
		$tpl->add_lang(LangLoader::get('common', 'contact'));
		
		$tpl->put_all(array(
			'NAME' => $this->get_html_id(),
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->is_disabled()
		));

		$this->assign_common_template_variables($template);

		$i = 0;
		foreach ($this->get_value() as $id => $options)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'LATITUDE' => $options['latitude'],
				'LONGITUDE' => $options['longitude'],
				'POPUP' => $options['popup']
			));
			$i++;
		}

		if ($i == 0)
		{
			$tpl->assign_block_vars('fieldelements', array(
				'ID' => $i,
				'LATITUDE' => '',
				'LONGITUDE' => '',
				'POPUP' => ''
			));
		}

		$tpl->put_all(array(
			'MAX_INPUT' => $this->max_input,
			'NBR_FIELDS' => $i == 0 ? 1 : $i
		));

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $tpl->render()
		));

		return $template;
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i < $this->max_input; $i++)
		{
			$field_popup_id = 'field_popup_' . $this->get_html_id() . '_' . $i;
			$field_latitude_id = 'field_latitude_' . $this->get_html_id() . '_' . $i;
			$field_longitude_id = 'field_longitude_' . $this->get_html_id() . '_' . $i;
			
			if ($request->has_postparameter($field_popup_id) && $request->has_postparameter($field_latitude_id) && $request->has_postparameter($field_longitude_id))
			{
				$field_popup = $request->get_poststring($field_popup_id);
				$field_latitude = $request->get_poststring($field_latitude_id);
				$field_longitude = $request->get_poststring($field_longitude_id);
				
				if (!empty($field_popup) && !empty($field_latitude) && !empty($field_longitude))
					$values[] = array(
						'popup' => $field_popup,
						'latitude' => $field_latitude,
						'longitude' => $field_longitude
					);
			}
		}
		$this->set_value($values);
	}

	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
					break;
			}
		}
		parent::compute_options($field_options);
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormField.tpl');
	}
}
?>