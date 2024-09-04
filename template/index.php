<?php
		//Buffer larger content areas like the main page content
		include "include/connection.php";
		ini_set("odbc.defaultlrl", "9999999");
		ob_start();

		if(isset($_GET['id']))
		{
				$id = $_GET['id'];
		}
		else
		{
				$id = "" ;
		}

?>

<div id="logo"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="871" height="82" id="hrportal" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="Images/hrportal.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="bgcolor" value="#ffffff" /><embed src="Images/hrportal.swf" quality="high" wmode="transparent" bgcolor="#ffffff" width="871" height="82" name="hr portal b" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object></div>
<div id="content_div1">

				<?php
				if($id == "")
				{
					$News=odbc_exec($conn,"select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' order by priority desc, newsID desc");

		while(odbc_fetch_row($News))
		{
				$id=odbc_result($News,1);
						$Title=odbc_result($News,3);
						$description=stripslashes(base64_decode(substr(odbc_result($News,4),0,620)));
						$NewsImageName=odbc_result($News,8);
						$NewsEvent=odbc_result($News,12);
						$PD=date(' D, d F Y' ,strtotime(odbc_result($News,'PostDate')));
		?>
<div class="main_center">
	<div class="main_top">
		<div class="main_bottom">
			<div class="main_content">
														<div align="left" style="color: #A9A9A9;">
																Post Date :<?php echo $PD;?>
														</div>
														<div align="center" style="font-size: 12px;">
														<h1>
							<?php 
							
								if ($id == "201106101825")
								{
							?>
														<a href="login.php">
																<?php
																		echo $Title;
																?>
														</a>
														<?php
								}else
								{
							?>
														<a href="Index.php?id=<?php echo $id;?>">
																<?php
																		echo $Title;
																?>
																</a>
																<?php
								}
								?>
														</h1>
														<div align="center">
														<br />
																		<?php
																				if($NewsImageName!=NULL)
																				{
											if ($id == "201106101825")
											{
																				 ?>
																				<a href="login.php"> <img src='ViewImages.php?id=<?php echo $id;?>'/>	</a>
																				 <?php
											}else
											{
										?>
																						 <img style="width:600px" src='ViewImages.php?id=<?php echo $id;?>'/>	
																			 <?php 
											}
										 }
																		?>
														</div>
														</div>      <br />
														<div align="center" style="font-size: 12px;">
														<div style="background-image:url(Images/bg_date.jpg); width:300px; font-weight: bold; background-position: center;">
														<div align="center">
																<?php
																					echo $NewsEvent;
																?>
														</div>
														</div>
														</div>
														<div style="font-size: 12px;">
														<br />
														<?php  // $lenght=strlen(odbc_result($News,4));
																		echo $description;
																		$lenght=strlen($description);

																		if($lenght>300)
																		{
																			echo "...";
																		}
																?>
											</div>
														<div align="right" style="font-size: 11px;">
							<?php 
							
								if ($id == "201106101825")
								{
							?>
							<a href="login.php">
																Read More...
														</a>
														<?php
								}else
								{
							?>
							<a href="Index.php?id=<?php echo $id;?>">
																Read More...
														</a>
																<?php
								}
								?>
							</div>
						</div>
		</div>
	</div>
</div>
				<?php }}else{
				// $News=odbc_exec($conn,"select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news where isActive='1' and isFeatured='1' and newsID='$id' order by newsID desc");
				odbc_execute($News = odbc_prepare($conn,"select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news where isActive='1' and isFeatured='1' and newsID=? order by newsID desc"), array($id));

		while(odbc_fetch_row($News))
		{

						$id=odbc_result($News,1);
						$Title=odbc_result($News,3);
						$description=stripslashes(base64_decode(odbc_result($News,4)));
						$NewsImageName=odbc_result($News,8);
						$Event=odbc_result($News,12);
						$PD=odbc_result($News,13);
		?>
<div class="main_center">
	<div class="main_top">
		<div class="main_bottom">
			<div class="main_content">
														<div align="left" style="color: #A9A9A9;">
																Post Date :<?php echo $PD;?>
														</div>
														<div align="center" style="font-size: 12px;">
														<h1>
																<?php
																		echo $Title;
																?>
														</h1>
														<div align="center">
														<br />
																		<?php
																				if($NewsImageName!=NULL)
																				{
																		?>
																				<img src="ViewImages.php?id=<?php echo $id;?>"/>
																		<?php
																				}
																		?>
														</div>
														</div>
														<br />
														<div align="center">
														<div style="background-image:url(Images/bg_date.jpg); width:300px; font-weight: bold; background-position: center;">
														<div align="center" style="font-size: 12px;">
																<?php
																					echo $Event;
																?>
														</div>
														</div>
														</div>
														<div style="font-size: 12px;">
														<br />
														<?php
																	echo $description;
																?>

											</div>
														<div align="right">
														<a href="Index.php">
																<< Back
														</a>
														</div>
						</div>
		</div>
	</div>
</div>
				<?php }}?>


</div>
<div id="content_div2" style="font-size: 11px;">
		<div class="side_center">
			<div class="side_top"><h1 class="top_h1">Search</h1>
				<div class="side_bottom">
					<div class="side_content">
			<form  id="searchform" action="" method="GET">
			<!--<div class="search1 bluetext"><b>Search:</b></div>
			<div class="search2">-->
			<input type="text" name="search" class="input-text" style="width:190px;"/>
			<input type="submit" value="" class="searchbutton"/></div>
			</form>
								<!--</div>-->
						</div>
				</div>
		</div>
<div class="side_center">
	<div class="side_top"><h1 class="top_h1">Employee Login</h1>
		<div class="side_bottom">
			<div class="side_content">
				<!--<h1>Employee Login</h1><br>-->
				<form id="login_form2" action="login.php" method="POST">
				<div class="login_side1">NIK</div>
				<div class="login_side2"><input type="text" name="NIK" maxlength="6"/></div>
				<div class="login_side1">Password</div>
				<div class="login_side2"><input type="password" name="PW" maxlength="16"/></div>
				<div class="login_side1">&nbsp;</div>
				<div class="login_side2"><input type="submit" value="Submit" name="submit"/>&nbsp;<input type="reset" value="Reset"/></div>
				<div class="login_msg"><a href="newaccount.php">New account</a>&nbsp;|&nbsp;<a href="newaccount.php?act=forgot">Forgot password?</a></span></div>
				</form>
			</div>
		</div>
	</div>
</div>

 <div class="side_center">
			<div class="side_top"><h1 class="top_h1">Visitor Counter</h1>
				<div class="side_bottom">
					<div class="side_content">
								<?php include("counter.php");?>
								</div>
						</div>
				</div>
		</div>
	 

<div class="side_center">
	<div class="side_top"><h1 class="top_h1">Gallery</h1>
		<div class="side_bottom">
			<div class="side_content">
				<table align="center" class="background" cellspacing="2" cellpadding="0">
					<tr>
						<td valign="top" align="center">
							<div class="project_slide">
								<ul style="margin: 0pt; padding: 0pt; position: relative; list-style-type: none; z-index: 1;">
										<?php for($i=0;$i<=27;$i++)
										{?>
												<li style="overflow: hidden; float: left; width: 202px; height: 142px;"><img src="Gallery/<?php if($i==0){echo $a=rand(1,27);}else{ if($i==$a){echo "KG";}else{echo $i;}}?>.jpg" width="200" height="140" border="1" style="border-color:white;"></li>
										<?php } ?>
								</ul>
								</div>
				</td>
				</tr>
				<tr>
						<td align="center">
								<a href="#" class="prev"><img src="images/background-gallery.jpg" width="30%" height="18" class="button"/><img src="images/left.jpg" width="20%" height="18" class="button"/></a><a href="#" class="next"><img src="images/right.jpg" width="20%" height="18" class="button"/><img src="images/background-gallery.jpg" width="30%" height="18" class="button"/></a>
						</td>
				</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="side_center">
	<div class="side_top"><h1 class="top_h1">Our Products</h1>
		<div class="side_bottom">
			<div class="side_content">
				<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"  height="180" width="215" id="ourproduct" align="middle">
<param name="allowScriptAccess" value="sameDomain" />
<param name="movie" value="Images/ourproduct.swf" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="bgcolor" value="#ffffff" /><embed src="Images/ourproduct.swf" quality="high" wmode="transparent" bgcolor="#ffffff"  height="180" width="215" name="ourproduct" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
</object>			</div>
		</div>
	</div>
</div>
<div class="side_center">
	<div class="side_top"><h1 class="top_h1">Careers</h1>
		<div class="side_bottom">
			<div class="side_content">
				<!--<a href="http://dimension.jobsdb.co.id/career/default.asp?PID=1&AC=GRAMEDIA&EC=GRAMED&GC=&LID=Null&SP=1">-->
				<a href="http://www.kompaskarier.com/chr">
				<img src="Images/carrers.png" border=0/></a>
			</div>
		</div>
	</div>
</div>
</div>

<script type="text/javascript">
$(".project_slide").jCarouselLite({
		visible: 1,
		btnNext: ".next",
		btnPrev: ".prev",
		speed: 700,
		mouseWheel: false
 });
</script>

<?php
	//Assign all Page Specific variables
	$pagemaincontent = ob_get_contents();
	$pagetitle = "Kompas Gramedia - HR Portal Home Page";
	//Apply the template
	 ob_end_clean();
		 odbc_close($conn);

	include("master.php");
?>
<script type="text/javascript" src="JS/jquery-1.3.2.min.js"></script>
<script src="JS/f_clone_Notify.js" type="text/javascript"></script>

<?php
		include "include/class.browser.php";
		$browser = Browser::detect(); 
		
		if (($browser['name']=='msie' and ($browser['version'] =='6.0' or $browser['version'] =='7.0')) or ($browser['name']=='mozilla' and ($browser['version'] =='2.0' or $browser['version'] =='3.0' or $browser['version'] =='3.5'or $browser['version'] =='unknown')) ){
			echo '<link href="CSS/f_clone_Notify2.css" rel="stylesheet" />'; 
			echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>HR Portal Warning :</font></strong><br><img src=img/icon/warning.png style=float:left;width:32px;margin:3px;><a href=browser-problem.php>Your Browser is not longer supported by HR Portal. Please Contact Your IT Admin/Support for update your Browser</a>');sNotify.alterNotifications('chat_msg');</script>";
		}else{
			echo '<link href="CSS/f_clone_Notify3.css" rel="stylesheet" />';
			if($url == "https://hr.kompasgramedia.com"){
				echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>HR Portal Notification :</font></strong><br><img src=img/icon/warning.png style=float:left;width:32px;margin:3px;><a href=http://202.146.0.38>Check HR Portal with New Design.</a>');sNotify.alterNotifications('chat_msg');</script>";
			}else{
				echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>HR Portal Notification :</font></strong><br><img src=img/icon/warning.png style=float:left;width:32px;margin:3px;><a href=http://10.10.55.40>Check HR Portal with New Design.</a>');sNotify.alterNotifications('chat_msg');</script>";
			}
		}
	 
?>