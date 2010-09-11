<?php
/*##################################################
 *                    FunctionCallTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 11 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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

class FunctionCallTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    private $input;
    private $output;
    private $ended = false;

    public function parse(StringInputStream $input, StringOutputStream $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->do_parse();
    }

    private function do_parse()
    {
        $this->process_expression_start();
        $this->process_expression_content();
        $this->process_expression_end();
        if (!$this->ended)
        {
            $this->missing_expression_end();
        }
    }

    private function process_expression_start()
    {
        $this->input->consume_next('\{');
        $this->output->write('\';');
    }

    private function process_expression_end()
    {
        $this->ended = $this->input->next() == '}';
        $this->output->write(';$_result=\'');
    }

    private function process_expression_content()
    {
        $element = new FunctionTemplateSyntaxElement();
        $element->parse($this->input, $this->output);
    }

    private function missing_expression_end()
    {
        throw new TemplateParserException('Missing function call expression end \'}\'', $this->input);
    }
}
?>