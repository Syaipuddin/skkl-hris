<?php
	function connect_ldap($host, $port){
		$err=0;
		if (!$ldap_con = ldap_connect($host, $port)) {
			$err++; $return['error'] = "That LDAP-URI was not parseable";
		}
		if (!ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3)) {
			$err++;
			$return['error'] = "Could not set LDAPv3";
		}
		if (!ldap_set_option($ldap_con, LDAP_OPT_REFERRALS, 0)) {
			$err++;
			$return['error'] = "Could not set LDAP Referrals";
		}
		ldap_set_option($ldap_con, LDAP_OPT_NETWORK_TIMEOUT, 2);
		if ($err > 0) {
			$return['status'] = 0;
			return $return;
		}
		else{
			$return['con'] = $ldap_con;
			$return['status'] = 1;
			return $return;
		}
	}

	function getData($con, $user, $domain, $passAdmin, $base_dn, $filter){
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {
			$result = ldap_search($con, $base_dn, $filter);
			$entries = ldap_get_entries($con, $result);
			$return['data'] = $entries;

			if ($return['data']['count']==0) {
				$return['status'] = 0;
				$return['error'] = "No Data";
				return $return;
			}
			else{
				$return['status'] = 1;
				return $return;
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
			return $return;
		}
	}

	function changeCN($con, $user, $domain, $passAdmin, $base_dn, $new_dn, $newParent){
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {
			// var_dump($base_dn);
			if(ldap_rename($con, $base_dn, $new_dn, $newParent, true)){
				$return['status'] = 1;
				return $return;
			}
			else{
				$return['status'] = 0;
				$return['error'] = "No Data";
				// var_dump(ldap_error($con));
				return $return;
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
			return $return;
		}
	}

	function changePass($con, $user, $domain, $passAdmin, $newPassUser, $dn_changePass)
	{
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {
			$newPassUser = "\"" . $newPassUser . "\"";
			$len = strlen($newPassUser);
			$newPass = '';
			for ($i = 0; $i < $len; $i++) $newPass .= "{$newPassUser{$i}}\000";
			$newEntry["unicodePwd"] = $newPass;
			if(ldap_mod_replace($con, "$dn_changePass", $newEntry))
			{
				$return['status'] = 1;
			}
			else{
				$return['status'] = 0;
				$return['error'] = ldap_error($con);
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
		}
		return $return;
	}

	function insertData($con, $user, $domain, $passAdmin, $dataAdd=array(), $dn){
		/*$dataAdd['cn'] = "999999 TEST";
		$dataAdd['employeeid'] = "999999";
		$dataAdd['givenname'] = "Bpk. TEST AJA";
		$dataAdd['sn'] = "AJA";
		$dataAdd['displayname'] = "TEST AJA";
		$dataAdd['company'] = "Gramedia";
		$dataAdd['department'] = "Human Resources Information System Dept.";
		$dataAdd['title'] = "Software Engineer";
		$dataAdd['mobile'] = "+621234567890";
		$dataAdd['mail'] = "TEST@GMAIL.COM";
		$dataAdd['samaccountname'] = "99999";*/
		/*var_dump($con);
		echo "<br>";
		var_dump($user);
		echo "<br>";
		var_dump($domain);
		echo "<br>";
		var_dump($passAdmin);
		echo "<br>";
		var_dump($dataAdd);
		echo "<br>";
		var_dump($dn);
		echo "<br>";*/
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {

			$dataAdd['objectclass']="user";
			$dataAdd['userAccountControl']=544;
			/*var_dump($dataAdd);
			echo "<br>";
			var_dump($con);
			echo "<br>";
			var_dump($dn);*/

			if (!ldap_add($con, $dn, $dataAdd)) {
				$return['status']=0;
				$return['error']="Add User Failed";
				$return['errorCode']=ldap_errno($con);
			}
			else{
				$return['status']=1;
				$return['error'] = ldap_error($con);
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
		}
		

		return $return;
	}

	function updateUser($con, $user, $domain, $passAdmin, $dataUpdate=array(), $dn){
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {
			if(ldap_mod_replace($con, "$dn", $dataUpdate))
			{
				$return['status'] = 1;
			}
			else{
				$return['status'] = 0;
				$return['error'] = ldap_error($con);
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
		}
		return $return;
	}

	function deleteUser($con, $user, $domain, $passAdmin, $dn)
	{
		if (ldap_bind($con, "$user@$domain", "$passAdmin")) {
			if (ldap_delete($con, $dn)) {
				$return['status'] = 1;
			}
			else{
				$return['status'] = 0;
				$return['error'] = ldap_error($con);
			}
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
		}
	}

	function checkUser($con,$dn,$passUser){
		/*var_dump($dn);
		echo "<br>";
		var_dump($passUser);*/
		if (ldap_bind($con, "$dn", "$passUser")) {
			$return['status'] = 1;
		}
		else{
			$return['status'] = 0;
			$return['error'] = ldap_error($con);
		}
		return $return;
	}


?>