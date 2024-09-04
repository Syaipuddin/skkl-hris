<?php include "keycloakInit.html"; ?>
<script>
	window.onload = function() {
		javascript:keycloak.logout();
	}
	var displayLoggedOff = function () {
        document.getElementById('someDetails').innerHTML = '<h2>logged off</h2>';
    }
</script>
<div id='someDetails'></div>