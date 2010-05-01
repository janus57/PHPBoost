<?php
/*##################################################
 *                       AbstractFormFieldset.class.php
 *                            -------------------
 *   begin                : February 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package builder
 * @subpackage form/fieldset
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
abstract class AbstractFormFieldset implements FormFieldset
{
	private $form_id = '';
	protected $fields = array();
	protected $description = '';
	protected $id = '';
	/**
	 * @var boolean
	 */
	protected $disabled = false;

	/**
	 * @var Template
	 */
	private $template = null;

	public function __construct($id, $options = array())
	{
		$this->id = $id;
		$this->compute_options($options);
	}

	protected function compute_options(array &$options)
	{
		foreach($options as $attribute => $value)
		{
			$attribute = strtolower($attribute);
			switch ($attribute)
			{
				case 'description':
					$this->set_description($value);
					unset($options['description']);
					break;
				case 'disabled':
					$this->disabled = $value;
					unset($options['disabled']);
					break;
				default :
					throw new FormBuilderException('The class ' . get_class($this) . ' hasn\'t the ' . $attribute . ' attribute');
			}
		}
	}

	public function set_description($description)
	{
		$this->description = $description;
	}
	
	public function get_html_id()
	{
		return $this->form_id . '_' . $this->id;
	}

	/**
	 * @desc Store fields in the fieldset.
	 * @param FormField $form_field
	 */
	public function add_field(FormField $form_field)
	{
		if (isset($this->fields[$form_field->get_id()]))
		{
			throw new FormBuilderException('Field with identifier "<strong>' . $form_field->get_id() . '</strong>" already exists,
			please chose a different one!');
		}
		$this->fields[$form_field->get_id()] = $form_field;
		$form_field->set_form_id($this->form_id);
		$form_field->set_fieldset_id($this->get_html_id());
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_form_id($form_id)
	{
		$this->form_id = $form_id;
		foreach ($this->fields as $field)
		{
			$field->set_form_id($form_id);
		}
	}

	public function validate()
	{
		$validation_result = true;
		foreach ($this->fields as $field)
		{
			if (!$field->validate())
			{
				$validation_result = false;
				$this->validation_error_messages[] = $field->get_validation_error_message();
			}
		}
		return $validation_result;
	}

	public function get_onsubmit_validations()
	{
		$validations = array();
		foreach ($this->fields as $field)
		{
			$validations[] = $field->get_js_validations();
		}
		return $validations;
	}

	public function get_validation_error_messages()
	{
		return $this->validation_error_messages;
	}

	/**
	 * @return bool
	 */
	public function has_field($field_id)
	{
		return isset($this->fields[$field_id]);
	}

	/**
	 * @return FormField
	 */
	public function get_field($field_id)
	{
		return $this->fields[$field_id];
	}

	public function get_fields()
	{
		return $this->fields;
	}

	protected function assign_template_fields(Template $template)
	{
		$js_tpl = new FileTemplate('framework/builder/form/AddFieldsetJS.tpl');
		
		$js_tpl->assign_vars(array(
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->disabled,
			'FORM_ID' => $this->form_id
		));
		
		$template->add_subtemplate('ADD_FIELDSET_JS', $js_tpl);
		
		$template->assign_vars(array(
            'C_DESCRIPTION' => !empty($this->description),
            'DESCRIPTION' => $this->description,
			'ID' => $this->get_html_id(),
			'C_DISABLED' => $this->disabled,
			'FORM_ID' => $this->form_id
		));
		
		foreach($this->fields as $field)
		{
			$template->assign_block_vars('fields', array(), array(
			'FIELD' => $field->display(),
			));
		}
	}

	/**
	 * @return Template
	 */
	protected function get_template_to_use()
	{
		if ($this->template !== null)
		{
			return $this->template;
		}
		else
		{
			return $this->get_default_template();
		}
	}

	/**
	 * @return Template
	 */
	abstract protected function get_default_template();

	/**
	 * @desc Sets the template to use to display the form. If this method is not called,
	 * a default template will be used (<code>/template/default/framework/builder/form/Form.tpl</code>).
	 * @param Template $template The template to use
	 */
	public function set_template(Template $template)
	{
		$this->template = $template;
	}
}
?>