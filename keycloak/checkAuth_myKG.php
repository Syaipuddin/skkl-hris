<?php
	require_once 'includes/backend-call.php';

	$token = $_GET['token'];

	$config = json_decode(file_get_contents('keycloak.json'), TRUE);
    $usrpwd = json_decode(file_get_contents('backendconfig.json'), TRUE);

    $tokenCheckResult = CallAPI($config['auth-server-url'] . "/realms/".$usrpwd['realm']."/protocol/openid-connect/token/introspect", $token, $usrpwd['backend-usrpwd']);
    $active = json_decode($tokenCheckResult);
    $_SESSION['active'] = $active->active;
    echo "$tokenCheckResult";
    // echo json_decode($tokenCheckResult);
?>