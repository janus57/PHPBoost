<?php
/*##################################################
 *                              wiki_functions.php
 *                            -------------------
 *   begin                : May 6, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if (defined('PHPBOOST') !== true)	exit;

define('WIKI_MENU_MAX_DEPTH', 5);

//Interpr�tation du BBCode en ajoutant la balise [link]
function wiki_parse($var)
{
	//On force le langage de formatage � BBCode
	$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
	$parser = $content_manager->get_parser();
	
	if (MAGIC_QUOTES)
	{
		$var = stripslashes($var);
	}
	
    $parser->set_content($var);
    $parser->parse();
	
    //Parse la balise link
	return preg_replace('`\[link=([a-z0-9+#-_]+)\](.+)\[/link\]`isU', '<a href="/wiki/$1">$2</a>', addslashes($parser->get_content()));
}

//Retour au BBCode en tenant compte de [link]
function wiki_unparse($var)
{
	//Unparse de la balise link
	$var = preg_replace('`<a href="/wiki/([a-z0-9+#-_]+)">(.*)</a>`sU', "[link=$1]$2[/link]", $var);
	
	//On force le langage de formatage � BBCode
	$content_manager = AppContext::get_content_formatting_service()->get_default_factory();
	$unparser = $content_manager->get_unparser();
    $unparser->set_content($var);
    $unparser->parse();
	
	return $unparser->get_content();
}

//Fonction de correction dans le cas o� il n'y a pas de rewriting (balise link consid�re par d�faut le rewriting activ�)
function wiki_no_rewrite($var)
{
	if (!ServerEnvironmentConfig::load()->is_url_rewriting_enabled()) //Pas de rewriting	
		return preg_replace('`<a href="/wiki/([a-z0-9+#-]+)">(.*)</a>`sU', '<a href="/wiki/wiki.php?title=$1">$2</a>', $var);
	else
		return $var;
}

function remove_chapter_number_in_rewrited_title($title)
{
	return Url::encode_rewrite(preg_replace('`((?:[0-9 ]+)|(?:[IVXCL ]+))[\.-](.*)`iU', '$2', $title));
}

//Fonction de d�composition r�cursive (passage par r�f�rence pour la variable content qui passe de cha�ne � tableau de cha�nes (5 niveaux maximum)
function wiki_explode_menu(&$content)
{
	$lines = explode("\n", $content);
	$num_lines = count($lines);
	$max_level_expected = 2;
	
	$list = array();
	
	//We read the text line by line
	$i = 0;
	while ($i < $num_lines)
	{
		for ($level = 2; $level <= $max_level_expected; $level++)
		{
			$matches = array();
			
			//If the line contains a title
			if (preg_match('`^\s*[\-]{' . $level . '}[\s]+(.+)[\s]+[\-]{' . $level . '}(?:<br />)?\s*$`', $lines[$i], $matches))
			{
				$title_name = strip_tags(TextHelper::html_entity_decode($matches[1]));
				
				//We add it to the list
				$list[] = array($level - 1, $title_name);
				//Now we wait one of its children or its brother
				$max_level_expected = min($level + 1, WIKI_MENU_MAX_DEPTH + 1);
				
				//R�insertion
				$class_level = $level - 1;
				$lines[$i] = '<h' . $class_level . ' class="wiki_paragraph' .  $class_level . '" id="paragraph_' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) .'</h' . $class_level . '><br />' . "\n";
			}
		}
		$i++;
	}
	
	$content = implode("\n", $lines);
	
	return $list;
}

//Fonction d'affichage r�cursive
function wiki_display_menu($menu_list)
{
	if (count($menu_list) == 0) //Aucun titre de paragraphe
	{
		return '';
	}
	
	$menu = '';
	$last_level = 0;
		
	foreach ($menu_list as $title)
	{
		$current_level = $title[0];
		
		$title_name = stripslashes($title[1]);		
		$title_link = '<a href="#paragraph_' . Url::encode_rewrite($title_name) . '">' . TextHelper::htmlspecialchars($title_name) . '</a>';
		
		if ($current_level > $last_level)
		{
			$menu .= '<ol class="wiki_list_' . $current_level . '"><li>' . $title_link;
		}
		elseif ($current_level == $last_level)
		{
			$menu .= '</li><li>' . $title_link;
		}
		else
		{
			if (substr($menu, strlen($menu) - 4, 4) == '<li>')
			{
				$menu = substr($menu, 0, strlen($menu) - 4);
			}
			$menu .= str_repeat('</li></ol>', $last_level - $current_level) . '</li><li>' . $title_link;
		}
		$last_level = $title[0];
	}
	
	//End
	if (substr($menu, strlen($menu) - 4, 4) == '<li>')
	{
		$menu = substr($menu, 0, strlen($menu) - 4);
	}
	$menu .= str_repeat('</li></ol>', $last_level);
	
	return $menu;
}

//Cat�gories (affichage si on connait la cat�gorie et qu'on veut reformer l'arborescence)
function display_cat_explorer($id, &$cats, $display_select_link = 1)
{
	global $_WIKI_CATS;
		
	if ($id > 0)
	{
		$id_cat = $id;
		//On remonte l'arborescence des cat�gories afin de savoir quelle cat�gorie d�velopper
		do
		{
			$cats[] = (int)$_WIKI_CATS[$id_cat]['id_parent'];
			$id_cat = (int)$_WIKI_CATS[$id_cat]['id_parent'];
		}	
		while ($id_cat > 0);
	}
	

	//Maintenant qu'on connait l'arborescence on part du d�but
	$cats_list = '<ul class="no-list">' . show_cat_contents(0, $cats, $id, $display_select_link) . '</ul>';
	
	//On liste les cat�gories ouvertes pour la fonction javascript
	$opened_cats_list = '';
	foreach ($cats as $key => $value)
	{
		if ($key != 0)
			$opened_cats_list .= 'cat_status[' . $key . '] = 1;' . "\n";
	}
	return '<script>
	<!--
' . $opened_cats_list . '
	-->
	</script>
	' . $cats_list;
}

//Fonction r�cursive pour l'affichage des cat�gories
function show_cat_contents($id_cat, $cats, $id, $display_select_link)
{
	global $_WIKI_CATS, $Sql, $Template;
	
	$module_data_path = PATH_TO_ROOT . '/wiki/templates/';
	$line = '';
	foreach ($_WIKI_CATS as $key => $value)
	{
		//Si la cat�gorie appartient � la cat�gorie explor�e
		if ($value['id_parent']  == $id_cat)
		{
			if (in_array($key, $cats)) //Si cette cat�gorie contient notre cat�gorie, on l'explore
			{
				$line .= '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="fa fa-minus-square-o" id="img2_' . $key . '"></i><i class="fa fa-folder-open" id="img_' . $key . '"></i></a><a id="class_' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a><span id="cat_' . $key . '">
				<ul>'
				. show_cat_contents($key, $cats, $id, $display_select_link) . '</ul></span></li>';
			}
			else
			{
				//On compte le nombre de cat�gories pr�sentes pour savoir si on donne la possibilit� de faire un sous dossier
				$sub_cats_number = $Sql->query("SELECT COUNT(*) FROM " . PREFIX . "wiki_cats WHERE id_parent = '" . $key . "'", __LINE__, __FILE__);
				//Si cette cat�gorie contient des sous cat�gories, on propose de voir son contenu
				if ($sub_cats_number > 0)
					$line .= '<li class="sub"><a class="parent" href="javascript:show_cat_contents(' . $key . ', ' . ($display_select_link != 0 ? 1 : 0) . ');"><i class="fa fa-extend" id="img2_' . $key . '"></i><i class="fa fa-folder" id="img_' . $key . '"></i></a><a id="class_' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');">' . $value['name'] . '</a><span id="cat_' . $key . '"></span></li>';
				else //Sinon on n'affiche pas le "+"
					$line .= '<li class="sub"><a id="class_' . $key . '" class="' . ($key == $id ? 'selected' : '') . '" href="javascript:' . ($display_select_link != 0 ? 'select_cat' : 'open_cat') . '(' . $key . ');"><i class="fa fa-folder"></i>' . $value['name'] . '</a></li>';
			}
		}
	}
	return "\n" . $line;
}

//Fonction qui d�termine toutes les sous-cat�gories d'une cat�gorie (r�cursive)
function wiki_find_subcats(&$array, $id_cat)
{
	global $_WIKI_CATS;
	//On parcourt les cat�gories et on d�termine les cat�gories filles
	foreach ($_WIKI_CATS as $key => $value)
	{
		if ($value['id_parent'] == $id_cat)
		{
			$array[] = $key;
			//On rappelle la fonction pour la cat�gorie fille
			wiki_find_subcats($array, $key);
		}
	}
}

?>