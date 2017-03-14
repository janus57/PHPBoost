<?php
/*##################################################
 *                       MediaModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 09, 2017
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

class MediaModuleUpdateVersion extends ModuleUpdateVersion
{
	private $querier;
	private $db_utils;
	
	public function __construct()
	{
		parent::__construct('media');
		$this->querier = PersistenceContext::get_querier();
		$this->db_utils = PersistenceContext::get_dbms_utils();
	}
	
	public function execute()
	{
		if (ModulesManager::is_module_installed('media'))
		{
			$tables = $this->db_utils->list_tables(true);
			
			if (in_array(PREFIX . 'media', $tables))
				$this->update_media_table();
			
			$this->update_content();
		}
	}
	
	private function update_media_table()
	{
		$columns = $this->db_utils->desc_table(PREFIX . 'media');
		
		if (!isset($columns['poster']))
			$this->db_utils->add_column(PREFIX . 'media', 'poster', array('type' =>  'string', 'length' => 255, 'default' => "''"));
	}
	
	public function update_content()
	{
		$unparser = new OldBBCodeUnparser();
		$parser = new BBCodeParser();
		
		$result = $this->querier->select('SELECT id, contents FROM ' . PREFIX . 'media');
		
		while($row = $result->fetch())
		{
			$unparser->set_content($row['contents']);
			$unparser->parse();
			$parser->set_content($unparser->get_content());
			$parser->parse();
			
			if ($parser->get_content() != $row['contents'])
				$this->querier->update(PREFIX . 'media', array('contents' => $parser->get_content()), 'WHERE id=:id', array('id' => $row['id']));
		}
		$result->dispose();
	}
}
?>