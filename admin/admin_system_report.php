<?php
/*##################################################
 *                               admin_system_report.php
 *                            -------------------
 *   begin                : July 14 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *  
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

require_once('../kernel/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../kernel/admin_header.php');

$template = new Template('admin/admin_system_report.tpl');

$template->assign_vars(array(
	'L_YES' => $LANG['yes'],
	'L_NO' => $LANG['no'],
	'L_UNKNOWN' => $LANG['unknown'],
	'L_SYSTEM_REPORT' => $LANG['system_report'],
	'L_SERVER' => $LANG['server'],
	'L_PHP_VERSION' => $LANG['php_version'],
	'L_DBMS_VERSION' => $LANG['dbms_version'],
	'L_GD_LIBRARY' => $LANG['dg_library'],
	'L_URL_REWRITING' => $LANG['url_rewriting'],
	'L_REGISTER_GLOBALS_OPTION' => $LANG['register_globals_option'],
	'L_SERVER_URL' => $LANG['serv_name'],
	'L_SITE_PATH' => $LANG['serv_path'],
	'L_PHPBOOST_CONFIG' => $LANG['phpboost_config'],
	'L_KERNEL_VERSION' => $LANG['kernel_version'],
	'L_DEFAULT_THEME' => $LANG['default_theme'],
	'L_DEFAULT_LANG' => $LANG['default_language'],
	'L_DEFAULT_EDITOR' => $LANG['choose_editor'],
	'L_START_PAGE' => $LANG['start_page'],
	'L_OUTPUT_GZ' => $LANG['output_gz'],
	'L_COOKIE_NAME' => $LANG['cookie_name'],
	'L_SESSION_LENGTH' => $LANG['session_time'],
	'L_SESSION_GUEST_LENGTH' => $LANG['session invit'],
	'L_DIRECTORIES_AUTH' => $LANG['directories_auth'],
	'L_ROOT' => $LANG['root']
));

//Temp variables
$temp_var = function_exists('apache_get_modules') ? apache_get_modules() : array();
$server_path = !empty($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
if( !$server_path )
	$server_path = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
$server_path = trim(str_replace('/admin', '', dirname($server_path)));
$server_name = 'http://' . (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTP_HOST'));

$lang_ini_file = load_ini_file('../lang/', $CONFIG['lang']);

$template->assign_vars(array(
	'PHP_VERSION' => phpversion(),
	'DBMS_VERSION' => $Sql->get_dbms_version(),
	'C_SERVER_GD_LIBRARY' => @extension_loaded('gd'),
	'C_URL_REWRITING_KNOWN' => function_exists('apache_get_modules'),
	'C_SERVER_URL_REWRITING' => function_exists('apache_get_modules') ? !empty($temp_var[5]) : false,
	'C_REGISTER_GLOBALS' => @ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on',
	'SERV_SERV_URL' => $server_name,
	'SERV_SITE_PATH' => $server_path,
	'KERNEL_VERSION' => $CONFIG['version'],
	'KERNEL_SERV_URL' => $CONFIG['server_name'],
	'KERNEL_SITE_PATH' => $CONFIG['server_path'],
	'KERNEL_DEFAULT_LANGUAGE' => $lang_ini_file['name'],
	'KERNEL_DEFAULT_EDITOR' => $CONFIG['editor'] == 'tinymce' ? 'TinyMCE' : 'BBCode',
	'KERNEL_START_PAGE' => $CONFIG['start_page'],
	'C_KERNEL_URL_REWRITING' => (bool)$CONFIG['rewrite'],
	'C_KERNEL_OUTPUT_GZ' => (bool)$CONFIG['ob_gzhandler'],
	'COOKIE_NAME' => $CONFIG['site_cookie'],
	'SESSION_LENGTH' => $CONFIG['site_session'],
	'SESSION_LENGTH_GUEST' => $CONFIG['site_session_invit'],
	'C_AUTH_DIR_CACHE' => is_writable('../cache'),
	'C_AUTH_DIR_UPLOAD' => is_writable('../upload'),
	'C_AUTH_DIR_KERNEL' => is_writable('../kernel'),
	'C_AUTH_DIR_KERNEL_AUTH' => is_writable('../kernel/auth'),
	'C_AUTH_DIR_MENUS' => is_writable('../menus'),
	'C_AUTH_DIR_ROOT' => is_writable('../'),
));

$template->parse();

require_once('../kernel/admin_footer.php');

?>