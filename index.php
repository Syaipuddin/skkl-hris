<?php
        ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//	session_start(); 
	$pageTitle="Home";
	include "template/top_index.php"; //Load template pembuka dan load css eksternal
	include "include/date_lib.php"; //Date Library
?> 
		<div align="center" id="navinfo">
		    <div id="navcontent">
		        <div class="info">
		            <label>Mengalami kesulitan dalam pendaftaran HR Portal? Silahkan klik <a href="faq.php" class="btn btn-info" title="klik di sini">FAQ</a></label>
		        </div>
		        
		    </div>
		</div>
			<div class="row">
				<div class="span7">
					<?php 
						include "template/slideshow.php"; 
					?>
				</div>
				<div class="span4 offset8">
					<ul id="tab" class="nav nav-tabs">
						<?php
							if ((isset($_SESSION['signup_tag']) and $_SESSION['signup_tag']==0)or isset($_SESSION['signup_tag'])==false){
								echo '<li class="active"><a href="#login" data-toggle="tab">Login</a></li>';
								echo '<li><a href="#signup" data-toggle="tab">Sign Up</a></li>';
							} else {
								echo '<li><a href="#login" data-toggle="tab">Login</a></li>';
								echo '<li class="active"><a href="#signup" data-toggle="tab">Sign Up</a></li>';
							}
						?>
					</ul>

					<div id="myTabContent" class="tab-content">
					<?php
						if (isset($_SESSION['signup_tag'])){
							echo '<div class="tab-pane fade" id="login">';
						}
						else if (isset($_SESSION['login_tag'])==2){
							echo '<div class="tab-pane fade in active" id="login">';
							echo '<div class="alert alert-error">';
							echo $_SESSION['login_msg'];
							echo '</div>';
						}
						else if (isset($_SESSION['login_tag'])==3 || isset($_SESSION['login_tag'])==4){
							echo '<div class="tab-pane fade in active" id="login">';
							echo '<div class="alert alert-info">';
							echo $_SESSION['login_msg'];
							echo '</div>';
						}
						else{
							echo '<div class="tab-pane fade in active" id="login">';
						}
					?>
						
							 <form class="indexBox well" id="loginForm" action="0-login.php" method="POST" style="padding-left:4px;padding-right:4px;">
								<div class="row">
								<div class="span1">NIK</div>
								<div class="span2">
								<input class="span2" type="text" id="NIK" name="NIK" maxlength=6 placeholder="NIK"></div>
								</div>
								<div class="row">
								<div class="span1">Password</div>
								<div class="span2">
								<input class="span2" type="password" id="PW" name="PW" placeholder="Password"></div>
								</div>
								
								<?php 

								if(isset($_SESSION['niklogin'])!='')
								{
							
								// $queryCounterHide="select counterPassword from tr_login where userLogin='".$_SESSION['niklogin']."'";
									$queryCounterHide="select counterPassword from tr_login where userLogin=?";
								odbc_execute($cekCounterLockHide=odbc_prepare($conn, $queryCounterHide), array($_SESSION['niklogin']));
								// $cekCounterLockHide = odbc_exec($conn, $queryCounterHide);
								$resultCounterPassHide = odbc_result($cekCounterLockHide,'counterPassword');
								
								if($resultCounterPassHide >= 3)
								{
								
								?>
								<div id="secCodeView">
								<div class="row">
									<div class="span1">Security Code</div>
								<img class="span2" src="template/1-captcha.php" id="captcha1" /><br/>
								</div>
								<div class="row">
								<a class="span2 offset1 redStrong" href="#" onclick="
								var randomVar = Math.random();
								document.getElementById('captcha1').src='template/1-captcha.php?'+randomVar;
								document.getElementById('captcha1').focus();"
								id="change-image">Unreadable? Click this for Change text.</a>
								</div>
								<div class="row">
								<div class="span2 offset1">
								<input class="span2" type="text" id="security_code_logon" name="security_code_logon" placeholder="Entry Security Code">
								</div>
								</div>
								</div>
								<?php
								}
								}

								//$_SESSION['counterPassword']=$resultCounterPass;
								?>

								<div class="row">
								<div class="span1">Language</div>
								<div class="span2">
									<select class="span2" name="lang">
										<option selected value="en">English</option>
										<option value="id">Indonesia</option>
									</select>
								</div>
								</div>
								
								<div class="row">
									<div class="offset1 span3">
										<button class="btn btn-primary" type="submit">Login</button> 
										<a href="0-forgot.php?KeepThis=true&TB_iframe=true&height=500&width=660" class="thickbox"><button class=" btn btn-info" type="button" >Forgot Password</button></a>
									</div>
								</div>
							</form>
							<p class="fontGaris"><span>or</span></p> 
							<style>
								.btnMyKG{
									text-align: center;
								    width: 80%;
								    font-size: 18px;
								    font-family: "Trebuchet MS", Helvetica, sans-serif
					            }
					            .btnMyKG img 
								{  
									display: inline-block;
								    vertical-align: middle;
								    /*background: #f00;*/
								    padding: 5px;
								    border-radius: 5px;
								}
								.btnMyKG span
								{
									display:inline-block;
								    vertical-align:middle;
								    color: 	#3F5D99;
								}

								.fontGaris {
								   width: 100%; 
								   text-align: center; 
								   border-bottom: 1px solid lightgrey; 
								   line-height: 0.1em;
								   margin: 10px 0 20px;
								}

								.fontGaris span { 
								    background:#fff; 
								    padding:0 10px;
								    color: grey; 
								}
							</style>
							<!-- <form id="loginMyKG" method="post" action="keycloak/0-login_myKG.php?v=<?php echo urlencode(date('Ymd-His')); ?>">
								<div class="row">
									<div class="span5">
										<button class="btn btn-default btnMyKG" style="height: 50px;">               
										    <img src="img/mykgLOGO.png" width="50" height="50"/>
										    <span>Login with MyKG</span>
										</button>
									</div>
								</div>
								<p style="margin: 15px 0 15px" class="fontGaris"><span>or</span></p>
									<div class="row">
										<div class="span4">
										<div style="text-align: center;">
										<a href="0-forgot.php?KeepThis=true&TB_iframe=true&height=500&width=660" class="thickbox"><button class=" btn btn-info" type="button" >Forgot Password</button></a>
										</div>
										</div>
									</div>
							</form> -->
							
							
						</div>
						
							<?php
								if (isset($_SESSION['signup_tag'])){
									echo '<div class="tab-pane fade in active" id="signup">';
									if ($_SESSION['signup_tag']==1){
										echo '<div class="alert alert-success">';
										echo $_SESSION['signup_msg'];
										echo '</div>';
									} else if ($_SESSION['signup_tag']==2){
										echo '<div class="alert alert-error">';
										echo $_SESSION['signup_msg'];
										echo '</div>';
									} else if ($_SESSION['signup_tag']==3){
										echo '<div class="alert">';
										echo $_SESSION['signup_msg'];
										echo '</div>';
									}
								}else {
									echo '<div class="tab-pane fade" id="signup">';
								}
							?>
							<form class="indexBox well" id="signupForm" name="signupForm" action="0-signup.php" method="POST">
								<div class="row">
								<div class="span1">NIK</div>
								<div class="span2">
								<input class="span2" type="text" id="NIK" name="NIK" placeholder="NIK"  maxlength="6"></div>
								</div>
								<div class="row">
								<div class="span1">Email</div>
								<div class="span2">
								<input class="span2" type="text" id="mail" name="mail"placeholder="Email"></div>
								</div>
								<div class="row">
								<div class="span1">Confirm Email</div>
								<div class="span2">
								<input class="span2" type="text" id="remail" name="remail" placeholder="Confirm Email"></div>
								</div>
								
								<div class="row">
								<div class="span1">Hp</div>
								<div class="span2">
								<input class="span2" type="text" id="hpNo" name="hpNo"placeholder="hpNo" maxlength="15"> ex. +62XXXXXXXX</div>
								</div>

								<div class="row">
								<div class="span1">Birthplace</div>
								<div class="span2">
								<input class="span2" type="text" id="PoB" name="PoB" placeholder="Birthplace"></div>
								</div>
								<div class="row">
								<div class="span1">Birthdate</div>
								<div class="span2">
								<input class="span2" type="text" id="DoB" name="DoB" placeholder="Birthdate"></div>
								</div>
								<div class="row">
								<div class="span1">MyValueID<span style="color: red;">&nbsp;**</span></div>
								<div class="span2">
								<input class="span2" type="text" id="valueID" name="valueID" placeholder="MyValue ID"></div>
								</div>
								<div class="row">
								<div class="span1">Security Code</div>
								<div class="span2">
								<input class="span2" type="text" id="security_code" name="security_code" placeholder="Security Code"></div>
								</div>
								

								<div class="row">
								<img class="span2 offset1" src="template/1-captcha.php" id="captcha2" /><br/>
								</div>
								<div class="row">
								<a class="span2 offset1 redStrong" href="#" onclick="
								var randomVar = Math.random();
		document.getElementById('captcha2').src='template/1-captcha.php?'+randomVar;
		document.getElementById('captcha2').focus();"
		id="change-image">Unreadable? Change text.</a>
								</div>
								<br>
								<div class="row">
									<div class="span3">
										<span style="color: red; font-size: 10px;">** Leave blank if you have not register MyValue</span>
									</div>
								</div>
								<br>
								<div class="row">
								 <button class="offset1 btn btn-primary" type="submit">Sign Up</button>
								 
								</div>
							 
							</form>
						</div>
					</div>      	
				</div>
			</div>

			<!-- Example row of columns -->
			<br>
			<!-- br buat login mykg only -->
		 	<div id="br_login_mykg" style="margin-top: 200px;"></div>

			<div class="row">
				<div class="span6" id="news">
					<h2><img src="img/icon/news.png" class="icon"/>News & Information</h2>
					
					<?php
					$date_now = date('Y-m-d');
					// $a = "select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' and (a.startDate <= '$date_now' and a.endDate >='$date_now') order by priority desc, newsID desc";
					$a = "select top 5 *,datename(dw,postDate)+', '+right('00'+day(postDate),2)+' '+datename(m,postDate)+' '+convert(varchar(4),year(postDate))as PostDate from tr_news a,ms_newsCat b, lk_newsLocation c where a.newsCatCode=b.newsCatCode and b.newsLocationCode=c.newsLocationCode and a.isActive='1' and a.isFeatured='1' and a.newsCatCode='News' and c.newsLocationCode='o' and (a.startDate <= ? and a.endDate >=?) order by priority desc, newsID desc";
					odbc_execute($News=odbc_prepare($conn, $a), array($date_now, $date_now));
					// $News=odbc_exec($conn,$a);
					$i=0;
					while(odbc_fetch_row($News)){
						$id=odbc_result($News,1);
						$Title=odbc_result($News,3);
						$description=stripslashes(base64_decode(odbc_result($News,10)));
						$NewsImageName=odbc_result($News,8);
						$NewsEvent=odbc_result($News,12);
						?>

						<form id="formNews_<?=$i?>" action="news.php" method="post">
							<h4><a href="javascript:;" onclick="document.getElementById('formNews_'+<?php echo $i ?>).submit();"><?php echo trim($Title)?></a></h4>
						    <!-- <a href="javascript:;" onclick="document.getElementById('form1').submit();"></a> -->
						    <div style="margin-left:10px;"><?=$description?>
						    <br><a href="javascript:;" onclick="document.getElementById('formNews_'+<?php echo $i ?>).submit();" class="btn-small btn-info" >Read more</a> <!-- <div class="fb-like" data-href="'.$url.'" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>
							<div class="fb-share-button" data-href="'.$url.'" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fhr.kompasgramedia.com%2F&amp;src=sdkpreparse">Share</a></div></div><hr style="colour:#000;"><br> -->
							</div>
						    <input type="hidden" name="id" value="<?=$id?>"/>
						</form>
						<hr style="colour:#000;"><br>
						

						<?php
						$url='https://'.$_SERVER['HTTP_HOST'].'/news.php?id='.$id;
						$PD=date(' D, d F Y',strtotime(odbc_result($News,'PostDate')));
						// echo '<h4><a href="news.php?id='.$id.'">'. trim($Title) .'</a></h4>';
						/*echo '<div style="margin-left:10px;">'.$description.'<a href="news.php?id='.$id.'" class="btn-small btn-info" >Read more</a> <div class="fb-like" data-href="'.$url.'" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>
						<div class="fb-share-button" data-href="'.$url.'" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fhr.kompasgramedia.com%2F&amp;src=sdkpreparse">Share</a></div></div><hr style="colour:#000;"><br>';*/
						$i++;
					}

					?>
					
				</div>
				<div class="span6">	
					<h2>	<img src="img/our_product/kg_online_news.png" style="height:25px;"></h2>
						<!--<h2><img src="img/icon/news.png" class="icon"/> Headline Kompas.com</h2>-->
						<div style='overflow:auto; width:450px;height:500px;'>
								<?php
									// function curl_download($Url){

									//     if (!function_exists('curl_init')){
									//         die('cURL is not installed. Install and try again.');
									//     }

									//     $ch = curl_init();
									//     curl_setopt($ch, CURLOPT_URL, $Url);
									//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									//     $output = curl_exec($ch);
									//     curl_close($ch);
									//     $start = strpos($output, '<div class="kcm-main-list">');
									//     $end = strpos($output, '</ul>', $start);
									//     $length = $end-$start;
									//     $output = substr($output, $start, $length);
									//     $output_2 = str_replace("<a ", "<a target='blank'", $output);
									//     $output_3 = str_replace("http://assets", "https://assets-b", $output_2);
									//     return $output_3;
									// }
									
									//print curl_download('http://indeks.kompas.com/indeks/headline');

									//$get_data_api = "SELECT TOP 10 t1.id_api, t1.title, t1.path, t1.photo, t1.date, t1.source_site FROM 
									//		tb_api_kg_news t1 join tb_api_kg_news t2 on t2.source_site = t1.source_site and 
									//		t2.id_api >= t1.id_api GROUP BY t1.title, t1.path, t1.photo, t1.date, t1.source_site, 
									//		t1.id_api ORDER BY count(*), t1.id_api DESC";
									$get_data_api = "select top 5 t1.id_api, t1.title, t1.path, t1.photo, t1.date, t1.source_site, 
											'id1' OrderKey  FROM tb_api_kg_news t1 where t1.source_site='kompas.com'
											UNION ALL
											select top 5 t1.id_api, t1.title, t1.path, t1.photo, t1.date, t1.source_site,
											'id2' OrderKey  FROM
											tb_api_kg_news t1 where t1.source_site='kontan.co.id' ORDER BY OrderKey, t1.id_api desc";
									$news_api = odbc_exec($conn, $get_data_api);

                                        echo '<div class="kcm-main-list"><ul class="clearfix">';
                                        while(odbc_fetch_row($news_api)){
                                                $title = odbc_result($news_api, 'title');
                                                $path = odbc_result($news_api, 'path');
                                                $photo = odbc_result($news_api, 'photo');
                                                $date = odbc_result($news_api, 'date');
                                                $source_site = odbc_result($news_api, 'source_site');
                                                echo '<li><div class="kcm-fig-cont rect left mr1">
                                                <img src="'.$photo.'">
                                                        </div>
                                                        <div class="tleft">
                                                                <div class="grey small">'.indonesian_date($date).'</div>
                                                                <h3><a target="blank" href="'.$path.'">'.$title.'</a></h3>
                                                                <i><small>Source: '.$source_site.'</small></h6></i>
                                                        </div>
                                                </li>';
										



									}
									echo '</ul></div>';
									?>
						</div>
				</div>
			</div>
			</div>

			<br>

				<div class="row">
				 				
				 				<div class="span6">

                                	<h2><img src="img/icon/services.png" class="icon"/>Services</h2>
									
									<div class="row">
										<div class="span2">
											<ul>
												<li>- Cuti Online..</li>
												<li>- Attendance Online</li>
												<li>- Absence Online</li>
											</ul>
										</div>
										<div class="span2 last-child">
											<ul>
												<li>- SKKL Online</li>
												<li>- SMS Gateway <i class="icon-flag"></i></li>
												<li>- And More...</li>
											</ul>
										</div>
										</div>
									
                                	
                                </div>

                                <div class="span3">
                                        <h2><img src="img/icon/visitor.png" class="icon"/>Visitor Counter</h2>
                                        <?php
                                                if(!isset($_SESSION['Counter'])){
                                                        $_SESSION['Counter']="Active";
                                                        // $sqlCounter="insert into tr_visitor(IPAddress,sessionID) values ('".$_SERVER["REMOTE_ADDR"]."','".session_id()."')";
                                                        $sqlCounter="insert into tr_visitor(IPAddress,sessionID) values (?,?)";
                                                        odbc_execute($sql=odbc_prepare($conn, $sqlCounter), array($_SERVER["REMOTE_ADDR"], session_id()));
                                                        // $sql = odbc_exec($conn, $sqlCounter);

                                                }
                                        ?>
                                        <div class="row top">
                                                <div class="span2"><i class="icon-user"></i>Today</div>
                                                <div class="span1"><?php 
                                                $sql = odbc_exec($conn, "select count(id) from tr_visitor where datediff(d,[date],getdate()) = 0");
                                                
                                                echo odbc_result($sql,1);?></div>
                                        </div>
                                        <div class="row">
                                                <div class="span2"><i class="icon-user"></i>Yesterday</div>
                                                <div class="span1"><?php $sql = odbc_exec($conn, "select count(id) from tr_visitor where datediff(d,[date],getdate()) = 1"); echo odbc_result($sql,1);?></div>
                                        </div>
                                        <div class="row">
                                                <div class="span2"><i class="icon-user"></i>This Week</div>
                                                <div class="span1"><?php $sql = odbc_exec($conn, "select count(id) from tr_visitor where datepart(wk,[date])=datepart(wk,getdate()) and datepart(year,[date])=datepart(year,getdate())"); echo odbc_result($sql,1);?></div>
                                        </div>
                                <!--    <div class="row">
                                                <div class="span2"><i class="icon-user"></i>This Month</div>
                                                <div class="span1"><?php //$sql = odbc_exec($conn, "select count(id) from tr_visitor where month([date])=month(getdate()) and datepart(year,[date])=datepart(year,getdate())"); echo odbc_result($sql,1);?></div>
                                        </div>
                                        <div class="row">
                                                <div class="span2"><i class="icon-user"></i>All Time</div>
                                                <div class="span1"><?php //$sql = odbc_exec($conn, "select count(id) from tr_visitor"); echo odbc_result($sql,1);?></div>
                                        </div> -->
                                </div>

                                
                                <div class="span3">
                                        <h2><img src="img/icon/product.png" class="icon"/>Our Product</h2>

                                        <!--<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="220px" height="152px">
                                        <param name="allowScriptAccess" value="sameDomain" />
                                        <param name="allowFullScreen" value="false" />
                                        <param name="movie" value="img/ourproduct.swf" />
                                        <param name="quality" value="high" />
                                        <param name="wmode" value="opaque">
                                        <param name="bgcolor" value="#FFF">
                                        <embed src="img/ourproduct.swf" quality="high" width="220px" height="152px" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" wmode="opaque" bgcolor="#FFF"/>
                                        </object>-->

                                    <marquee behavior="scroll" direction="up" scrollamount="2" style="height: 200px;">
                                                    <a href="http://epaper.kompas.com/kompas/"></a><div style="padding:20px 0px;"><a href="http://epaper.kompas.com/kompas/"><img src="img/our_product/kompas.jpg" class="img-responsive"></a></div>
                                                    <!--<a href="http://www.gku.co.id"></a><div style="padding:20px 0px;"><a href="http://www.gku.co.id"><img src="img/our_product/GKU.jpg" class="img-responsive"></a></div>-->
                                                    <a href="http://www.gramediaprinting.com"></a><div style="padding:20px 0px;"><a href="http://www.gramediaprinting.com"><img src="img/our_product/w48.png" class="img-responsive"></a></div>
                                                    <a href="http://www.grazera.com"></a><div style="padding:20px 0px;"><a href="http://www.grazera.com"><img src="img/our_product/grazera.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.gramediamajalah.com"></a><div style="padding:20px 0px;"><a href="http://www.gramediamajalah.com"><img src="img/our_product/w41.png" class="img-responsive"></a></div>
                                                    <a href="http://www.tribunnews.com"></a><div style="padding:20px 0px;"><a href="http://www.tribunnews.com"><img src="img/our_product/49.png" class="img-responsive"></a></div>
                                                    <a href="http://www.santika.com"></a><div style="padding:20px 0px;"><a href="http://www.santika.com"><img src="img/our_product/w47.png" class="img-responsive"></a></div>
                                                    <a href="http://www.gramedia.com"></a><div style="padding:20px 0px;"><a href="http://www.gramedia.com"><img src="img/our_product/Gramedia.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.kompas.com"></a><div style="padding:20px 0px;"><a href="http://www.kompas.com"><img src="img/our_product/kompascom2.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.kompas.tv"></a><div style="padding:20px 0px;"><a href="http://www.kompas.tv"><img src="img/our_product/kompastv2.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.kontan.co.id"></a><div style="padding:20px 0px;"><a href="http://www.kontan.co.id"><img src="img/our_product/52.png" class="img-responsive"></a></div>
                                                    <a href="http://www.bolanews.com"></a><div style="padding:20px 0px;"><a href="http://www.bolanews.com"><img src="img/our_product/P2ymU6qQ.jpeg" class="img-responsive"></a></div>
                                                    <a href="http://www.sonora-network.com"></a><div style="padding:20px 0px;"><a href="http://www.sonora-network.com"><img src="img/our_product/sonora2.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.umn.ac.id"></a><div style="padding:20px 0px;"><a href="http://www.umn.ac.id"><img src="img/our_product/logoumn.jpg" class="img-responsive"></a></div>
                                                    <a href="http://www.radiosmartfm.com"></a><div style="padding:20px 0px;"><a href="http://www.radiosmartfm.com"><img src="img/our_product/smartfm2.jpg" class="img-responsive"></a></div>
                                                    <a href="http://motionradiofm.com"></a><div style="padding:20px 0px;"><a href="http://motionradiofm.com"><img src="img/our_product/motionradio2.jpg" class="img-responsive"></a></div>
                                    </marquee>				

                                </div>

			</div>

<?php

	//echo "<script>alert('".$_SESSION['niklogin']."');</script>";
	//echo "<script>alert('".$_SESSION['counterPassword']."');</script>";
	unset($_SESSION['signup_msg']);
	unset($_SESSION['signup_tag']);
	unset($_SESSION['nik']);
	unset($_SESSION['security_code']);
	unset($_SESSION['login_msg']);
	unset($_SESSION['login_tag']);
	
	include "template/bottom1.php"; //Load tenplate penutup dan load javascript eksternal
?>

<!-- Javascript dan jquery script dimulai dari sini-->
<!--<script type="text/javascript" src="js/snow.js"></script>-->
<!--<script type="text/javascript" src="js/fireworks.js"></script>-->
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.7&appId=1049694831794464";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
                <!-- Your share button code -->
<script  type="text/javascript">
$(document).ready(function(){
	$('#DoB').focus(function(){
		$(this).blur();
	});

	$( "#DoB" ).datepicker({
		dateFormat: 'dd/mm/yy',
		changeMonth: true,
		changeYear: true,
		minDate: new Date(1950, 1 - 1, 1) , 
		maxDate: '-17Y',
		yearRange: '1952',
		onChangeMonthYear: function () {
			$(this).blur();
		}
	});

	/*$( "#DoB" ).click(function(){
	    $(this).blur();
	});*/
	//$('#secCodeView').hide()
});






$(function(){
		$('#slides').slides({
			preload: true,
			preloadImage: 'img/slide/loading.gif',
			play: 4000,
			pause: 3000,
			hoverPause: true
		});
	});

jQuery.validator.addMethod("selectNone", 
	function(value, element) { 
	if (element.value == "none") 
	{ 
		return false; 
	} 
	else return true; 
	}, "Please select an option." ); 
jQuery.validator.addMethod(
    "australianDate",
    function(value, element) {
        // put your own logic here, this is just a (crappy) example
        return value.match(/^\d\d?\/\d\d?\/\d\d\d\d$/);
    },
    "Please enter a date in the format dd/mm/yyyy"
	);
$(document).ready(
	function() 
	{ 
		 $.validator.addMethod("hpNo",function(value,element){
	    return this.optional(element) || /^\d*[0-9+](|.\d*[0-9]|,\d*[0-9])?$/i.test(value);
	    },"wrong input mobile number.");

		$("#signupForm").validate(
		{
			rules: 	{
				 		hpNo: "required hpNo",
						NIK: {required: true,number:true},
						mail: {required: true, email:true},
						remail: {required: true, email:true},
						PoB: {required:true},
						DoB: {required:true, australianDate:true},
						security_code: {required:true}
					}
		});
		$("#loginForm").validate(
		{
			rules: 	{
						NIK: {required: true},
						PW: {required: true}
					}
		});				
	}); 		
		
$('#signupForm').submit(function() 
	{
		//cek no HP
		var hpNo = document.getElementById("hpNo").value;
		var hpSub=hpNo.substr(0,3);
		if(hpSub!='+62')
		{
			alert("Mobile phone number is not correct.")
			return false;
		}
	}); 		


</script>