<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2016 02 11
 * @since       PHPBoost 3.0 - 2012 01 29
*/

class OnlineHomePageExtensionPoint implements HomePageExtensionPoint
{
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), OnlineHomeController::get_view());
	}

	private function get_title()
	{
		return LangLoader::get_message('online', 'common', 'online');
	}
}
?>
