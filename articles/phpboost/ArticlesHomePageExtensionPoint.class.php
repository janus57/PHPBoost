<?php
/*##################################################
 *                     ArticlesHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 27, 2012
 *   copyright            : (C) 2012 K�vin MASSY
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

class ArticlesHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $ARTICLES_LANG;
		
		return $ARTICLES_LANG['title_articles'];
	}
	
	private function get_view()
	{
		global $idartcat, $Session, $User, $invisible, $Cache, $ARTICLES_CAT, $LANG, $ARTICLES_LANG, $Bread_crumb;
		require_once(PATH_TO_ROOT . '/articles/articles_begin.php'); 
		
		// Initialisation des imports.
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$Pagination = new DeprecatedPagination();
		
		$articles_config = ArticlesConfig::load();
		
		//Récupération des éléments de configuration
		$config_auth = $articles_config->get_authorization();
		$config_nbr_columns = $articles_config->get_nbr_columns();
		$config_nbr_cat_max = $articles_config->get_nbr_cat_max();
		$config_nbr_articles_max = $articles_config->get_nbr_articles_max();
		$config_note_max = $articles_config->get_note_max();
		
		if ($idartcat > 0)
		{
			if (!isset($ARTICLES_CAT[$idartcat]) || $ARTICLES_CAT[$idartcat]['visible'] == 0)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$cat_links = '';
			$cat_links .= ' <a href="articles' . url('.php?cat=' . $idartcat, '-' . $idartcat . '.php') . '">' . $ARTICLES_CAT[$idartcat]['name'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '" . $idartcat . "' AND ac.visible = 1";
		}
		else //Racine.
		{
			$cat_links = ' <a href="articles.php">' . $ARTICLES_LANG['title_articles'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '0' AND ac.visible = 1";
		}

		$tpl = new FileTemplate('articles/articles_cat.tpl');

		/*//Niveau d'autorisation de la cat�gorie
		if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ))
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		*/

		$nbr_articles = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE visible = 1 AND idcat = '" . $idartcat . "' AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)", __LINE__, __FILE__);
		$nbr_articles_invisible = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE visible = 0 AND idcat = '" . $idartcat . "' AND user_id != -1 AND start > '" . $now->get_timestamp() . "' AND (end <= '" . $now->get_timestamp() . "' OR start = 0)", __LINE__, __FILE__);
		$total_cat = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES_CAT . " ac " . $clause_cat, __LINE__, __FILE__);

		$rewrite_title = Url::encode_rewrite($ARTICLES_CAT[$idartcat]['name']);

		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$selected_fields = array(
			'alpha' => '',
			'view' => '',
			'date' => '',
			'com' => '',
			'note' => '',
			'author'=>'',
			'asc' => '',
			'desc' => '',
			);

		switch ($get_sort)
		{
			case 'alpha' :
				$sort = 'title';
				$selected_fields['alpha'] = ' selected="selected"';
				break;
			case 'com' :
				$sort = 'nbr_com';
				$selected_fields['com'] = ' selected="selected"';
				break;
			case 'date' :
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
				break;
			case 'view' :
				$sort = 'views';
				$selected_fields['view'] = ' selected="selected"';
				break;
			case 'note' :
				$sort = 'note';
				$selected_fields['note'] = ' selected="selected"';
				break;
			case 'author' :
				$sort = 'a.user_id';
				$selected_fields['author'] = ' selected="selected"';
				break;
			default :
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
		}

		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		if ($mode == 'ASC')
		$selected_fields['asc'] = ' selected="selected"';
		else
		$selected_fields['desc'] = ' selected="selected"';

		//Colonnes des cat�gories.
		$nbr_column_cats = ($total_cat > $config_nbr_columns) ? $config_nbr_columns : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);

		$group_color = User::get_group_color($User->get_attribute('user_groups'), $User->get_attribute('level'));
		$array_class = array('member', 'modo', 'admin');

		$tpl->put_all(array(
		'C_WRITE'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE),
		'C_IS_ADMIN' => $User->check_level(User::ADMIN_LEVEL) ? true : false,
		'C_ADD' => $User->check_auth($config_auth, AUTH_ARTICLES_CONTRIBUTE) || $User->check_auth($config_auth, AUTH_ARTICLES_WRITE),
		'C_EDIT' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE) || $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE) ,
		'IDCAT' => $idartcat,
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'SELECTED_ALPHA' => $selected_fields['alpha'],
		'SELECTED_COM' => $selected_fields['com'],
		'SELECTED_DATE' => $selected_fields['date'],
		'SELECTED_VIEW' => $selected_fields['view'],
		'SELECTED_NOTE' => $selected_fields['note'],
		'SELECTED_AUTHOR' => $selected_fields['author'],
		'SELECTED_ASC' => $selected_fields['asc'],
		'SELECTED_DESC' => $selected_fields['desc'],
		'TARGET_ON_CHANGE_ORDER' => ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? 'category-' . $idartcat . '.php?' : 'articles.php?cat=' . $idartcat . '&',
		'L_DATE' => $LANG['date'],
		'L_VIEW' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_COM' => $LANG['com'],
		'L_DESC' => $LANG['desc'],
		'L_ASC' => $LANG['asc'],
		'L_TITLE'=> $LANG['title'],
		'L_WRITTEN' => $LANG['written_by'],
		'L_ARTICLES' => $ARTICLES_LANG['articles'],
		'L_AUTHOR' => $ARTICLES_LANG['author'],
		'L_ORDER_BY' => $ARTICLES_LANG['order_by'],
		'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
		'L_TOTAL_ARTICLE' => ($nbr_articles > 0) ? sprintf($ARTICLES_LANG['nbr_articles_info'], $nbr_articles) : '',
		'L_NO_ARTICLES' => ($nbr_articles == 0) ? $ARTICLES_LANG['none_article'] : '',
		'L_ARTICLES_INDEX' => $ARTICLES_LANG['title_articles'],
		'L_CATEGORIES' => ($ARTICLES_CAT[$idartcat]['order'] >= 0) ? $ARTICLES_LANG['sub_categories'] : $LANG['categories'],
		'U_ADD' => url('management.php?new=1&amp;cat=' . $idartcat),
		'U_EDIT'=> url('admin_articles_cat.php?edit='.$idartcat),
		'U_ARTICLES_CAT_LINKS' => trim($cat_links, ' &raquo;'),
		'U_ARTICLES_WAITING'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE) ? ' <a href="articles.php?invisible=1&amp;cat='.$idartcat.'">' . $ARTICLES_LANG['waiting_articles'] . '</a>' : '',
		'U_ARTICLES_ALPHA_TOP' => url('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=desc'),
		'U_ARTICLES_ALPHA_BOTTOM' => url('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=asc'),
		'U_ARTICLES_DATE_TOP' => url('.php?sort=date&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=desc'),
		'U_ARTICLES_DATE_BOTTOM' => url('.php?sort=date&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=asc'),
		'U_ARTICLES_VIEW_TOP' => url('.php?sort=view&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=desc'),
		'U_ARTICLES_VIEW_BOTTOM' => url('.php?sort=view&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=asc'),
		'U_ARTICLES_NOTE_TOP' => url('.php?sort=note&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=desc'),
		'U_ARTICLES_NOTE_BOTTOM' => url('.php?sort=note&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=asc'),
		'U_ARTICLES_COM_TOP' => url('.php?sort=com&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=desc'),
		'U_ARTICLES_COM_BOTTOM' => url('.php?sort=com&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=asc')
		));

		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

		//On cr�e une pagination si le nombre de fichiers est trop important.

		$Pagination = new DeprecatedPagination();

		//Cat�gories non autoris�es.
		$unauth_cats_sql = array();
		foreach ($ARTICLES_CAT as $id => $key)
		{
			if (!$User->check_auth($ARTICLES_CAT[$id]['auth'], AUTH_ARTICLES_READ))
			$unauth_cats_sql[] = $id;
		}
		$nbr_unauth_cats = count($unauth_cats_sql);
		$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND ac.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		##### Cat�gories disponibles #####
		if ($total_cat > 0)
		{
			$tpl->put_all(array(
			'C_ARTICLES_CAT' => true,
			'PAGINATION_CAT' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;pcat=%d', '-' . $idartcat . '-0+' . $rewrite_title . '.php?pcat=%d' . $unget), $total_cat , 'pcat', $config_nbr_cat_max, 3)
			));

			$i = 0;
			$result = $this->sql_querier->query_while("SELECT ac.id, ac.name, ac.auth,ac.description, ac.image, ac.nbr_articles_visible AS nbr_articles
			FROM " . DB_TABLE_ARTICLES_CAT . " ac
			" . $clause_cat . $clause_unauth_cats . "
			ORDER BY ac.id_parent
			" . $this->sql_querier->limit($Pagination->get_first_msg($config_nbr_cat_max, 'pcat'), $config_nbr_cat_max), __LINE__, __FILE__);

			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$tpl->assign_block_vars('cat_list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => FormatingHelper::second_parse($row['description']),
				'ICON_CAT' => !empty($row['image']) ? '<a href="articles' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php') . '"><img src="' . $row['image'] . '" alt="" class="valign_middle" /></a><br />' : '',
				'U_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
				'L_NBR_ARTICLES' => sprintf($ARTICLES_LANG['nbr_articles_info'], $row['nbr_articles']),
				));
			}
			$this->sql_querier->query_close($result);
		}

		##### Affichage des articles #####

		if ($nbr_articles > 0 ||  $invisible)
		{
			$tpl->put_all(array(
			'C_ARTICLES_LINK' => true,
			'PAGINATION' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $config_nbr_articles_max, 3),
			'CAT' => $ARTICLES_CAT[$idartcat]['name']
			));


			$result = $this->sql_querier->query_while("SELECT a.id, a.title,a.description, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com,a.user_id,m.user_id,m.login,m.level
			FROM " . DB_TABLE_ARTICLES . " a
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
			WHERE a.visible = 1 AND a.idcat = '" . $idartcat .	"' AND a.start <= '" . $now->get_timestamp() . "' AND (a.end >= '" . $now->get_timestamp() . "' OR a.end = 0)
			ORDER BY " . $sort . " " . $mode .
			$this->sql_querier->limit($Pagination->get_first_msg($config_nbr_articles_max, 'p'), $config_nbr_articles_max), __LINE__, __FILE__);

			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				//On reccourci le lien si il est trop long.
				$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

				$tpl->assign_block_vars('articles', array(
				'NAME' => $row['title'],
				'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
				'CAT' => $ARTICLES_CAT[$idartcat]['name'],
				'DATE' => gmdate_format('date_format_short', $row['timestamp']),
				'COMPT' => $row['views'],
				'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $config_note_max, 5) : '<em>' . $LANG['no_note'] . '</em>',
				'COM' => $row['nbr_com'],
				'DESCRIPTION'=>FormatingHelper::second_parse($row['description']),
				'U_ARTICLES_PSEUDO'=>'<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
				'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php'),
				'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $idartcat . '-' . $row['id'] . '.php?com=0'),
				'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
				'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				));
			}

			if($invisible && $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE))
			{
				$tpl->put_all(array(
					'C_INVISIBLE'=>true,
					'L_WAITING_ARTICLES' => $ARTICLES_LANG['waiting_articles'],
					'L_NO_ARTICLES_WAITING'=>($nbr_articles_invisible == 0) ? $ARTICLES_LANG['no_articles_waiting'] : '',
					'U_ARTICLES_WAITING'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) ? ' <a href="articles.php?cat='.$idartcat.'">' . $ARTICLES_LANG['publicate_articles'] . '</a>' : ''
				));


				$result = $this->sql_querier->query_while("SELECT a.id, a.title, a.icon, a.timestamp, a.views, a.note, a.nbrnote, a.nbr_com,a.user_id,m.user_id,m.login,m.level
				FROM " . DB_TABLE_ARTICLES . " a
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
				WHERE a.visible = 0 AND a.idcat = '" . $idartcat .	"'  AND a.user_id != -1 AND a.start > '" . $now->get_timestamp() . "' AND (a.end <= '" . $now->get_timestamp() . "' OR a.start = 0)
				ORDER BY " . $sort . " " . $mode .
				$this->sql_querier->limit($Pagination->get_first_msg($config_nbr_articles_max, 'p'), $config_nbr_articles_max), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					//On reccourci le lien si il est trop long.
					$fichier = (strlen($row['title']) > 45 ) ? substr(html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];

					$tpl->assign_block_vars('articles_invisible', array(
						'NAME' => $row['title'],
						'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
						'CAT' => $ARTICLES_CAT[$idartcat]['name'],
						'DATE' => gmdate_format('date_format_short', $row['timestamp']),
						'COMPT' => $row['views'],
						'NOTE' => ($row['nbrnote'] > 0) ? Note::display_img($row['note'], $config_note_max, 5) : '<em>' . $LANG['no_note'] . '</em>',
						'COM' => $row['nbr_com'],
						'U_ARTICLES_PSEUDO'=>'<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
						'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php'),
						'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $idartcat . '-' . $row['id'] . '.php?com=0'),
						'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
						'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					));
				}
			}
			$this->sql_querier->query_close($result);
		}
		return $tpl;
	}
}
?>