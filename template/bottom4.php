
  <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/jquery-ui-1.8.16.custom.min.js"></script>
    <script src="js/bootstrap-tab.js"></script>

    
    <script src="js/jquery.validate.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.monthYear.js"></script>
    <script src="js/dataTables.fnFilterClear.js"></script>
    <script type="text/javascript" src="js/bootstrap-popover.js" ></script>
    <script type="text/javascript" src="js/bootstrap-tooltip.js" ></script>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37161530-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
<?php

odbc_close($conn);
odbc_close($connSISDM);
unset($_SESSION['auth_mykg']);
include "include/sapdisconnect.php" ;
// include "survey_dobloo_script.php";
include "popup_banner_djp_script.php";
include "heregistrasi_script.php";
?>
