<?php
/*##################################################
 *                           MediaComments.class.php
 *                            -------------------
 *   begin                : September 23, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class MediaComments extends AbstractCommentsExtensionPoint
{
	public function get_authorizations($module_id, $id_in_module)
	{
		global $MEDIA_CATS, $CONFIG_MEDIA;
		
		$cache = new Cache();
		$cache->load($module_id);
		
		require_once(PATH_TO_ROOT .'/'. $module_id . '/media_constant.php');
		
		$id_cat = $this->get_categorie_id($module_id, $id_in_module);
		
		$cat_authorizations = $MEDIA_CATS[$id_cat]['auth'];
		if (!is_array($cat_authorizations))
		{
			$cat_authorizations = $CONFIG_MEDIA['root']['auth'];
		}
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($cat_authorizations, MEDIA_AUTH_READ));
		return $authorizations;
	}
	
	public function is_display($module_id, $id_in_module)
	{
		return true;
	}

	private function get_categorie_id($module_id, $id_in_module)
	{
		$columns = 'idcat';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $id_in_module);
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'media', $columns, $condition, $parameters);
	}
}
?>