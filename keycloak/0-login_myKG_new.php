<head>
  <?php 
  session_start();
  ?>
  <link href="../css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet">
    <script src="../js/jquery-1.7.2.min.js"></script>
    <script src="keycloak.js"></script>
    <script>
        var keycloak = Keycloak();
        var action = "<?php echo $action = (isset($_POST['action'])) ? $_POST['action'] : '' ; ?>";
        keycloak.init().success(function (authenticated) {
          var menu = "<?php echo $menu = (isset($_SESSION['menu_mykg'])) ? $_SESSION['menu_mykg'] : '' ; ?>";

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
                    data: "nik="+keycloak.userInfo.preferred_username,
                    cache: false,
                    success: function(msg){
                      document.location= "../"+menu+link_get;
                    }
                  });
                }
                else{
                  $.ajax({
                    url: "../include/setSession_myKg.php",
                    type: "POST",
                    data: "nik="+keycloak.userInfo.preferred_username,
                    cache: false,
                    success: function(msg){
                      console.log('test masuk2');
                      document.location= "../home.php";
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

        var displaySomeUserInfo = function (kc) {
          var message = "<h1>";
          message += 'Hello ' + kc.userInfo.name;
          message += "</h1>";
          message += "<p>";
          message += "registered email : " + kc.userInfo.email;
          message += "<br />";
          message += "registered user name : " + kc.userInfo.preferred_username;
          message += "</p>";
          return message;
        }

        var displayLoggedOff = function () {
        }

        function toTokenTest(){
          location.href= location.href + 'check-token.php?token='+document.getElementById('token').value;
        }
</script>
    
</head>

<body>
    <!-- <button id="login" onclick="javascript:keycloak.login()">
        Test LOGIN
    </button> -->
    <!-- <button onclick="javascript:toTokenTest();">
        Test CHECK TOKEN
    </button>
    <button type="hidden" onclick="javascript:keycloak.logout()">
        Test LOGOUT
    </button>
    <div>
        <div id='someDetails'></div>
    </div>
    <div id="tokenDetails" style="visibility: hidden;">
        <p>token (as obtained from js keycloak object):</p>
        <textarea id="token" style="width: 50%; height: 70%;"></textarea>
    </div> -->
</body>