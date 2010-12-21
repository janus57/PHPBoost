<?php
/*##################################################
 *                       FormFieldsetVertical.class.php
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
 * @package {@package}
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FormFieldsetVertical extends AbstractFormFieldset
{
    private static $tpl_src = '# INCLUDE ADD_FIELDSET_JS #<div class="vertical_fieldset" id="${escape(ID)}" # IF C_DISABLED # style="display:none;" # ENDIF #># IF C_DESCRIPTION #<p>${escape(DESCRIPTION)}</p># ENDIF ## START fields # # INCLUDE fields.FIELD # # END fields #</div>';

    public function __construct($id, $options = array())
    {
    	parent::__construct($id, $options);
    }
    
    /**
     * @return Template
     */
    public function display()
    {
        $template = $this->get_template_to_use();

        $this->assign_template_fields($template);

        return $template;
    }

    protected function get_default_template()
    {
        return new StringTemplate(self::$tpl_src);
    }
}
?>