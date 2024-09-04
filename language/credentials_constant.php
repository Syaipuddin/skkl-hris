<?php
	// HOST DB SQL SERVER
	define("DB_HOST_PORTALPROD", 'PORTAL');
	define("DB_HOST_IDCARD", 'IDCARD');
	define("DB_HOST_SMS", 'SMSLIVE');
	define("DB_HOST_REMKES", 'REMKES');
	define("DB_HOST_SURVEY", 'SURVEY');
	define("DB_HOST_PORTAL", 'HRPORTAL-DEV');
	define("DB_HOST_REPO57", 'DBREPO57');

	// User DB SQL SERVER
	define("DB_USER_PORTALPROD", 'hris00');
	define("DB_USER_IDCARD", 'presensi');
	define("DB_USER_SMS", 'hris00');
	define("DB_USER_REMKES", 'Remkes');
	define("DB_USER_SURVEY", 'hris00');
	define("DB_USER_PORTAL", 'userPortal');
	define("DB_USER_REPO57", 'sa');

	// Passwd DB SQL SERVER
	define("DB_PASSWD_PORTALPROD", 'Hrport@l2012');
	define("DB_PASSWD_IDCARD", 'abc12345678');
	define("DB_PASSWD_SMS", 'Hrport@l2012');
	define("DB_PASSWD_REMKES", 'sekmer');
	define("DB_PASSWD_SURVEY", 'Hrport@l2012');
	define("DB_PASSWD_PORTAL", 'Hrport@l2012');
	define("DB_PASSWD_REPO57", 'Hrport@l2012');

	// SAP config
	define("SAP_HOST_PROD", '10.9.12.100');
	define("SAP_SYSNR_PROD", '30');
	define("SAP_CLIENT_PROD", '600');
	define("SAP_R3NAME_PROD", 'LHR');
	define("SAP_CODEPAGE_PROD", '4110');

	// Credentials SAP HCM-PORTAL-1
	define("SAP_USER_PORTAL1", 'HCM-PORTAL-1');
	define("SAP_PASSWD_PORTAL1", 'hris2010');

	// Credentials SAP HCM-PORTAL-2
	define("SAP_USER_PORTAL2", 'HCM-PORTAL-2');
	define("SAP_PASSWD_PORTAL2", 'sAp@2020@HRis');

	// Credentials SAP HCM-PORTAL-3
	define("SAP_USER_PORTAL3", 'HCM-PORTAL-3');
	define("SAP_PASSWD_PORTAL3", 'sAp@2022@HRis');

	// Credentials SAP HCM-PORTAL-4
	define("SAP_USER_PORTAL4", 'HCM-PORTAL-4');
	define("SAP_PASSWD_PORTAL4", 'sappayroll2015');

	// Email HR-PORTAL office365
	define("EMAIL_HOST_PORTAL365", 'smtp.office365.com');
	define("EMAIL_PORT_PORTAL365", 587);
	define("EMAIL_USER_PORTAL365", 'hrportal@hr.kompasgramedia.com');
	define("EMAIL_PASSWORD_PORTAL365", 'S1rh0K3@@)@!');
	define("EMAIL_FROM_PORTAL365", "hrportal@hr.kompasgramedia.com");
	define("EMAIL_FROM2_PORTAL365", '[HRPortal] DO NOT REPLY THIS EMAIL!');
	define("EMAIL_SECURITY_PORTAL365", true);

	// Email HR-PORTAL mdaemon(10.10.55.10)
	define("EMAIL_HOST_PORTAL_LOCAL", '10.10.55.10');
	define("EMAIL_PORT_PORTAL_LOCAL", 110);
	define("EMAIL_USER_PORTAL_LOCAL", 'hrportal@chr.kompasgramedia.com');
	define("EMAIL_PASSWORD_PORTAL_LOCAL", 'abc123');
	define("EMAIL_FROM_PORTAL_LOCAL", "hrportal@chr.kompasgramedia.com");
	define("EMAIL_FROM2_PORTAL_LOCAL", '[HRPortal] DO NOT REPLY THIS EMAIL!');
	define("EMAIL_SECURITY_PORTAL_LOCAL", false);

	// Email HR-PORTAL mykg
	// define("EMAIL_HOST_PORTAL_MYKG", '10.9.60.237');
	define("EMAIL_HOST_PORTAL_MYKG", '10.9.60.195');
	// define("EMAIL_HOST_PORTAL_MYKG", 'mail.mykg.id');
	define("EMAIL_PORT_PORTAL_MYKG", 587);
	// define("EMAIL_PORT_PORTAL_MYKG", 465);
	define("EMAIL_USER_PORTAL_MYKG", 'noreply-hrportal@mykg.id');
	define("EMAIL_PASSWORD_PORTAL_MYKG", 'HRportal2022');
	// define("EMAIL_USER_PORTAL_MYKG", 'noreply-hrportal2@mykg.id');
	// define("EMAIL_PASSWORD_PORTAL_MYKG", 'HRPortal_2024');
	define("EMAIL_FROM_PORTAL_MYKG", "noreply-hrportal@mykg.id");
	define("EMAIL_FROM2_PORTAL_MYKG", '[HRPortal] DO NOT REPLY THIS EMAIL!');
	define("EMAIL_SECURITY_PORTAL_MYKG", true);
	// define("EMAIL_SECURITY_PORTAL_MYKG", false);

	// Email utama HR-PORTAL:
	define("EMAIL_HOST_1", EMAIL_HOST_PORTAL_MYKG);
	define("EMAIL_PORT_1", EMAIL_PORT_PORTAL_MYKG);
	define("EMAIL_USER_1", EMAIL_USER_PORTAL_MYKG);
	define("EMAIL_PASSWORD_1", EMAIL_PASSWORD_PORTAL_MYKG);
	define("EMAIL_FROM_1", EMAIL_FROM_PORTAL_MYKG);
	define("EMAIL_FROM2_1", EMAIL_FROM2_PORTAL_MYKG);
	define("EMAIL_SECURITY_1", EMAIL_SECURITY_PORTAL_MYKG);

	// Email kedua HR-PORTAL:
	define("EMAIL_HOST_2", EMAIL_HOST_PORTAL365);
	define("EMAIL_PORT_2", EMAIL_PORT_PORTAL365);
	define("EMAIL_USER_2", EMAIL_USER_PORTAL365);
	define("EMAIL_PASSWORD_2", EMAIL_PASSWORD_PORTAL365);
	define("EMAIL_FROM_2", EMAIL_FROM_PORTAL365);
	define("EMAIL_FROM2_2", EMAIL_FROM2_PORTAL365);
	define("EMAIL_SECURITY_2", EMAIL_SECURITY_PORTAL365);


	// Credentials Odoo untuk API HR-PORTAL PROD
	define("ODOO_USER_PORTAL_PROD", 'api@hr.kompasgramedia.com');
	define("ODOO_PASSWD_PORTAL_PROD", 'api2022');
?>
