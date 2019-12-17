<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2019 04 03
 * @since       PHPBoost 4.1 - 2015 09 30
*/

####################################################
#                    English                       #
####################################################

$lang['module_title'] = 'Database';
$lang['module_config_title'] = 'Database module configuration';

$lang['database.actions.database_management'] = 'Database management';
$lang['database.actions.db_sql_queries'] = 'SQL queries';

//config
$lang['config.database-tables-optimization-enabled'] = 'Enable auto database tables optimization';
$lang['config.database-tables-optimization-day'] = 'Optimization day';
$lang['config.database-tables-optimization-day.explain'] = 'Executed by night';


//Admin
$lang['database'] = 'Database';
$lang['creation_date'] = 'Creation date';
$lang['database_management'] = 'Database management';
$lang['db_sql_queries'] = 'SQL queries';
$lang['db_explain_actions'] = 'This panel allows you to manage your database. You can see the list of tables used by PHPBoost, their properties and tools which allows you to do basic operations on the tables. You can save your database or some tables by checking them in the list below.';
$lang['db_explain_actions.question'] = 'The optimization of the database allows to reorganize the structure of the tables in order to facilitate the operations to the SQL Server. This operation can be performed automatically if the option is checked in module administration. You can optimize tables manually through this database management panel.
<br />
Repairing isn\'t normally required, but when a problem occurs it may be useful. Before performing this action, please contact the PHPBoost support team.
<br />
<strong>Be careful: </strong>It\'s a heavy operation, and it needs many resources. Do not repair tables unless you\'re told to do so by the PHPBoost support team.';
$lang['db_restore_from_server'] = 'You can use files you didn\'t delete in your last restoration.';
$lang['db_view_file_list'] = 'See list of backup files (<em>cache/backup</em>)';
$lang['import_file_explain'] = 'You can restore your database using a file on your computer. If your file exceed the maximum size allowed by your server (it\'s %s), you must manually upload on your server the backup file in the <em>cache/backup</em> folder.';
$lang['db_restore'] = 'Restore';
$lang['db_table_list'] = 'Tables list';
$lang['db_table_name'] = 'Table';
$lang['db_table_rows'] = 'Records';
$lang['db_table_rows_format'] = 'Format';
$lang['db_table_engine'] = 'Type';
$lang['db_table_structure'] = 'Structure';
$lang['db_table_collation'] = 'Collation';
$lang['db_table_data'] = 'Size';
$lang['db_table_index'] = 'Index';
$lang['db_table_field'] = 'Field';
$lang['db_table_attribute'] = 'Attribute';
$lang['db_table_null'] = 'Null';
$lang['db_table_value'] = 'Value';
$lang['db_table_extra'] = 'Extra';
$lang['db_autoincrement'] = 'Auto increment';
$lang['db_table_free'] = 'Overhead';
$lang['db_selected_tables'] = 'Select';
$lang['db_select_all'] = 'Select all tables';
$lang['db_select_db_for_restore'] = 'Select the database for restore';
$lang['db_for_selected_tables'] = 'Actions to execute on selected tables';
$lang['db_optimize'] = 'Optimize';
$lang['db_repair'] = 'Repair';
$lang['db_insert'] = 'Insert';
$lang['db_backup'] = 'Save';
$lang['database.compress.file'] = 'Compress file';
$lang['db_download'] = 'Download';
$lang['db_succes_repair_tables'] = 'The repair query has been executed successfully on the following tables :<br /><br /><em>%s</em>';
$lang['db_succes_optimize_tables'] = 'The optimized query has been executed successfully on the following tables :<br /><br /><em>%s</em>)';
$lang['db_backup_database'] = 'Save the database';
$lang['db_backup_explain'] = 'In this form, you still have the chance to select the tables you want to save.
<br />
Take a few moments to make sure all the tables that you want to save are selected.';
$lang['db_backup_all'] = 'Data and structure';
$lang['db_backup_struct'] = 'Only structure';
$lang['db_backup_data'] = 'Only data';
$lang['db_backup_success'] = 'Your database was successfully saved. You can download it trough this link : <a href="admin_database.php?read_file=%s">%s</a>';
$lang['db_execute_query'] = 'Run a query on the database';
$lang['db_tools'] = 'Database management tools';
$lang['db_query_explain'] = 'In this administration panel, you can run queries on the database. This interface should be used only when the support asks you to run a query on the database.<br />
<strong>Be careful:</strong> If this query wasn\'t submitted by a member of the support team, you\'re responsible for its execution and any loss of data generated by this query. Don\'t use this module if you do not have the skills required.';
$lang['db_submit_query'] = 'Execute';
$lang['db_query_result'] = 'Result';
$lang['db_executed_query'] = 'SQL query';
$lang['db_confirm_query'] = 'Do you really want to run the following query?';
$lang['db_file_list'] = 'Files list';
$lang['db_confirm_restore'] = 'Are you sure you want to restore your database with the selected save?';
$lang['db_restore_file'] = 'Click on the file you want to restore.';
$lang['db_restore_success'] = 'The restoration query has been executed successfully';
$lang['db_restore_failure'] = 'An error occurred while restoring the database.';
$lang['db_upload_failure'] = 'An error occured during file transfert from it you wish import your database';
$lang['db_file_already_exists'] = 'The file you try to import has the same name of a file in the cache/backup folder. Please rename the file you try to import.';
$lang['db_no_sql_file'] = 'The file to import is not the backup of a database, please provide a correct file to restore.';
$lang['db_backup_not_from_this_site'] = 'The file to import is not the backup of this site, impossible to restore.';
$lang['db_unlink_success'] = 'The file was successfuly deleted!';
$lang['db_unlink_failure'] = 'The file couldn\'t be deleted';
$lang['db_confirm_delete_file'] = 'Do you really want to delete this file?';
$lang['db_confirm_delete_table'] = 'Do you really want to delete this table?';
$lang['db_confirm_truncate_table'] = 'Do you really want to truncate this table?';
$lang['db_confirm_delete_entry'] = 'Do you really want to delete this entry?';
$lang['db_file_does_not_exist'] = 'The file you wish to delete doesn\'t exist or it is not a SQL file';
$lang['db_empty_dir'] = 'The folder is empty';
$lang['db_file_name'] = 'File';
$lang['db_file_weight'] = 'File size';
?>
