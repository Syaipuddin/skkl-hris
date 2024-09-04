<head>
  <?php 
  header('Cache-Control: no-cache');
  header('Pragma: no-cache');
  session_start();
  ?>
  <link href="../css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet">
    <script src="../js/jquery-1.7.2.min.js"></script>
    <script src="keycloak.js?v=<?php echo urlencode(date('Ymd-His')); ?>"></script>
    <script>
      $(document).ready(function(){
        $(".loading").show();
      });
        var keycloak = Keycloak();
        var action = "<?php echo $action = (isset($_POST['action'])) ? $_POST['action'] : '' ; ?>";
        // auto login sso tiap buka halaman ini
        keycloak.init().success(function (authenticated) {
          var menu = "<?php echo $menu = (isset($_SESSION['menu_mykg'])) ? $_SESSION['menu_mykg'] : '' ; ?>";
          console.log(menu);
          if (action == 'logout')
          {
            javascript:keycloak.logout();
          }
          else{
            if (authenticated) {
              keycloak.loadUserInfo().success(function (result) {
                if (menu != '') {
                  $.ajax({
                    url: "../include/setSession_myKg.php",
                    type: "POST",
                    data: "nik="+keycloak.userInfo.preferred_username+"&token="+keycloak.token,
                    cache: false,
                    success: function(msg){
                      // msg isinya status kontrak karyawan
                      var link_get = '';
                      var i=0;
                      msg = JSON.parse(msg);

                      /*if (msg['status_kary'] == 'TRAINEE') {
                        alert('Sorry, you don\'t have permission to access HR-PORTAL!\nPlease contact HR Admin for detail information.');
                        document.location= "../0-logout.php";
                      }
                      else if (msg['payarea'] == "KM"){
                        // alert('Sorry, you don\'t have permission to access HR-PORTAL!\nYou will be redirected to MyKG Workplace!');
                        // TODO : ini perlu disesuaikan dgn link odoo di masing2 server
                        document.location= "https://sso.mykg.id/auth/realms/master/protocol/openid-connect/auth?response_type=token&client_id=odoo-hr&redirect_uri=https%3A%2F%2Fhr.mykg.id%2Fauth_oauth%2Fsignin&scope=OAuth2&state=%7B%22d%22%3A+%22odoopayroll%22%2C+%22p%22%3A+4%2C+%22r%22%3A+%22https%253A%252F%252Fhr.mykg.id%252Fweb%22%7D";
                      }
                      else{*/
                        document.location= "../"+menu;
                      // }
                    }
                  });
                }
                else{
                  $.ajax({
                    url: "../include/setSession_myKg.php",
                    type: "POST",
                    data: "nik="+keycloak.userInfo.preferred_username+"&token="+keycloak.token,
                    cache: false,
                    success: function(msg){
                      msg = JSON.parse(msg);

                      /*if (msg['status_kary'] == 'TRAINEE') {
                        alert('Sorry, you don\'t have permission to access HR-PORTAL!\nPlease contact HR Admin for detail information.');
                        document.location= "../0-logout.php";
                      }
                      else if (msg['payarea'] == "KM"){
                        // alert('Sorry, you don\'t have permission to access HR-PORTAL!\nYou will be redirected to MyKG Workplace!');
                        // TODO : ini perlu disesuaikan dgn link odoo di masing2 server
                        document.location= "https://sso.mykg.id/auth/realms/master/protocol/openid-connect/auth?response_type=token&client_id=odoo-hr&redirect_uri=https%3A%2F%2Fhr.mykg.id%2Fauth_oauth%2Fsignin&scope=OAuth2&state=%7B%22d%22%3A+%22odoopayroll%22%2C+%22p%22%3A+4%2C+%22r%22%3A+%22https%253A%252F%252Fhr.mykg.id%252Fweb%22%7D";
                      }
                      else{*/
                        document.location= "../home.php";
                      // }
                      
                    }
                  });
                }
              });
            } else {
              javascript:keycloak.login();
              console.log('test3');
              displayLoggedOff();
            }
          }
        }).error(function () {
          alert('failed to initialize');
        });
</script>
    
</head>

<body>
  <div class="container loading" style="text-align: center;"><img src="../img/mykgLOGO.png" style="width: 20%" /><br><img src="../img/loading_1.gif" /><h1 style="color: darkblue;">LOADING...</h1></div>

</body>