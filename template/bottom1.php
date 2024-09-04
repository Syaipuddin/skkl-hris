    </div>
    <hr>
      <footer>
        <p>&copy; Corporate Human Resources - Kompas Gramedia 2012 
			<br /> Best view Firefox 4+, Chrome, Internet Explorer 8+, Safari 5+
        </p>
      </footer>
	</div>
    </div> <!-- /container --> 
  <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script src="js/jquery-1.7.2.min.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/jquery-ui-1.8.16.custom.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/slides.min.jquery.js"></script>
  	<script src="js/thickbox.js"></script>
    <!--<script type="text/javascript" src="js/bootstrap-popover.js" ></script>-->
    <script type="text/javascript" src="js/bootstrap-tooltip.js" ></script>
<!--	<script type="text/javascript" src="js/jquery.snow.min.1.0.js" ></script> -->
   <script type="text/javascript">$('.row div[class^="span"]:last-child').addClass('last-child');</script>
<script type="text/javascript">
/*$(document).ready( function(){
    $.fn.snow();
});*/
 // Function
 function filterTable(value) {
     if (value != "") {
         $("#table td:contains-ci('" + value + "')").parent("tr").show();
     }
 }

 // jQuery expression for case-insensitive filter
 $.extend($.expr[":"], {
     "contains-ci": function (elem, i, match, array) {
         return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
     }
 });

 // Event listener
 $('#filter').on('keyup', function () {
     if ($(this).val() == '') {
         $("#table tbody > tr").show();
     } else {
         $("#table > tbody > tr").hide();
         var filters = $(this).val().split(' ');
         filters.map(filterTable);
     }
 });
</script>
</body>
</html>
<?php
 if ($browser['name']=='msie' and $browser['version'] =='6.0'){
    
  }
  unset($_SESSION['auth_mykg']);
?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-37161530-1', 'auto');
  ga('send', 'pageview');

</script>
