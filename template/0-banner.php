  <!-- <div class="banner"> -->
  	<div class="doodle">
  		<?php 
  			$periode_date = date('d');
  			$periode_month = date('m');
  			// $sql_banner = "SELECT TOP 1 * FROM ms_system_event where start_month <= $periode_month and end_month >= $periode_month and start_date <= $periode_date and end_date >= $periode_date  and isActive =1 order by priority desc ";
        $sql_banner = "SELECT TOP 1 * FROM ms_system_event where start_month <= ? and end_month >= ? and start_date <= ? and end_date >= ?  and isActive = ? order by priority desc ";
        $row_banner = odbc_prepare($conn, $sql_banner);
        $exec = odbc_execute($row_banner, array($periode_month, $periode_month, $periode_date, $periode_date, 1));

  			// $row_banner = odbc_exec($conn, $sql_banner);

  			if(odbc_result($row_banner, 'filename')==''){
  				$logo_file = 'logo-default.png';
  			}else{
  				$logo_file = odbc_result($row_banner, 'filename');
  			}

  		?>
  		<img src="img/banner/<?php echo $logo_file ?>">
  	</div>
  	<!-- <div class="animation">
    <embed src="img/banner/header.swf" quality="high" wmode="transparent" bgcolor="#ffffff" width="100%" height="100%" name="hr portal b" align="middle" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer">
    </div> -->
  <!-- </div> -->
