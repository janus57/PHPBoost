<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
*/

####################################################
#                      English                     #
####################################################

$lang['root_category_description'] = 'Welcome to the web links section of the website!
<br /><br />
One category and one link were created to show you how this module works. Here are some tips to get started on this module.
<br /><br />
<ul class="formatter-ul">
	<li class="formatter-li"> To configure or customize the module homepage your module, go into the <a href="' . WebUrlBuilder::configuration()->relative() . '">module administration</a></li>
	<li class="formatter-li"> To create categories, <a href="' . CategoriesUrlBuilder::add_category(Category::ROOT_CATEGORY, 'web')->relative() . '">clic here</a></li>
	<li class="formatter-li"> To create links, <a href="' . WebUrlBuilder::add()->relative() . '">clic here</a></li>
</ul>
<br />To learn more, don \'t hesitate to consult the documentation for the module on <a href="https://www.phpboost.com">PHPBoost</a> website.';
?>
