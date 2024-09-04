<?php
session_start();
include "keycloakInit.html";
$menu = $_SESSION['menu_mykg'];
?>
<script>
	window.onload = function() {
    // console.log('test');
   console.log(keycloak);
   var auth;
   if (!keycloak.userInfo) {
    auth = 0;
   }
   else
   {
    auth = 1;
   }
		var menu = "<?php echo $menu; ?>";
		$.ajax({
              url: "../include/setSession_myKg.php",
              type: "POST",
              data: "action=auth"+"&auth="+auth,
              cache: false,
              success: function(msg){
                // document.location.href = "../"+menu;
              }
        });
	};
</script>