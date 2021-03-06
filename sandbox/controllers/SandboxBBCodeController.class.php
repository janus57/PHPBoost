<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 05 21
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxBBCodeController extends ModuleController
{
	private $view;
	private $common_lang;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('bbcode', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxBBCodeController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		if (ModulesManager::is_module_installed('wiki')  && ModulesManager::is_module_activated('wiki'))
		{
			// return true when Wiki is on
			$c_wiki = true;

			include_once('../wiki/wiki_functions.php');

			// Create and store the paragraph menu
			$contents = wiki_parse("
				-- " . $this->lang['bbcode.paragraph'] . " 1 --
				" . $this->common_lang['lorem.short.content'] . "
				--- " . $this->lang['bbcode.paragraph'] . " 1.1 ---
				" . $this->common_lang['lorem.short.content'] . "
				---- " . $this->lang['bbcode.paragraph'] . " 1.1.1 ----
				" . $this->common_lang['lorem.short.content'] . "
				----- " . $this->lang['bbcode.paragraph'] . " 1.1.1.1 -----
				" . $this->common_lang['lorem.short.content'] . "
				------ " . $this->lang['bbcode.paragraph'] . " 1.1.1.1.1 ------
				" . $this->common_lang['lorem.short.content'] . "
				------ " . $this->lang['bbcode.paragraph'] . " 1.1.1.1.2 ------
				" . $this->common_lang['lorem.short.content'] . "
				-----  " . $this->lang['bbcode.paragraph'] . " 1.1.1.2 -----
				" . $this->common_lang['lorem.short.content'] . "
				---- " . $this->lang['bbcode.paragraph'] . " 1.1.2 ----
				" . $this->common_lang['lorem.short.content'] . "

				--- " . $this->lang['bbcode.paragraph'] . " 1.2 ---
				" . $this->common_lang['lorem.short.content'] . "

				-- " . $this->lang['bbcode.paragraph'] . " 2 --
				" . $this->common_lang['lorem.short.content'] . "
				-- " . $this->lang['bbcode.paragraph'] . " 3 --
				" . $this->common_lang['lorem.short.content'] . "
			");

			$this->view->assign_block_vars('wikimenu', array(
				'MENU' => wiki_display_menu(wiki_explode_menu($contents))
			));

			$this->view->put('WIKI_CONTENTS', FormatingHelper::second_parse(wiki_no_rewrite($contents)));
		} else {
			// return false when Wiki is off
			$c_wiki = false;
		}

		$this->view->put_all(array(
			'C_WIKI'          => $c_wiki,
			'TYPOGRAPHY'      => self::build_markup('sandbox/pagecontent/bbcode/typography.tpl'),
			'BLOCKS'          => self::build_markup('sandbox/pagecontent/bbcode/blocks.tpl'),
			'CODE'            => self::build_markup('sandbox/pagecontent/bbcode/code.tpl'),
			'LIST'            => self::build_markup('sandbox/pagecontent/bbcode/list.tpl'),
			'TABLE'           => self::build_markup('sandbox/pagecontent/bbcode/table.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);
		$view->add_lang($this->common_lang);
		return $view;
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->common_lang['title.bbcode'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.bbcode']);

		return $response;
	}
}
?>
