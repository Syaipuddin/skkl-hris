<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once "/var/www/language/credentials_constant.php";
/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');



/* MENU LINK */
define('URL_USER_LIST','master_data/user_admin/lists');
define('URL_ADD_NIK_IDCARD','idcard/add_idcard');
define('URL_PERS_ADMIN_LIST','master_data/pers_admin/lists');
define('URL_EDIT_NIK_IDCARD','idcard/edit_idcard');
define('URL_DELETE_NIK_IDCARD','idcard/delete_idcard');

define('URL_ADD_USER_ADMIN','master_data/user_admin/add_user');
define('URL_EDIT_USER_ADMIN','master_data/user_admin/edit_user');
define('URL_DELETE_USER_ADMIN','master_data/user_admin/delete_user');
define('URL_SEARCH_USER','master_data/user_admin/search');
define('URL_SEARCH_PERS_ADMIN','master_data/pers_admin/search');
define('URL_ADD_PERS_ADMIN','master_data/pers_admin/add_persadmin');
define('URL_EDIT_PERS_ADMIN','master_data/pers_admin/edit_persadmin');
define('URL_DELETE_PERS_ADMIN','master_data/pers_admin/delete_persadmin');


define('URL_ADD_USER_ADMIN_EXE','master_data/user_admin/add_user_process');
define('URL_EDIT_USER_ADMIN_EXE','master_data/user_admin/edit_user_process');

define('URL_EXTEND_NIK_IDCARD','idcard/extend_idcard');
define('URL_BROKEN_NIK_IDCARD','idcard/broken_idcard');
define('URL_LOST_NIK_IDCARD','idcard/lost_idcard');


define('URL_SEARCH_NAMA','home/search');
define('URL_ADD_PERS_ADMIN_EXE','master_data/pers_admin/add_persadmin_process');
define('URL_EDIT_PERS_ADMIN_EXE','master_data/pers_admin/edit_persadmin_process');


define('URL_ADD_NIK_IDCARD_EXE','idcard/add_idcard_process');
define('URL_EDIT_NIK_IDCARD_EXE','idcard/edit_idcard_process');

define('URL_EXTEND_NIK_IDCARD_EXE','idcard/add_extend_process');
define('URL_BROKEN_NIK_IDCARD_EXE','idcard/add_broken_process');
define('URL_LOST_NIK_IDCARD_EXE','idcard/add_lost_process');

define('URL_HOME_LIST','home/lists');
define('URL_USER_ADMIN_LISTS','master_data/user_admin/lists');
define('URL_PERS_ADMIN_LISTS','master_data/pers_admin/lists');
define('URL_PERS_ADMIN_SAP_LISTS','master_data/pers_admin_sap/lists');
define('URL_TEMPLATE_MAIN_TOP','layout/header');
define('URL_TEMPLATE_MAIN_BOTTOM','layout/footer');

define('URL_REPORT_LISTS','report/lists');
define('URL_SEARCH_NAMA_REPORT','report/search');
define('URL_TAGIHAN_LISTS','tagihan/lists');
define('URL_SEARCH_NAMA_TAGIHAN','tagihan/search');
define('UPL_FOTO_PATH_APP', "P:/images/foto");

define('UPL_FOTO_PATH', "./assets/images/foto");
define('UPL_ALLOW_TYPE', "jpg|jpeg|png");
define('UPL_MAX_SIZE', "1000");
define('UPL_MAX_WIDTH', "2000");
define('UPL_MAX_HEIGHT', "1700");


define('URL_HOME','backend/home');
 define ('_RETURN_VALUE','return_value');
define ('_RETURN_DETAIL','return_detail');
define ('_NOTIF','notif');
define ('_DIV_ALERT_TYPE','div_alert_type');
define ('_ACTION','action');
define ('_ACTION_DETAIL','action_detail');
define ('_URL_LISTS','url_lists');
define ('_LOGINNAME','loginname');
define('LIMIT_PAGING_MASTER_DATA',20);
define('PAGING_NAV_FIRST','First');
define('PAGING_NAV_NEXT','Next');
define('PAGING_NAV_PREV','Prev');
define('PAGING_NAV_LAST','Last');
define('URL_UNAUTHORIZED_PAGE','home/unauthorized_page');
define('DIV_ALERT_INFO_SUCCESS', "success");
define('DIV_ALERT_INFO_FAILED', "error");


define('TITLE_USER', "User Admin ID Card");
define('TITLE_PERSADMIN', "Pers Admin ID Card");

define('TITLE_USER_ADD', "Add User Admin");
define('TITLE_USER_EDIT', "Edit User Admin");

define('TITLE_PERS_ADMIN_ADD', "Add Pers Admin");
define('TITLE_PERS_ADMIN_EDIT', "Edit Pers Admin");

define('TITLE_NEW_ID', "New ID Request");
define('TITLE_EXTEND_ID', "Extend ID Request");
define('TITLE_LOST_ID', "Lost ID Request");
define('TITLE_BROKEN_ID', "Broken ID Request");


/* RETURN TYPE */
define('FN_USER_LOGIN_WRONG_PASSWORD', "Wrong Password");
define('FN_USER_LOGIN_INVALID_NIK', "Nik not found");

/* COMMON RETURN TYPE FUNCTION */
define('FN_SUCCESS', "success");
define('FN_FAILED', "failed");

define('OPT_ROLE_ADMIN_STATUS_VALUE', "admin");
define('OPT_ROLE_STAFF_STATUS_VALUE', "staff");
define('OPT_ROLE_ADMIN_STATUS_TEXT', "Admin");
define('OPT_ROLE_STAFF_STATUS_TEXT', "Staff");

define('OPT_SAP_VALUE', "1");
define('OPT_SAP_TEXT', "SAP");
define('OPT_NON_SAP_VALUE', "0");
define('OPT_NON_SAP_TEXT', "Non SAP");

define('OPT_CETAK_VALUE', "1");
define('OPT_CETAK_TEXT', "Sudah Cetak");
define('OPT_BLM_CETAK_VALUE', "0");
define('OPT_BLM_CETAK_TEXT', "Belum Cetak");

define('OPT_STATUS_NEW_VALUE', "1");
define('OPT_STATUS_NEW_TEXT', "New ID");
define('OPT_STATUS_EXT_VALUE', "2");
define('OPT_STATUS_EXT_TEXT', "Extend ID");
define('OPT_STATUS_LOST_VALUE', "3");
define('OPT_STATUS_LOST_TEXT', "Lost ID");
define('OPT_STATUS_BROKEN_VALUE', "4");
define('OPT_STATUS_BROKEN_TEXT', "Broken ID");



// define('PATH_FOTO_SISDM', "http://10.10.55.45/assets");// yang bener ini 
define('PATH_FOTO_SISDM', "http://10.10.55.45/assets/PHOTO");//PHOTO/
define('PATH_FOTO_SISDM_JS', "http://10.10.55.45/assets");
define('TITLE_SEARCH_USER', "SEARCH USER");
define('SEARCH_USER_PROCESS_NAME', "master_data/user_admin/search_name_process");

/* End of file constants.php */
/* Location: ./application/config/constants.php */
