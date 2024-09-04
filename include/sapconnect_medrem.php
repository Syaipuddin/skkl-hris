<?php
		if(isset($NIK)=="")
		{
			//nothing
			$login = array (
			"ASHOST"=>"10.9.12.100",
			"SYSNR"=>"30",
			"CLIENT"=>"600",
			"USER"=>"HCM-GAM-CIK",
			"PASSWD"=>"",
			"MSGSRV"=>"",
			"R3NAME"=>"LHR",
			"CODEPAGE"=>"4110");
			$rfc = saprfc_open ($login );
			if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>'; exit; }
			//echo 'a';
		}
		else
		{
			$conn = odbc_connect  ( "HRPORTAL-DEV" , "userPortal" ,  "Hrport@l2012"  );
		
			//get data module role id in tr login
			$QryModulRoleID= "select * from tr_login where userLogin='$NIK' AND isActive='True'";
			$QryResultRole  = odbc_exec($conn, $QryModulRoleID);
			//role id di tr login
			$roleIDNik = odbc_result($QryResultRole,"ModuleRoleID");
			
			if($roleIDNik!='')
			{
				$QryTbModulRole= "select * from tb_ModuleRole WHERE ModuleRoleID=$roleIDNik";
				$QryTbResultRole  = odbc_exec($conn, $QryTbModulRole);
				
				//get UserName dan Password
				$usernameRole = odbc_result($QryTbResultRole,"Username");
				$passwordRole = odbc_result($QryTbResultRole,"Password");		
				$login = array (
					"ASHOST"=>"10.9.12.100",
					"SYSNR"=>"30",
					"CLIENT"=>"600",
					"USER"=>$usernameRole,
					"PASSWD"=>$passwordRole,
//                                        "PASSWD"=>"initial",
					"MSGSRV"=>"",
					"R3NAME"=>"LHR",
					"CODEPAGE"=>"4110");
				$rfc = saprfc_open ($login );
				if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>';exit; }
			}
			else
			{
				$login = array (
				"ASHOST"=>"10.9.12.100",
				"SYSNR"=>"30",
				"CLIENT"=>"600",
				"USER"=>"HCM-GAM-CIK",
                "PASSWD"=>"",
				"MSGSRV"=>"",
				"R3NAME"=>"LHR",
				"CODEPAGE"=>"4110");
				$rfc = saprfc_open ($login );
				if (! $rfc ) { echo '<img style="margin-right:auto;margin-left:auto;margin-top:80px" src="img/announce/maintenance sap2.jpg"></img>'; exit; }
			}
		}
		

?>