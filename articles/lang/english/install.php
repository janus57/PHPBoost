<?php
/**
 * @copyright 	&copy; 2005-2019 PHPBoost
 * @license 	https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version   	PHPBoost 5.2 - last update: 2019 11 03
 * @since   	PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

#####################################################
#                       English                     #
#####################################################

$lang = array();

$lang['default.category.name'] = 'First category';
$lang['default.category.description'] = 'Demonstration of an article';
$lang['default.article.title'] = 'How to begin with the articles module';
$lang['default.article.description'] = '';
$lang['default.article.contents'] = 'This brief article will give you some simple tips to take control of this module.<br />
<br />
<ul class="formatter-ul">
<li class="formatter-li">To configure your module, <a href="' . ArticlesUrlBuilder::configuration()->rel() . '">click here</a>
</li><li class="formatter-li">To add categories: <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'articles')->rel() . '">click here</a> (categories and subcategories are infinitely)
</li><li class="formatter-li">To add an item: <a href="' . ArticlesUrlBuilder::add_article()->rel() . '">click here</a>
</li></ul>
<ul class="formatter-ul">
<li class="formatter-li">To format your articles, you can use bbcode language or the WYSIWYG editor (see this <a href="https://www.phpboost.com/wiki/bbcode">article</a>)<br />
</li></ul><br />
<br />
For more information, please see the module documentation on the site <a href="https://www.phpboost.com">PHPBoost</a>.<br />
<br />
<br />
Good use of this module.';

?>
