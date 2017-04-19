<?php
/*##################################################
 *		      GoogleMapsFormFieldMultipleMarkers.class.php
 *                            -------------------
 *   begin                : April 3, 2017
 *   copyright            : (C) 2017 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class GoogleMapsFormFieldMultipleMarkers extends AbstractFormField
{
	private $max_input = 50;
	
	/**
	 * @var Usefull to know if we have to include all the necessary JS includes
	 */
	private $include_api = true;
	
	/**
	 * @desc Constructs a GoogleMapsFormFieldSimpleAddress.
	 * @param string $id Field identifier
	 * @param string $label Field label
	 * @param string $value Default value
	 * @param string[] $field_options Map containing the options
	 * @param FormFieldConstraint[] $constraints The constraints checked during the validation
	 */
	public function __construct($id, $label, $value, array $field_options = array(), array $constraints = array())
	{
		parent::__construct($id, $label, $value, $field_options, $constraints);
	}
	
	/**
	 * @return string The html code for the input.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();
		$config   = GoogleMapsConfig::load();
		
		$field_tpl = new FileTemplate('GoogleMaps/GoogleMapsFormFieldMultipleMarkers.tpl');
		$field_tpl->add_lang(LangLoader::get('common', 'GoogleMaps'));
		
		$this->assign_common_template_variables($template);
		
		$unserialized_value = @unserialize($this->get_value());
		$markers = $unserialized_value !== false ? $unserialized_value : $this->get_value();
		
		$i = 0;
		if (is_array($markers))
		{
			if (array_key_exists(0, $markers))
			{
				foreach ($markers as $m)
				{
					if (!($m instanceof GoogleMapsMarker))
					{
						$marker = new GoogleMapsMarker();
						
						$marker->set_properties(array(
							'name' => isset($m['name']) ? $m['name'] : '', 
							'address' => isset($m['address']) ? $m['address'] : '', 
							'latitude' => isset($m['latitude']) ? $m['latitude'] : '',
							'longitude' => isset($m['longitude']) ? $m['longitude'] : '',
							'zoom' => isset($m['zoom']) ? $m['zoom'] : 0,
							'address_displayed_on_label' => isset($m['address_displayed_on_label']) ? $m['address_displayed_on_label'] : ''
						));
					}
					else
						$marker = $m;
					
					$field_tpl->assign_block_vars('fieldelements', array_merge($marker->get_array_tpl_vars(), array(
						'ID' => $i
					)));
					
					$i++;
				}
			}
			else
			{
				if (!($markers instanceof GoogleMapsMarker))
				{
					$marker = new GoogleMapsMarker();
					
					$marker->set_properties(array(
						'name' => isset($markers['name']) ? $markers['name'] : '', 
						'address' => isset($markers['address']) ? $markers['address'] : '', 
						'latitude' => isset($markers['latitude']) ? $markers['latitude'] : '',
						'longitude' => isset($markers['longitude']) ? $markers['longitude'] : '',
						'zoom' => isset($markers['zoom']) ? $markers['zoom'] : '',
						'address_displayed_on_label' => isset($markers['address_displayed_on_label']) ? $markers['address_displayed_on_label'] : ''
					));
				}
				else
					$marker = $markers;
				
				$field_tpl->assign_block_vars('fieldelements', array_merge($marker->get_array_tpl_vars(), array(
					'ID' => $i
				)));
				
				$i++;
			}
		}
		else
		{
			if (!($markers instanceof GoogleMapsMarker))
			{
				$marker = new GoogleMapsMarker();
				$marker->set_address($markers);
			}
			else
				$marker = $markers;
			
			$field_tpl->assign_block_vars('fieldelements', array_merge($marker->get_array_tpl_vars(), array(
				'ID' => $i
			)));
			
			$i++;
		}
		
		$field_tpl->put_all(array(
			'C_INCLUDE_API' => $this->include_api,
			'C_CLASS' => !empty($this->get_css_class()),
			'API_KEY' => $config->get_api_key(),
			'DEFAULT_LATITUDE' => $config->get_default_marker_latitude(),
			'DEFAULT_LONGITUDE' => $config->get_default_marker_longitude(),
			'NAME' => $this->get_html_id(),
			'HTML_ID' => $this->get_html_id(),
			'CLASS' => $this->get_css_class(),
			'C_READONLY' => $this->is_readonly(),
			'C_DISABLED' => $this->is_disabled(),
			'MAX_INPUT' => $this->max_input,
		 	'NBR_FIELDS' => $i
		));
		
		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $field_tpl->render()
		));
		
		return $template;
	}
	
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		$values = array();
		for ($i = 0; $i <= $this->max_input; $i++)
		{
			$field_address_id = $this->get_html_id() . '_' . $i;
			if ($request->has_postparameter($field_address_id))
			{
				$field_name_id = 'name_' . $this->get_html_id() . '_' . $i;
				$field_latitude_id = 'latitude_' . $this->get_html_id() . '_' . $i;
				$field_longitude_id = 'longitude_' . $this->get_html_id() . '_' . $i;
				$field_zoom_id = 'zoom_' . $this->get_html_id() . '_' . $i;
				
				$marker = new GoogleMapsMarker($request->get_poststring($field_address_id), $request->get_poststring($field_latitude_id), $request->get_poststring($field_longitude_id), $request->get_poststring($field_name_id), $request->get_poststring($field_zoom_id));
				
				$values[] = $marker->get_properties();
			}
		}
		
		$this->set_value(TextHelper::serialize($values));
	}
	
	protected function compute_options(array &$field_options)
	{
		foreach($field_options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'max_input':
					$this->max_input = $value;
					unset($field_options['max_input']);
					break;
				
				case 'include_api':
					$this->include_api = (bool)$value;
					unset($field_options['include_api']);
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