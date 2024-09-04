<?php
	include_once __DIR__."/../language/credentials_constant.php";
		if(isset($NIK)=="")
		{
			//nothing
			$login = array (
			/*"ASHOST"=>"10.9.12.100",
			"SYSNR"=>"30",
			"CLIENT"=>"600",
			"USER"=>"HCM-PORTAL-1",
			"PASSWD"=>"hris2010",
			"MSGSRV"=>"",
			"R3NAME"=>"LHR",
			"CODEPAGE"=>"4110"*/

			"ASHOST"=>SAP_HOST_PROD,
			"SYSNR"=>SAP_SYSNR_PROD,
			"CLIENT"=>SAP_CLIENT_PROD,
			"USER"=>SAP_USER_PORTAL1,
			"PASSWD"=>SAP_PASSWD_PORTAL1,
			"MSGSRV"=>"",
			"R3NAME"=>SAP_R3NAME_PROD,
			"CODEPAGE"=>SAP_CODEPAGE_PROD
		);
			$rfc = saprfc_open ($login );
			if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>'; exit; }
			//echo 'a';
		}
		else
		{
			$conn = odbc_connect  ( "HRPORTAL-DEV" , "userPortal" ,  "Hrport@l2012"  );
		
			//get data module role id in tr login
			//$QryModulRoleID= "select * from tr_login where userLogin='$NIK' AND isActive='True'";
			$QryModulRoleID= "select * from tr_login where userLogin=? AND isActive=?";
			//$QryResultRole  = odbc_exec($conn, $QryModulRoleID);
			$QryResultRole = odbc_prepare($conn, $QryModulRoleID);
			odbc_execute($QryResultRole, array($NIK, True));

			//role id di tr login
			$roleIDNik = odbc_result($QryResultRole,"ModuleRoleID");
			
			if($roleIDNik!='')
			{
				// $QryTbModulRole= "select * from tb_ModuleRole WHERE ModuleRoleID=$roleIDNik";
				$QryTbModulRole= "select * from tb_ModuleRole WHERE ModuleRoleID=?";
				odbc_execute($QryTbResultRole=odbc_prepare($conn, $QryTbModulRole), array($roleIDNik));
				// $QryTbResultRole  = odbc_exec($conn, $QryTbModulRole);
				
				//get UserName dan Password
				$usernameRole = odbc_result($QryTbResultRole,"Username");
				$passwordRole = odbc_result($QryTbResultRole,"Password");		
				$login = array (
					/*"ASHOST"=>"10.9.12.100",
					"SYSNR"=>"30",
					"CLIENT"=>"600",
					"USER"=>$usernameRole,
					"PASSWD"=>$passwordRole,
					"MSGSRV"=>"",
					"R3NAME"=>"LHR",
					"CODEPAGE"=>"4110"*/

					"ASHOST"=>SAP_HOST_PROD,
					"SYSNR"=>SAP_SYSNR_PROD,
					"CLIENT"=>SAP_CLIENT_PROD,
					"USER"=>$usernameRole,
					"PASSWD"=>$passwordRole,
					"MSGSRV"=>"",
					"R3NAME"=>SAP_R3NAME_PROD,
					"CODEPAGE"=>SAP_CODEPAGE_PROD
				);
				$rfc = saprfc_open ($login );
				if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>';exit; }
			}
			else
			{
				$login = array (
				/*"ASHOST"=>"10.9.12.100",
				"SYSNR"=>"30",
				"CLIENT"=>"600",
				"USER"=>"HCM-PORTAL-1",
                "PASSWD"=>"hris2010",
				"MSGSRV"=>"",
				"R3NAME"=>"LHR",
				"CODEPAGE"=>"4110"*/

				"ASHOST"=>SAP_HOST_PROD,
				"SYSNR"=>SAP_SYSNR_PROD,
				"CLIENT"=>SAP_CLIENT_PROD,
				"USER"=>SAP_USER_PORTAL1,
				"PASSWD"=>SAP_PASSWD_PORTAL1,
				"MSGSRV"=>"",
				"R3NAME"=>SAP_R3NAME_PROD,
				"CODEPAGE"=>SAP_CODEPAGE_PROD
			);
				$rfc = saprfc_open ($login );
				if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>'; exit; }
			}
		}

		//sementara masuk ke HCM-PORTAL-1 smua
		/*	$login = array (
			"ASHOST"=>"10.9.12.100",
			"SYSNR"=>"30",
			"CLIENT"=>"600",
			"USER"=>"HCM-PORTAL-1",
			"PASSWD"=>"hris2010",
			"MSGSRV"=>"",
			"R3NAME"=>"LHR",
			"CODEPAGE"=>"4110");
			$rfc = saprfc_open ($login );
			if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>'; exit; }*/
		

?>

