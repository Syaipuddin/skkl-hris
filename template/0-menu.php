	<!-- start of menu-->
	<?php
	include "language/Home_library_word.php";
	if(isset($_SESSION['nik'])!='')
        {
                $NIK = base64_decode($_SESSION['nik']);
        }

         if(isset($_SESSION['nik'])==false){
                echo "<script>alert('Please login first to access this page.')</script>";
                echo "<script>window.location='index.php'</script>";
         }
	$url="http://".$_SERVER['HTTP_HOST'];
	    $sqlall="SELECT * FROM ms_EmpOrg WHERE NIK='00".$NIK."'";
	    $queryall = odbc_exec($conn,$sqlall);
            $PersAdmin=odbc_result($queryall, 'PERSADMIN');

		$sqlallname="SELECT Nama, PayArea FROM ms_niktelp WHERE NIK= '$NIK'";
		$queryallname = odbc_exec($conn,$sqlallname);
		$namalengkapquery = odbc_result($queryallname,"Nama");
		// buat medrem
		$payarea_login = odbc_result($queryallname,"PayArea");
                
                //CARI TRAVEL ONLINE PASKA
		if($NIK == '005290' || $NIK == '068101' || $NIK == '052962' || $NIK == '000909' || $NIK == '000349' || $NIK == '002367' || $NIK == '005098'){
                $sqltravelonlinePASKA="select COUNT(*) jumlahTravelonline from tb_travel a, tb_travel_pasca b where b.isApproved = 0 and a.FirstApproverNIK='$NIK' and a.TravelID=b.TravelID";
		$querytravelonlinePASKA = odbc_exec($conn,$sqltravelonlinePASKA);
		$jumlahTravelonlinePASKA = odbc_result($querytravelonlinePASKA,"jumlahTravelonline");
                
                //CARI TRAVEL ONLINE
                $sqltravelonline="select COUNT(*) jumlahTravelonline from tb_travel where isApproved = 0 and FirstApproverNIK='$NIK'";
		$querytravelonline = odbc_exec($conn,$sqltravelonline);
		$jumlahTravelonline = odbc_result($querytravelonline,"jumlahTravelonline");
		}
		//mapping akses userLogin ke module / menu yang ada (mulai)
		$sqlMdl_list = "select ModuleID from ms_Module";
		$rowsMdl_list = odbc_exec($conn, $sqlMdl_list);//listing modul yang ada
		while($row = odbc_fetch_object($rowsMdl_list)){
			$sqlView = "select count(*) from ms_ModuleView where isActive = 1 and UserLogin = '$NIK' and ModuleID =".$row->ModuleID ;
			 /*$menuFlag[jenis_flag (string)][nomor_modul(int)][(opt)nomor_spesial]
			jenis_flag = view, admin, spesial
			nomor_module = moduleID
			opt) nomor_spescial = SpesialID */
			if (odbc_result(odbc_exec($conn, $sqlView), 1)>0){
				$menuFlag['view'][$row->ModuleID]=1;//tanda user dapat mengakses menu sebagai user biasa
			}else{
				$menuFlag['view'][$row->ModuleID]=0;//tanda user TIDAK dapat mengakses menu sebagai user biasa
			}
			$sqlAdmin = "select count(*) from ms_ModuleAdmin where isActive = 1 and UserLogin = '$NIK' and ModuleID =".$row->ModuleID ;
			if (odbc_result(odbc_exec($conn, $sqlAdmin), 1)>0){
				$menuFlag['admin'][$row->ModuleID]=1;//tanda user dapat mengakses menu sebagai admin
				$sqlSpecial = "select * from ms_ModuleAdmin where isActive =1 and UserLogin = '$NIK' and ModuleID =".$row->ModuleID;
				$rowsMdl_Spec = odbc_exec($conn, $sqlSpecial);
				while ($rowSpe = odbc_fetch_object($rowsMdl_Spec)){
					$menuFlag['special'][$rowSpe->ModuleID][$rowSpe->SpecialID]=1;//mapping jenis admin spesial yang dapat diakses oleh user
				}
			}else{
				$menuFlag['admin'][$row->ModuleID]=0;//tanda user TIDAK dapat mengakses menu sebagai admin
			}
		}
		$menuDecree = 0;
		//mapping akses userLogin ke module / menu yang ada (selesai)
	?>
	<div id="menuBar" >
		<a tabindex="0"  class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="hierarchybreadcrumb"><span class="ui-icon ui-icon-triangle-1-s">
		</span><?php echo $word[45]; //Menu ?></a>
		<div id="news-items" class="hidden">
		<ul>
			<?php
//                    -----------------------------------------------------------------------
//                    --------------- MANAGER SELF SERVICE MENU START -----------------------

                    $sqlmss="SELECT COUNT(*) AS JUMLAH FROM ms_EmpOrg WHERE  (isChief = 2 or isChief=1) AND NIK='00".$NIK."'";
                    $statusmss=odbc_exec($conn,$sqlmss);
                    $ISCHIEF = odbc_result($statusmss,"JUMLAH");

                     if($ISCHIEF>0){
                    ?>
                        <li><a href=""><?php echo $word[181]; // Manager Self Services ?></a>
                            <ul>
                                    <li><a href=""><?php echo $word[50]; // Time Management ?></a>
                                        <ul>
                                        	<li><a href="MSScalendar.php"><?php echo $word[198]; // All Calendar ?></a></li>
                                                <li><a href="MSSrecapitulationTimeEvent.php"><?php echo $word[186]; // Time Data Recapitulation ?></a></li>
                                                <li><a href="MSStimeEvent.php"><?php echo $word[10]; // Time Event ?></a></li>
                                                <li><a href="MSSsisacuti.php"><?php echo $word[22]; // Leave Balance ?></a></li>
												<li><a href="MSS_ShiftScheduling.php"><?php echo $word[199]; // Shift Scheduling ?></a></li>
												<li><a href="MSS_leave_history.php"><?php echo 'Leave History'; // Shift Scheduling ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href=""><?php echo $word[168]; // Personal Data ?></a>
                                        <ul>
                                                <li><a href="MSSprofile.php"><?php echo $word[5]; // Profile ?></a></li>
                                                <li><a href="MSSmedical.php"><?php echo $word[187]; // Medical by Person ?></a></li>
                                                <li><a href="MSSmedicalgrup3.php"><?php echo $word[188]; // Medical by Organization ?></a></li>
                                                <li><a href="MSSplafonmedical.php"><?php echo $word[203]; // Pagu medical ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="MSStreechart.php"><?php echo $word[189]; // Tree Chart Organization ?></a></li>
                                    <?php
		                            	if($menuFlag['view'][23]==1){
											$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
											$md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));
											echo '<li><a href="/MD/index.php/account/portal_login/'.$NIK.'/'. $md5_password.'">Dashboard</a></li>';// end of SKC
										}

		if($menuFlag['admin'][31]==1)
		{
			$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
			$md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));

			if ($NIK == '050143' || $NIK == '004565') {
				$persAdmin_msg = " = '002' OR pers_admin IN(SELECT PersAdmin FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')";
			}
			else{
				$persAdmin_msg = "= '".$PersAdmin."'";
			}

			$sqlDecreeQuery="select count(*) 
								from tb_online_decree
								WHERE print_date IS NULL
								and decree_type = 'Z001'
								and user_printed ='".$NIK. "' AND pers_admin ".$persAdmin_msg;

			$totalDecree = odbc_result(odbc_exec($conn, $sqlDecreeQuery),1);
			$menuDecree = 1;
		?>

                                        <li><a href="https://hr.kompasgramedia.com/hr1/index.php/account/login_transfer/<?php echo $NIK ?>/<?php echo $md5_password ?>/EN/<?php echo 'decree%2Fdecree'?>">Online Decree</a></li>
            <?php } ?>
                            </ul>
                        </li>
                    <?php
                     }
//                    --------------------- MANAGER SELF SERVICE MENU END --------------------
//                    ------------------------------------------------------------------------
                    ?>

                    <?php
//                    -----------------------------------------------------------------------
//                    --------------- HR SELF SERVICE MENU START -----------------------

                    	$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAH FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and b.isActive=1";
                        $StatusHrss=odbc_exec($conn,$sqlhrss);

                        if(odbc_result($StatusHrss,"JUMLAH")>0 || ($menuFlag['admin'][31]==1))
                        {


					?>
						<li><a href=""><?php echo $word[182]; // Hr Self Services ?></a>
							<ul>

								<?php
								//START MSS Online Decree (PKWT) Khusus GOMAN
								if($menuFlag['admin'][31]==1 && $menuDecree == 0)
								{
									$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
									$md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));

									odbc_execute($qry_akses_persadmin = odbc_prepare($conn, "SELECT COUNT(*) as jml FROM tb_akses_PersAdmin where status = 1 and NIK_Admin = '?'"), array($NIK));
									$akses_persadmin = odbc_result($qry_akses_persadmin, "jml");


									if ($akses_persadmin > 0) {
										$persAdmin_msg = "(pers_admin = '$pers_admin' OR pers_admin IN(SELECT a.PersAdmin_Id FROM HRSS_PersAdmin a INNER JOIN tb_akses_PersAdmin b ON a.PersAdmin_parent = b.akses_PersAdmin WHERE b.NIK_Admin = '".$NIK."'))";
									}
									else{
										$persAdmin_msg = "pers_admin = '".$PersAdmin."'";
									}

									$sqlDecreeQuery="select count(*) 
														from tb_online_decree
														WHERE print_date IS NULL
														and decree_type = 'Z001'
														and user_printed ='".$NIK."' AND ".$persAdmin_msg;

									$totalDecree = odbc_result(odbc_exec($conn, $sqlDecreeQuery),1);
								?>
						            <li><a href="https://hr.kompasgramedia.com/hr1/index.php/account/login_transfer/<?php echo $NIK ?>/<?php echo $md5_password ?>/EN/<?php echo 'decree%2Fdecree'?>">Print Online Decree</a></li>
						        <?php }
						        //END MSS Online Decree (PKWT) Khusus GOMAN


								// HCM Personnel Action
								$sqlhrss = "SELECT COUNT(b.UserLogin) AS JUMHCMPA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' AND b.isActive=1 and a.SpecialID=b.SpecialID and (a.SpecialName= 'HRSS_HCM_PA' OR a.SpecialName= 'HRSS_HCM_PA_S') ";
								$StatusHrss=odbc_exec($conn,$sqlhrss);
								if (odbc_result($StatusHrss,"JUMHCMPA")>0) {
									echo '<li>';
									echo '<a href="">Personnel Action</a>';
									echo '<ul>';
									$sqlhrss = "SELECT COUNT(b.UserLogin) AS JUMHCMPA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and (a.SpecialName= 'HRSS_HCM_PA') ";
									$StatusHrss=odbc_exec($conn,$sqlhrss);
									if (odbc_result($StatusHrss,"JUMHCMPA")>0) {
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fhiring">Hiring</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Frenewal">Contract Renewal </a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fpermanent">Change to Permanent</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fmovement">Employee Movement</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fterminate">Termination</a></li>';
										echo '<hr>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fpa30">Maintain Data</a></li>';
                                                                                echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fupdatepwt">Change PWT</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fmarital">Change of Marital Status</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Ffamily">Change of Family Status</a></li>';
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Freport">Report</a></li>';
									}

									$sqlhrss = "SELECT COUNT(b.UserLogin) AS JUMHCMPA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and (a.SpecialName= 'HRSS_HCM_PA_S') ";
									$StatusHrss=odbc_exec($conn,$sqlhrss);
									if (odbc_result($StatusHrss,"JUMHCMPA")>0) {
										echo '<li><a href="redirect.php?to=hrss&redirect=pa%2Fsetting">Setting</a></li>';

									}
									echo '</ul>';
									echo '</li>';
								}


								?>
									<?php
									// -----------------------------------------------------------------------
									//  --------------- TM (HR SELF SERVICE MENU) START -----------------------

                            		$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHTM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_TM' ";
				                        $StatusHrss=odbc_exec($conn,$sqlhrss);
				                        if(odbc_result($StatusHrss,"JUMLAHTM")>0)
				                        {
                            		?>
                                    <li><a href=""><?php echo $word[50]; // Time Management ?></a>
                                        <ul>
                                                <li><a href="HRSS_Calendar.php"><?php echo $word[198]; // All Calendar ?></a></li>
                                                <li><a href="HRSS_RecapitulationTimeEvent.php"><?php echo $word[186]; // Time Data Recapitulation ?></a></li>
                                                <li><a href="HRSS_TimeEvent.php"><?php echo $word[10]; // Time Event ?></a></li>
                                                <li><a href="HRSS_sisacuti.php"><?php echo $word[22]; // Leave Balance ?></a></li>

                                                <?php
                                                //SKKL HR
                                                    $StatusHRQry=odbc_exec($conn,"select * from tr_login a inner join ms_ModuleAdmin b on a.userLogin =b.UserLogin where b.UserLogin ='$NIK' and ModuleID = 5 and SpecialID = 6 and b.isActive=1 and a.isActive=1");

                                                    if (odbc_result($StatusHRQry,"ModuleRoleID") == 3){
                                                    	// ini tutup dulu sampai tgl 10
                                                        echo '<li><a href="skklHRList.php" >SKKL HR';
                                                        $StatusHRQry=odbc_exec($conn,"select * from tb_SKKLTransaction a inner join ms_niktelp b on a.NIK=b.NIK where a.isApproved='true' and a.HRNIK is NULL and b.PersAdmin='$PersAdmin' and a.Flag is null");
                                                        $GetTotalHRApp=odbc_exec($conn,"select count(*) as totalHR from tb_SKKLTransaction a inner join ms_niktelp b on a.NIK=b.NIK where isApproved='true' and HRNIK is NULL and PersAdmin='$PersAdmin' and (Flag is null or Flag ='1') AND InputDate BETWEEN DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE())-1, 0) AND DATEADD (dd, -1, DATEADD(mm, DATEDIFF(mm, 0, GETDATE()) + 1, 0))");
                                                        if (odbc_result($GetTotalHRApp,"totalHR") !=0){
                                                            $totalHR = odbc_result($GetTotalHRApp,"totalHR");
                                                            echo '<font class="redStrong"> ('.odbc_result($GetTotalHRApp,"totalHR").') </font>';
                                                        }
                                                        echo '</a></li>';
                                                        echo '<li><a href="skklReport.php?flag=0" >'.$word[71].'</a></li>';
                                                        // ini tutup dulu

                                                        //SKKL Report
                                                        /*echo '<li><a href="skklListPendingApprove.php" ><img src="img/icon/alert.png" />'.$word[85].'</a></li>'; //SKKL List Pending Approve
                                                        echo '<li><a href="reqAttendanceListPendingApprove.php" ><img src="img/icon/alert.png" />'.$word[62].'</a></li>'; //Attendance List Pending Approval
                                                        echo '<li><a href="absenceListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[79].'</a></li>'; //Absence List Pending Approval
                                                        echo '<li><a href="reqCutiListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[84].'</a></li>'; //Leave List Pending Approval */
                                                  }
														echo '<li><a href="skklListPendingApprove.php" ><img src="img/icon/alert.png" />'.$word[85].'</a></li>'; //SKKL List Pending Approve
                                                        echo '<li><a href="reqAttendanceListPendingApprove.php" ><img src="img/icon/alert.png" />'.$word[62].'</a></li>'; //Attendance List Pending Approval
                                                        echo '<li><a href="absenceListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[79].'</a></li>'; //Absence List Pending Approval
                                                        echo '<li><a href="reqCutiListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[84].'</a></li>'; //Leave List Pending Approval

                                                ?>
                                        </ul>
                                    </li>
                                <?php
                                  }
                  // -----------------------------------------------------------------------

								    //  --------------- SJ (HR SELF SERVICE MENU) START -----------------------?>

								  <?php
									$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHSJ FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_SJ' and b.isActive = 1";
										$StatusHrss=odbc_exec($conn,$sqlhrss);
										if(odbc_result($StatusHrss,"JUMLAHSJ")>0)
										{
											?>
											<li><a href=""><?php echo $word[232]; // Surat Jaminan ?></a>
												<ul>
														<li><a href="SuratJaminan_ApprovalStep1.php"><?php echo $word[233]; // ApprovalRequest ?></a></li>
														<li><a href="SuratJaminan_HistoryApproval.php">History Approval <?php // History Approval ?></a></li>
														<li><a href="#"><?php echo $word[234]; // MasterData ?></a>
															<ul>
																<li><a href="SuratJaminan_MasterData.php"><?php echo $word[238]; ?></a></li>
																<li><a href="SuratJaminan_MasterAlamatTelp.php"><?php echo $word[239] ?></a></li>
															</ul>
														</li>
												</ul>
											</li>
										<?php
										  }

				  			//  --------------- SJ (HR SELF SERVICE MENU) END -----------------------

							//  --------------- Tunjangan Sekolah (HR SELF SERVICE MENU) START -----------------------?>

								  <?php
									$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHSJ FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_TunjSekolah' and b.isActive = 1";
										$StatusHrss=odbc_exec($conn,$sqlhrss);
									// echo "<script>console.log('tunjsekolah: ".$total_tunjSekolah."');</script>";

										if(odbc_result($StatusHrss,"JUMLAHSJ")>0)
										{
											//jumlah approval gantung
											$ms_niktelp_tunjSek = odbc_exec($conn, "SELECT * FROM ms_niktelp WHERE NIK = '$NIK'");
								            $persadmin_tunjSek = odbc_result($ms_niktelp_tunjSek, "PersAdmin");

								            // Penjagaan baru, by table aja jgn persadmin
            								$dataqry = odbc_exec($conn, "SELECT a.* FROM tb_tunjSekolah a, ms_niktelp b, tb_tunjSekolah_akses c where a.status_approve = 0 and a.nik = b.NIK AND (b.PersArea = c.PersArea and b.SubArea = c.PSubarea and ((c.PersAdmin IS NOT NULL and b.PersAdmin = c.PersAdmin) or (c.PersAdmin IS NULL)) and (c.PIC = '$NIK' OR c.GroupAkses IN(SELECT group_akses FROM tb_tunjSekolah_admin WHERE NIK = '$NIK'))) ORDER BY a.id_header ASC");

								            /*if ($persadmin_tunjSek == '002') {
								              $persadmin_msg = "(persadmin = '002' OR persadmin BETWEEN '160' and '293')";
								            }
								            else if ($persadmin_tunjSek == '007') {
								              $persadmin_msg = "persadmin = '$persadmin'";
								            }
								            else{
								              if ($NIK!="005290" && $NIK!="003705") {
								                //untuk FUHR dibagi2 per persarea dan subareanya
								                $dataqry = odbc_exec($conn, "SELECT a.* FROM tb_tunjSekolah a, ms_niktelp b, tb_tunjSekolah_akses c where a.status_approve = 0 and a.nik = b.NIK AND (b.PersArea = c.PersArea and b.SubArea = c.PSubarea and c.PIC = '$NIK') ORDER BY a.id_header ASC");
								              }
								              else{
								                $dataqry = odbc_exec($conn, "SELECT * FROM tb_tunjSekolah WHERE status_approve = 0 AND persadmin != '002' AND (persadmin NOT BETWEEN '160' and '293') AND persadmin != '007' ORDER BY id_header ASC");
								              }
								            }

								            if ($persadmin_tunjSek == '002' || $persadmin_tunjSek == '007') {
								              $dataqry = odbc_exec($conn, "SELECT * FROM tb_tunjSekolah WHERE status_approve = 0 AND ".$persadmin_msg." ORDER BY id_header ASC");
								            }*/

											$total_tunjSekolah = odbc_num_rows($dataqry);
											
											?>
											<li><a href=""><?php echo $word[243]; // Tunjangan Sekolah ?></a>
												<ul>
														<li><a href="tunjSekolah_approval.php"><?php echo $word[64]; // ApprovalRequest ?></a></li>
														<li><a href="tunjSekolah_historyApproval.php"><?php echo $word[65]; // History Approval ?></a></li>
												</ul>
											</li>
										<?php
										}

				  			//  --------------- Tunjangan Sekolah (HR SELF SERVICE MENU) END -----------------------

									//  --------------- TM (HR SELF SERVICE MENU) END -----------------------



                  // -----------------------------------------------------------------------
									//  --------------- PA (HR SELF SERVICE MENU) START -----------------------
								$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHPA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_PA' ";
										$StatusHrss=odbc_exec($conn,$sqlhrss);
										if(odbc_result($StatusHrss,"JUMLAHPA")>0)
										{
								?>
									<li><a href=""><?php echo $word[168]; // Personal Data ?></a>
										<ul>
												<li><a href="HRSS_Profile.php"><?php echo $word[5]; // Profile ?></a></li>
                                                                                                <li><a href="HRSS_ReportKaryawanAktif.php"><?php echo $word[227]; // Laporan karyawan aktif ?></a></li>
												<li><a href="HRSS_ExitInterview.php"><?php echo $word[229]; // Wawancara Pengunduran Diri ?></a></li>

                                                <li><a href="HRSS_ExitInterviewHistory.php"><?php echo $word[230]; // Daftar Riwayat Pengunduran Diri ?></a></li>
													</ul>
									</li>
<?php
                                  }
                  // -----------------------------------------------------------------------
									//  --------------- PA (HR SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
									//  --------------- COMBEN (HR SELF SERVICE MENU) START -----------------------
								$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_CB' ";
										$StatusHrss=odbc_exec($conn,$sqlhrss);
										if(odbc_result($StatusHrss,"JUMLAHCOMBEN")>0)
										{
		?>
									<li><a href="">Compensation & Benefit</a>
										<ul>
												<li><a href="HRSS_Medical.php"><?php echo $word[187]; // Medical by Person ?></a></li>
												<li><a href="HRSS_Medicalgrup3.php"><?php echo $word[188]; // Medical by Organization ?></a></li>
                                                                                                <li><a href="HRSS_plafonmedical.php"><?php echo $word[203]; // Pagu medical ?></a></li>
										</ul>
									</li>
<?php
                                  }
                  // -----------------------------------------------------------------------
									//  --------------- COMBEN (HR SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
									//  --------------- OM (HR SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHOM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_OM' ";
				                        $StatusHrss=odbc_exec($conn,$sqlhrss);
				                        if(odbc_result($StatusHrss,"JUMLAHOM")>0)
				                        {
?>
                                    <li><a href="">Organization Management</a>
                                        <ul>
                                                <li><a href="HRSS_Treechart.php"><?php echo $word[189]; // Tree Chart Organization ?></a></li>
                                        </ul>
                                    </li>
<?php
                                }
                  // -----------------------------------------------------------------------
									//  --------------- OM (HR SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
									//  --------------- MAP (HR SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHMAP FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_MAP' ";
				                        $StatusHrss=odbc_exec($conn,$sqlhrss);
				                        if(odbc_result($StatusHrss,"JUMLAHMAP")>0)
				                        {
?>
																    <li><a href="ER_Search.php"><?php echo $word[190]; //Employee Map ?></a></li>
<?php
                                }
                  // -----------------------------------------------------------------------
									//  --------------- MAP (HR SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
									//  --------------- ROLEADMIN (HR SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHROLE FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_ROLE' ";
				                        $StatusHrss=odbc_exec($conn,$sqlhrss);
				                        if(odbc_result($StatusHrss,"JUMLAHROLE")>0)
				                        {
?>

																    <li><a href=""><?php echo $word[200]; // Master Admin ?></a>
												                <ul>
												                        <li><a href="HRSS_MasterUser.php"><?php echo $word[201]; // Account Management ?></a></li>
												                </ul>
												            </li>

<?php
}
									// -----------------------------------------------------------------------
									//  --------------- ROLEADMIN (HR SELF SERVICE MENU) END -----------------------

$sqlhrss2="select COUNT(*) as JUMLAHASSESS from ais_transaction where approval_superior=0 AND superior_nik='$NIK'";
$Assessment=odbc_exec($conn,$sqlhrss2);
$totalAssessment=odbc_result($Assessment,"JUMLAHASSESS");

$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and (a.SpecialName='HRSS_AISHR' OR a.SpecialName='HRSS_AISADMIN')";

            $StatusHrss=odbc_exec($conn,$sqlhrss);
            if(odbc_result($StatusHrss,"JUMLAHCOMBEN")>0)
            {
?>
<li><a href="">Assessment</a>
    <ul>
<?php

            
                
                
                $sqlhrss2="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_AISHR'";
                $StatusHrss2=odbc_exec($conn,$sqlhrss2);
                if(odbc_result($StatusHrss2,"JUMLAHCOMBEN")>0)
                {

?>


																		<li><a href="AIS_CalendarRequest.php">Assessment Request</a></li>
<li><a href="AIS_RequestStatus.php">Assessment Request status</a></li>


                                      <?php
                }

                $sqlhrss2="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_AISADMIN'";
                $StatusHrss2=odbc_exec($conn,$sqlhrss2);
                if(odbc_result($StatusHrss2,"JUMLAHCOMBEN")>0)
                {

?>

<li><a href="AIS_AdminRequestApproval.php">Assessment Request Approval</a></li>
<li><a href="AIS_MasterAssessor.php">Master Assessor</a></li>
<li><a href="AIS_AdminAssessmentRealization.php">Assessment Realization</a></li>
<li><a href="AIS_AdminReportSubmission.php">Report Submission</a></li>
<li><a href="AIS_AdminReport.php">Assessment Schedule Report</a></li>
<li><a href="AIS_AdminUploadHasil.php">Upload Hasil Assessment</a></li>
<li><a href="AIS_UploadHasilOneByOne.php"><?php echo "Input Hasil Assessment" // Upload Hasil Assessmen ?></a></li>
<li><a href="AIS_AdminLaporanDemografiPesertaAssessment.php">Laporan Demografi Peserta Assessment</a></li>
                <?php
                }
                ?>
                   </ul>
</li>
<?php
            }


// START DECREE ONLINE HRSS
$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and (a.SpecialName='HRSS_OnlineDecree') AND isActive = 1";
			$StatusHrss=odbc_exec($conn,$sqlhrss);
            if(odbc_result($StatusHrss,"JUMLAHCOMBEN")>0)
            {
            
            $sqlDecreeHRSSQuery="select count(*) 
								from tb_online_decree
								WHERE print_date IS NULL
								and user_printed ='".$NIK. "'";

			$totalDecreeHRSS = odbc_result(odbc_exec($conn, $sqlDecreeHRSSQuery),1);
?>

<li><a href="">Online Decree</a>
    <ul>
    	<?php 
    	echo '<li>Menu</li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fdecree">Decree Request</a></li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fdecree_report">Decree Report</a></li>';
    	echo '<hr>';
    	echo '<li>Master Data</li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fmaster_data_supervisor">Tanda Tangan</a></li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fmaster_data_cap_area">Cap Perusahaan</a></li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fmaster_data_header">Header Surat</a></li>';
    	echo '<li><a href="redirect.php?to=hrss&redirect=decree_hrss%2Fmaster_data_footer">Footer Surat</a></li>';
    	?>
    </ul>

<?php

}


// END DECREE ONLINE HRSS



$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHDATA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and (a.SpecialName='HRSS_OSHR' OR a.SpecialName='HRSS_OSADMIN')";

            $StatusHrss=odbc_exec($conn,$sqlhrss);
            if(odbc_result($StatusHrss,"JUMLAHDATA")>0)
            {
?>
<li><a href="">OS Management</a>
    <ul>
<?php


                // HRSS OS HR UNIT START

                $sqlhrss2="SELECT COUNT(b.UserLogin) AS JUMLAHDATA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_OSHR'";

                $StatusHrss2=odbc_exec($conn,$sqlhrss2);
                if(odbc_result($StatusHrss2,"JUMLAHDATA")>0)
                {

?>
                <li><a href="OS_unit.php"><?php echo $word[223];?></a></li>
		<li><a href="OS_VendorUnit.php"><?php echo $word[224];?></a></li>
	    	<li><a href="OS_ViewUnitVendor.php"><?php echo $word[225];?></a></li>
                <li><a href=""><?php echo $word[212];?></a>
                    <ul>
    			<li><a href="OS_ChartRekapitulasiOS.php"><?php echo $word[216];?></a></li>
    			<li><a href="OS_ChartVendor2.php"><?php echo $word[217];?></a></li>
                    </ul>
                </li>
				      <?php
                }

                // HRSS OS ADMIN START (CHR)

                $sqlhrss2="SELECT COUNT(b.UserLogin) AS JUMLAHDATA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_OSADMIN'";
                $StatusHrss2=odbc_exec($conn,$sqlhrss2);
                if(odbc_result($StatusHrss2,"JUMLAHDATA")>0)
                {

?>
                        <li><a href="OS_index.php"><?php echo $word[204];?></a></li>
                        <li><a href=""><?php echo $word[205];?></a>
                            <ul>
                                <li><a href="OS_Vendor.php"><?php echo $word[206];?></a></li>
			 					<li><a href="OS_PolaOS.php"><?php echo $word[207];?></a></li>
				    			<li><a href="OS_KegiatanOS.php"><?php echo $word[208];?></a></li>
				    			<li><a href="OS_PekerjaanOS.php"><?php echo $word[209];?></a></li>
				    			<li><a href="OS_AspekPrtyEval.php"><?php echo $word[210];?></a></li>
				    			<li><a href="OS_PrtyEval.php"><?php echo $word[211];?></a></li>
                            </ul>
                        </li>
                        <li><a href=""><?php echo $word[212];?></a>
                            <ul>
                                <li><a href="OS_ViewArea.php"><?php echo $word[213];?></a></li>
				    			<li><a href="OS_ViewPekerjaan.php"><?php echo $word[214];?></a></li>
				    			<li><a href="OS_Report.php"><?php echo $word[215];?></a></li>
				    			<li><a href="OS_ChartRekapitulasiOS.php"><?php echo $word[216];?></a></li>
				    			<li><a href="OS_ChartVendor.php"><?php echo $word[217];?></a></li>
				    			<li><a href="OS_ChartPolaOS.php"><?php echo $word[218];?></a></li>
				    			<li><a href="OS_ChartKegiatan.php"><?php echo $word[219];?></a></li>
				    			<li><a href="OS_ChartPekerjaan.php"><?php echo $word[220];?></a></li>
				    			<li><a href="OS_ChartProvinsi.php"><?php echo $word[221];?></a></li>
				    			<li><a href="OS_ChartKabupaten.php"><?php echo $word[222];?></a></li>
                            </ul>
                        </li>
                <?php
                }
                ?>
                   </ul>
</li>
<?php
            }



        function encodeString($stringCode){
            return base64_encode($stringCode);
	}

	$queryPersAdmin = odbc_exec($conn,"SELECT PersAdmin
					   FROM [PORTAL].[dbo].[ms_ModuleAdmin]
					   WHERE SpecialID in (
					   SELECT SpecialID
					   FROM [PORTAL].[dbo].[ms_ModuleSpecial]
					   WHERE SpecialName LIKE '%HRSS_MD%'
					)  AND UserLogin = '$NIK'
					   AND isActive = 1");

	if(odbc_num_rows($queryPersAdmin) >= 1){
		while($row = odbc_fetch_row($queryPersAdmin)){
			$PersAdmin_Id = odbc_result($queryPersAdmin,"PersAdmin");
			$OrgID = '';
			if($PersAdmin_Id != '001'){
				$queryOrgID = odbc_exec($conn,"SELECT Organization_Id
											   FROM ms_PersAdminOrganization
											   WHERE PersAdmin_Id = '$PersAdmin_Id' AND
													 IsActive = 1");

				while($row = odbc_fetch_row($queryOrgID)){
					if($OrgID != ''){
						$OrgID = $OrgID.','.odbc_result($queryOrgID,"Organization_Id");
					}
					else{
						$OrgID = odbc_result($queryOrgID,"Organization_Id");
					}
				}
			}
		}
		if($OrgID == ''){
			$OrgID = 0;
		}
		$stringCode = 'kompas'.'persadmin='.$PersAdmin_Id.'&orgid='.$OrgID.'|';
		$code = encodeString($stringCode);
		$url="http://".$_SERVER['HTTP_HOST'];

		if($url == "http://10.9.70.30"){
        //if($url == "http://10.10.55.25"){
			echo "<li><a href=http://10.10.55.57/tangkap?id=$code".'>HR Management Dashboard</a></li>';
		}
		else{
			echo "<li><a href=http://hrmd.kompasgramedia.com/tangkap?id=$code".'>HR Management Dashboard</a></li>';
		}
	}
	
	//PEOPLE REVIEW HRSS
	$queryPeopleReview = odbc_exec($conn,"SELECT PersAdmin
					   FROM [PORTAL].[dbo].[ms_ModuleAdmin]
					   WHERE SpecialID in (
					   SELECT SpecialID
					   FROM [PORTAL].[dbo].[ms_ModuleSpecial]
					   WHERE SpecialName LIKE '%HRSS_P_REVIEW%'
					)  AND UserLogin = '$NIK'
					   AND isActive = 1");

	if(odbc_num_rows($queryPeopleReview) >= 1){
		while($row = odbc_fetch_row($queryPeopleReview)){
			$PersAdmin_Id = odbc_result($queryPeopleReview,"PersAdmin");
		}
		$stringCode = 'nik='.$NIK.'&persadmin='.$PersAdmin_Id;
		$code = encodeString($stringCode);

		echo "<li><a href=/people_review/HomeController/check_login/$code".'>People Review</a></li>';
	}
        
        //HRSS check payroll
        $querypayroll = odbc_exec($conn,"SELECT PersAdmin
					   FROM [PORTAL].[dbo].[ms_ModuleAdmin]
					   WHERE SpecialID in (
					   SELECT SpecialID
					   FROM [PORTAL].[dbo].[ms_ModuleSpecial]
					   WHERE SpecialName LIKE '%HRSS_PAYROLL%'
					)  AND UserLogin = '$NIK'
					   AND isActive = 1");

	if(odbc_num_rows($querypayroll) >= 1){
		while($row = odbc_fetch_row($queryPeopleReview)){
			$PersAdmin_Id = odbc_result($queryPeopleReview,"PersAdmin");
		}
		$stringCode = 'nik='.$NIK.'&persadmin='.$PersAdmin_Id;
		$code = encodeString($stringCode);

		echo '<li><a href="HRSSsptlink.php">HRSS 1721 A1 Online</a></li>';
                echo '<li><a href="HRSSpayrolllink.php">HRSS Check Payroll</a></li>';
	}

$sqlcal_event="select count(b.UserLogin) as total_id from ms_ModuleSpecial a, ms_ModuleAdmin b  where a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_CalendarEventHR'";
        $query_cevent=odbc_exec($conn,$sqlcal_event);
        if(odbc_result($query_cevent,"total_id")>0)
        {
?>
<li><a href=""><?php echo "Calendar Event"; ?></a>
                            <ul>
                                <li><a href="CE_index.php"><?php echo "Calendar";?></a></li>
                        <?php
                                $sqlcal_event_admin="select count(b.UserLogin) as total_id_admin from ms_ModuleSpecial a, ms_ModuleAdmin b  where a.SpecialName like '%HRSS%' and b.UserLogin     ='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_CalendarEventAdmin'";
                                $query_cevent_admin=odbc_exec($conn,$sqlcal_event_admin);


                                if(odbc_result($query_cevent_admin,"total_id_admin")>0)
                                {
                                        echo "<li><a href='CE_listEvents.php'>List Event</a></li>";
                                        echo "<li><a href='CE_listCategories.php'>List Categories</a></li>";
                                        echo "<li><a href='CE_listTypes.php'>List Types</a></li>";
                                }
                                ?>
                            </ul>
                        </li>
<?php }
	
	// Start of Labor Risk Calendar
	$sqlVisCal = "SELECT COUNT(b.UserLogin) AS JUMPIMHR FROM ms_ModuleSpecial a, ms_ModuleAdmin b  WHERE b.UserLogin = '".$NIK."' and a.SpecialID = b.SpecialID and (a.SpecialName = 'HRSS_PIMPINAN_HR')";
	$accessVisCal = odbc_exec($conn, $sqlVisCal);
	if(odbc_result($accessVisCal, 1) >= 1){
		echo "<li><a href='LR_LaborRiskCalendarDownload.php'>Labor Risk Calendar</a></li>";
	}
	// End of Labor Risk Calendar


	// START HRSS DOWNLOAD FORM TRIBUN
        //  --------------- OM (HR SELF SERVICE MENU) START -----------------------
            $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHOM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_DOWNLOADFORMTRIBUN' ";
                            $StatusHrss=odbc_exec($conn,$sqlhrss);
            if(odbc_result($StatusHrss,"JUMLAHOM")>0)
            {
?>
                <li><a href="files/sisdm%20v2/">Download Form</a></li>

    <?php
            }
            //END OF HRSS DOWNLOAD FORM TRIBUN

	$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
        $md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));
        
        $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHOM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_PROMO' ";
                            $StatusHrss=odbc_exec($conn,$sqlhrss);
        if(odbc_result($StatusHrss,"JUMLAHOM")>0)
        {
            echo "<li><a href='https://hr.kompasgramedia.com/hr1/index.php/account/login_transfer/".$NIK."/".$md5_password."/EN/promo%2Fpromo'>Promo Barter</a></li>";
        }
        
        $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHOM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='HRSS_IDCARDSN' ";
                            $StatusHrss=odbc_exec($conn,$sqlhrss);
        if(odbc_result($StatusHrss,"JUMLAHOM")>0)
        {
            echo "<li><a href='HRSS_ListEmployee.php'>$word[228]</a></li>";//serial number
        }
        
        // echo '<li><a href="http://202.146.0.43:8080/share/page/" target="_blank">HR Documents</a><li>';
    
        
        $sqlidcard="select count(b.UserLogin) as Total_ID from ms_Module a, ms_ModuleAdmin b, ms_ModuleSpecial c
where b.ModuleID=29 and b.UserLogin='".$NIK."'and c.SpecialID=54";
        $query_idcard=odbc_exec($conn,$sqlidcard);
        	$url="http://".$_SERVER['HTTP_HOST'];
        if(odbc_result($query_idcard,"Total_ID")>0)
        {
                 if(trim($url) == "http://10.9.70.30"){
?>                
    <li><a href="">ID Card Online</a>
            <ul>
                <li><a href="0-logout.php?ref=http://10.9.70.44/backend/portal_login/<?php echo $NIK; ?>/<?php echo $md5_password; ?>/1"><?php echo "ID Card Online SAP"; //  ?></a></li>
                <li><a href="0-logout.php?ref=http://10.9.70.44/backend/"><?php echo "ID Card Online Non SAP"; // ?></a></li>
            </ul>
    </li>
<?php      }else{  ?>
    <li><a href="">ID Card Online</a>
            <ul>
                <li><a href='0-logout.php?ref=https://idcard.kompasgramedia.com/backend/portal_login/<?php echo $NIK; ?>/<?php echo $md5_password; ?>/1'>ID Card Online SAP</a></li>
                <li><a href="https://idcard.kompasgramedia.com/backend/"><?php echo "ID Card Online Non SAP"; // ?></a></li>
            </ul>
    </li>




<?php } 
}
	
 ?>

    <?php
    	$sql_for_billing="select count(a.UserLogin) as hitung from ms_ModuleAdmin a, ms_ModuleSpecial b where (b.SpecialName='HRSS_IDCARD_BILLING_MASTER' or a.ModuleID=29)  and
	 a.SpecialID=b.SpecialID and a.UserLogin='$NIK'";
    	// $sql_for_billing="SELECT COUNT(b.UserLogin) AS JUMLAHROLE FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%id_card_admin%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_ROLE' AND b.isActive='1' ";
	         $Status_for_billing=odbc_exec($conn,$sql_for_billing);
	         if(odbc_result($Status_for_billing,"hitung")>0)
	         {
	?>

	                            <li><a href=""><?php echo 'ID Card Online Billing '; // Master Admin ?></a>
	                                <ul>
	                                        <li><a href="billingConfirm.php"><?php echo 'Billing '; // Account Management ?></a></li>
	                                         <li><a href="billingHistory.php"><?php echo "History"; // Account Management ?></a></li>
	                                </ul>
	                            </li>

	<?php
                }
    ?>


    </ul>



                    <?php
                }
//                    --------------------- HR SELF SERVICE MENU END --------------------
//                    ------------------------------------------------------------------------
//                    -----------------------------------------------------------------------
//                    --------------- Secretary SELF SERVICE MENU START -----------------------

                        $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAH FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and b.isActive=1 ";
                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                        if(odbc_result($StatusHrss,"JUMLAH")>0)
                        {


                    ?>
                        <li><a href=""><?php echo $word[202]; // Secretariate Self Services ?></a>
                            <ul>
                                    <?php
                                    // -----------------------------------------------------------------------
                                    //  --------------- TM (Secretary SELF SERVICE MENU) START -----------------------

                                    $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHTM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_TM' AND b.isActive='1' ";
                                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                                        if(odbc_result($StatusHrss,"JUMLAHTM")>0)
                                        {
                                    ?>
                                    <li><a href=""><?php echo $word[50]; // Time Management ?></a>
                                        <ul>
                                                <li><a href="SSS_calendar.php"><?php echo $word[198]; // All Calendar ?></a></li>
                                                <li><a href="SSS_RecapitulationTimeEvent.php"><?php echo $word[186]; // Time Data Recapitulation ?></a></li>
                                                <li><a href="SSS_TimeEvent.php"><?php echo $word[10]; // Time Event ?></a></li>
                                                <li><a href="SSS_sisacuti.php"><?php echo $word[22]; // Leave Balance ?></a></li>
												<li><a href="SSS_ShiftScheduling.php"><?php echo $word[199]; // Shift Scheduling ?></a></li>
                                        </ul>
                                    </li>
                                    <?php
                                    	}
                                    ?>
                                <?php
                                  //} INI DIpindahkan ke line 729
                  // -----------------------------------------------------------------------
                                    //  --------------- TM (Secretary SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
                                    //  --------------- PA (Secretary SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHPA FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_PA' AND b.isActive='1' ";
                                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                                        if(odbc_result($StatusHrss,"JUMLAHPA")>0)
                                        {
                                ?>
                                    <li><a href=""><?php echo $word[168]; // Personal Data ?></a>
                                        <ul>
                                                <li><a href="SSS_Profile.php"><?php echo $word[5]; // Profile ?></a></li>
                                                    </ul>
                                    </li>
<?php
                                  }
                  // -----------------------------------------------------------------------
                                    //  --------------- PA (Secretary SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
                                    //  --------------- COMBEN (Secretary SELF SERVICE MENU) START -----------------------
                            $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHCOMBEN FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_CB' AND b.isActive='1' ";
                                $StatusHrss=odbc_exec($conn,$sqlhrss);
                                if(odbc_result($StatusHrss,"JUMLAHCOMBEN")>0)
                                {
?>
                            <li><a href="">Compensation & Benefit</a>
                                <ul>
                                        <li><a href="HRSS_Medical.php"><?php echo $word[187]; // Medical by Person ?></a></li>
                                        <li><a href="HRSS_Medicalgrup3.php"><?php echo $word[188]; // Medical by Organization ?></a></li>
                                </ul>
                            </li>
<?php
                                  }
                  // -----------------------------------------------------------------------
                                    //  --------------- COMBEN (Secretary SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
                                    //  --------------- OM (Secretary SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHOM FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_OM' AND b.isActive='1' ";
                                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                                        if(odbc_result($StatusHrss,"JUMLAHOM")>0)
                                        {
?>
                                    <li><a href="">Organization Management</a>
                                        <ul>
                                                <li><a href="HRSS_Treechart.php"><?php echo $word[189]; // Tree Chart Organization ?></a></li>
                                        </ul>
                                    </li>
<?php
                                }
                  // -----------------------------------------------------------------------
                                    //  --------------- OM (Secretary SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
                                    //  --------------- MAP (Secretary SELF SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHMAP FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_MAP' AND b.isActive='1' ";
                                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                                        if(odbc_result($StatusHrss,"JUMLAHMAP")>0)
                                        {
?>
                                                                    <li><a href="ER_Search.php"><?php echo $word[190]; //Employee Map ?></a></li>
<?php
                                }
                  // -----------------------------------------------------------------------
                                    //  --------------- MAP (Secretary SELF SERVICE MENU) END -----------------------

                  // -----------------------------------------------------------------------
                                    //  --------------- ROLEADMIN (Secretary SERVICE MENU) START -----------------------
                                $sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAHROLE FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%SSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and a.SpecialName='SSS_ROLE' AND b.isActive='1' ";
                                        $StatusHrss=odbc_exec($conn,$sqlhrss);
                                        if(odbc_result($StatusHrss,"JUMLAHROLE")>0)
                                        {
?>

                                                            <li><a href=""><?php echo $word[200]; // Master Admin ?></a>
                                                                <ul>
                                                                        <li><a href="HRSS_MasterUser.php"><?php echo $word[201]; // Account Management ?></a></li>
                                                                </ul>
                                                            </li>

<?php
                                }
                                    // -----------------------------------------------------------------------
                                    //  --------------- ROLEADMIN (Secretary SELF SERVICE MENU) END -----------------------
?>


                            </ul>
                    <?php

                        }
//                    --------------------- Secretary SELF SERVICE MENU END --------------------
//                    ------------------------------------------------------------------------
                    ?>

			<li><a href=""><?php echo $word[46]; // organization ?></a><ul>
				<li><a href="tablePimpinanKG.php"><?php echo $word[47]; // management KG ?></a></li>
					<li><a href="">Extension</a>
					<ul>
						<li><a href="ExtNumberSearch.php">Employee Extension Number</a></li>
						<li><a href="ExtDirect.php">Direct Line & Building Phone</a></li>
						<?php if ($menuFlag['admin'][19]==1){?>
						<li><a href="ExtNumberUpdate.php">Employee Extension Update</a></li>
						<li><a href="ExtDirectUpdate.php">Direct Line & Building Phone Update</a></li>
						<?php } ?>
					</ul>
					</li>
			</ul></li>
            <li><a href="https://kognisi.mykg.id "><?php echo "Kognisi"; // Kognisi ?></a> </li>
		<!--<ul>
                <li><a href="redirect.php?to=blog">Blog</a></li>
                <li><a href="redirect.php?to=elearning">eLearning</a></li>
                <?php
                //Training Management System
		/*						function is_LnD1($trainer_id){
									include "TMS/connection.php";
									$query ="SELECT COUNT(*) as jmlCtr FROM trm_trainer t WHERE (t.is_Active = 1 AND t.is_LnD = 1 AND t.trainer_code = '$trainer_id')";
									if ($result = odbc_exec($connTMS,$query)){
										return odbc_result($result,1);
									}
									else{
										return -1;
									}
								}
								echo '<li><a href="#">Training</a><ul>';
								if (is_LnD1($NIK)){
									echo '<li><a href="#">Master</a><ul>'; //Master
									echo '<li><a href="Training_AddMasterInstitution.php">Institution</a></li>';
									echo '<li><a href="Training_AddMasterParticipant.php">Participant Level</a></li>';
									echo '<li><a href="Training_AddMasterPersAdmin.php">Pers Admin</a></li>';
									echo '<li><a href="Training_AddMasterProgram.php">Program</a></li>';
									echo '<li><a href="Training_AddMasterTrainer.php">Trainer</a></li>';
									echo '</ul></li>';
								}
								echo '<li><a href="#">Training Catalog</a><ul>'; //Training Catalog
								echo '<li><a href="Training_AddTrainingCatalog.php">Catalog</a></li>';
								if (is_LnD1($NIK)){
									echo '<li><a href="Training_AddTrainingSchedule.php">Schedule</a></li>';
								}
								echo '</ul></li>';
								echo '<li><a href="#">Training Request</a><ul>'; //Training Request
								echo '<li><a href="Training_RequestForm1.php">Request</a></li>';
								echo '<li><a href="Training_RequestStatus.php">Request Status</a></li>';
								echo '<li><a href="Training_RequestHistory.php">Request History</a></li>';
								echo '</ul></li>';
								echo '<li><a href="#">Training Approval</a><ul>'; //Training Approval
								echo '<li><a href="Training_ApprovalStatus.php">Approval Status</a></li>';
								echo '<li><a href="Training_ApprovalHistory.php">Approval History</a></li>';
								echo '</ul></li>';
								if (is_LnD1($NIK)){
									echo '<li><a href="Training_ParticipantList.php">Participant List</a></a>'; //Participant List
									echo '</li>';
									echo '<li><a href="#">Evaluation</a><ul>'; //Evaluation
									echo '<li><a href="Training_InputBAP.php">BAP Input</a></li>';
									echo '<li><a href="Training_InputTrainingIndex.php">Training Index Input</a></li>';
									echo '<li><a href="Training_InputFacilitatorIndex.php">Facilitator Index Input</a></li>';
									echo '</ul></li>';
									echo '<li><a href="#">Report</a><ul>'; //Report
									echo '<li><a href="Training_ReportBAP.php">BAP Report</a></li>';
									echo '<li><a href="Training_ReportTrainingIndex.php">Training Index Report</a></li>';
									echo '<li><a href="Training_ReportFacilitatorIndex.php">Facilitator Index Report</a></li>';
									echo '<li><a href="Training_ReportTeachingHours.php">Teaching Hours Report</a></li>';
									echo '</ul></li>';
								}
								echo '</ul></li>'; */
								//Training Management System
                ?>
		</li>
            </ul></li>End of Knowledge -->
			<!-- <li><a href="forum-close.php"><?php echo $word[48]; // forum ?></a></li> -->
			<!--<li><a href="bestEmployee.php"><?php echo $word[49]; // best employee ?> <img src="img/icon/star.gif" width="18" height="18" border="0" /></a></li>-->
			<li><a href=""><?php echo $word[50]; // time event ?></a><ul>
				<li><a href="timeEventStatus.php"><?php echo $word[10]; ?></a></li>
				<!--<li><a href="underMaintenance.php"><?php echo $word[10]; ?></a></li>-->
				<li><a href="uangMakan.php"><?php echo $word[24]; ?></a></li>

				<li><a href="uangLembur.php"><?php echo $word[51]; ?></a></li>
				<li><a href=""><?php echo $word[52]; // attendance ?></a><ul>
					<li><a href=""><?php echo $word[53]; // history ?></a><ul>
						<li><a href="reqAttendanceHistoryStatus.php" ><?php echo $word[54]; //Attendance History ?></a></li>
						<li><a href="reqAttendanceTransactionHistory.php" ><?php echo $word[55]; //Attendance Transaction History ?></a></li>
						<li><a href="reqAttendanceCancelHistory.php" ><?php echo $word[56]; //Attendance Cancelation History ?></a></li>
					</ul></li>
					<li><a href="" ><?php echo $word[57]; // request ?></a><ul>
						<!--<li><a href="underMaintenance.php" ><?php echo $word[58]; //Attendance Request ?></a></li>-->
						<li><a href="reqAttendance.php" ><?php echo $word[58]; //Attendance Request ?></a></li>
						<li><a href="reqAttendanceCancelBefore.php" ><?php echo $word[59]; //Attendance Cancelation Before Approve ?></a></li>
					</ul></li>
				<li><a href="" ><?php echo $word[60]; //Request Status ?></a><ul>
					<li><a href="reqAttendanceStatus.php" ><?php echo $word[61];//Attendance Request Status ?></a></li></ul></li>
					<?php
				/*	$sqlah="select * from tr_login a inner join ms_ModuleAdmin b on a.userLogin =b.UserLogin where
												b.UserLogin ='$NIK'
												and ModuleID = 5 and SpecialID = 6 and
												b.isActive=1 and a.isActive=1";
					$StatusAttendanceHRQry=odbc_exec($conn,$sqlah);
					if(odbc_result($StatusAttendanceHRQry,"userLogin")==$NIK)
					{
						echo '<li><a href="reqAttendanceListPendingApprove.php" ><img src="img/icon/alert.png" />'.$word[62].'</a></li>'; //Attendance List Pending Approval
					}	*/
				?>

			<?php
			$queryAttendance   = "SELECT FirstApproverNIK, SecondApproverNIK, ThirdApproverNIK from tb_AttendanceTransaction WHERE (FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK' OR ThirdApproverNIK='$NIK')";
			$queryAttendanceResult  = odbc_exec($conn, $queryAttendance);
			$att_1 = odbc_result($queryAttendanceResult,1);
			$att_2 = odbc_result($queryAttendanceResult,2);
			$att_3 = odbc_result($queryAttendanceResult,3);
			if (count($queryAttendanceResult) && ($att_1 == $NIK || $att_2 == $NIK || $att_3 == $NIK)) {
				echo '<li><a href="">'.$word[63].'</a><ul>'; //Approval
				echo '<li><a href="reqAttendanceApproval.php" >'.$word[64]; //Approval Request
				//echo '<li><a href="underMaintenance.php" >'.$word[64]; //Approval Request
				$rs = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tb_AttendanceTransaction where ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL OR '$NIK'= SecondApproverNIK AND isSecondApproved is NULL OR '$NIK'= ThirdApproverNIK AND isThirdApproved is NULL)");
				$arr = odbc_fetch_array($rs);
				$arr['counter'];
				$totalAttendance = $arr['counter'];
				if($totalAttendance != 0){
					echo '<font class="redStrong"> ('.$totalAttendance .')</font>';
				}
				echo '</a></li>';
				echo '<li><a href="reqAttendanceApprovalHis.php">'.$word[65].'</a></li>'; //Approval History
				echo '</ul></li>';
			}
			?>
			</ul></li>
			<li><a href=""><?php echo $word[66]; ?></a> <ul><?php	//SKKL
			$StatusSuperiorQry=odbc_exec($conn,"SELECT FirstApproverNIK,SecondApproverNIK FROM tb_SKKLTransaction where ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL OR '$NIK'= SecondApproverNIK AND isSecondApproved is NULL) AND isApproved is null");
			$skkl_1 = odbc_result($StatusSuperiorQry,1);
			$skkl_2 = odbc_result($StatusSuperiorQry,2);
				// TUTUP DULU SAMPAI TGL 10
				if (count($StatusSuperiorQry) && ( $skkl_1 == $NIK || $skkl_2 == $NIK)){
					echo '<li><a href="skklStatusSuperior.php" >'.$word[67]; //SKKL Status Superior
					$GetTotalSpvNikLmbr=odbc_exec($conn,"SELECT COUNT(*) AS Total from dbo.tb_SKKLTransaction
										where ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL
										OR '$NIK'= SecondApproverNIK AND isSecondApproved is NULL) AND isApproved is null");
					if (odbc_result($GetTotalSpvNikLmbr,"Total") !=0){
						$totaldataApproveSKKL = odbc_result($GetTotalSpvNikLmbr,"Total");
						echo '<font class="redStrong"> ('.$totaldataApproveSKKL .') </font>';
					}
					echo '</a></li>';
				}
				// TUTUP DULU
				
				$StatusAtasanQry=odbc_exec($conn,"select * from tr_login where userLogin = '$NIK'");
				if (odbc_result($StatusAtasanQry,"ModuleRoleID") == 2 ){
					// echo '<li><a href="underMaintenance.php" >'.$word[70].'</a></li>'; //Create SKKL
					// tutup dulu karena bikin lemot
					echo '<li><a href="skklCreate.php" >'.$word[70].'</a></li>'; //Create SKKL
					echo '<li><a href="skklStatusAtasan.php">'.$word[68].'</a></li>'; //SKKL Status Assignment
				}
				$StatusAtasanReportQry=odbc_exec($conn,"select * from ms_ModuleAdmin where ModuleID =4 and UserLogin = '$NIK'");
				if (odbc_result($StatusAtasanReportQry,"isActive") == true ){
					echo '<li><a href="skklReport.php?flag=0" >'.$word[71].'</a></li>'; //SKKL Report
				}

			?>
				<li><a href="skklStatusBawahan.php" ><?php echo $word[69]; //SKKL Status ?>
			<?php
				$sqlSKKLAccept = "select count(*) as total from tb_SKKLTransaction where NIK='$NIK' AND isApproved='true' and Accepted is NULL";
				$GetTotal=odbc_exec($conn,$sqlSKKLAccept);
				$totaldata2 = odbc_result($GetTotal,"total");
				if ($totaldata2 !=0){
					echo '<font class="redStrong"> ('.$totaldata2.') </font>';
				}
			?></a></li>
				<li><a href="skklRekapLembur.php" ><?php echo $word[72]; //SKKL Rekap ?></a></li>

			</ul></li><!--End of SKKL-->
			<li><a href="#"><?php echo $word[73]; //Absence ?></a><ul>
				<li><a href="absenceStatus.php"><?php echo $word[74]; //View Status ?></a></li>
				<li><a href="absenceRequest1.php"><?php echo $word[57]; //Request ?></a></li>
				<!--<li><a href="underMaintenance.php"><?php echo $word[57]; //Request ?></a></li>-->
				<li><a href="absenceRequestStatus.php"><?php echo $word[60]; //Request Status ?></a></li>
				<li><a href="#"><?php echo $word[75]; //Cancelation ?></a><ul>
					<li><a href="absenceCancelReqBefore.php"><?php echo $word[76]; //Before Approved?></a></li>
				</ul></li>
				<li><a href="#"><?php echo $word[53]; //History?></a><ul>
					<li><a href="absenceHistory.php"><?php echo $word[77]; //Absence History?></a></li>
					<li><a href="absenceCancelHistory.php"><?php echo $word[78]; //Cancelation History?></a></li>
				</ul></li>
				<?php
				$queryAdis   = "SELECT FirstApproverNIK, SecondApproverNIK,ThirdApproverNIK
				from tr_Absence WHERE
									(AbsenceType != 1001 AND AbsenceType != 1002 AND AbsenceType != 1003) AND (FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK' OR ThirdApproverNIK='$NIK')";
				$queryAdisResult  = odbc_exec($conn, $queryAdis);
				$abs_1 = 	odbc_result($queryAdisResult,1);
				$abs_2 = 	odbc_result($queryAdisResult,2);
				$abs_3 = 	odbc_result($queryAdisResult,3);

				if ( count($queryAdisResult) && ($abs_1  == $NIK || $abs_2  == $NIK || $abs_3 == $NIK )) {
					echo '<li><a href="#">'.$word[63].'</a><ul>'; //Approval
					echo '<li><a href="absenceRequestApproval.php">'.$word[63]; //Approval
					//echo '<li><a href="underMaintenance.php">'.$word[63]; //Approval
					$rs = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tr_Absence where (AbsenceType != 1001 AND AbsenceType != 1002 AND AbsenceType != 1003) AND ('$NIK'= FirstApproverNIK AND isFirstApproved is NULL OR '$NIK'= SecondApproverNIK AND isSecondApproved is NULL OR '$NIK'= ThirdApproverNIK AND isThirdApproved is NULL)");
					$totalAbsenceCuti = odbc_result($rs, 1);
					if ($totalAbsenceCuti == 0){
						echo "";
					} else{
						echo '<font class="redStrong"> ('. $totalAbsenceCuti.')</font>';

					}
					echo '</a></li>';
					echo '<li><a href="absenceRequestApprovalHis.php">'.$word[65].'</a></li>'; //Approval History
					echo '</ul></li>';
				}
				?>

				<?php
/*					$sqlah="select * from tr_login a inner join ms_ModuleAdmin b on a.userLogin =b.UserLogin where
												b.UserLogin ='$NIK'
												and ModuleID = 5 and SpecialID = 6 and
												b.isActive=1 and a.isActive=1";
					$StatusLeaveHRQry=odbc_exec($conn,$sqlah);
					if(odbc_result($StatusLeaveHRQry,"userLogin")==$NIK)
					{
						echo '<li><a href="absenceListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[79].'</a></li>'; //Absence List Pending Approval
					}	*/
				?>

			</ul></li><!--End of Absence-->


			<li><a href="#"><?php echo $word[80]; //Leave ?></a><ul>
				<li><a href="leaveStatus.php" ><?php echo $word[74]; //View Status ?></a></li>
				<li><a href="reqCuti_1.php" ><?php echo $word[57]; //Request ?></a></li>
				<!--<li><a href="underMaintenance.php" ><?php echo $word[57]; //Request ?></a></li>-->
				<li><a href="reqCutiStatus.php" ><?php echo $word[60]; //Request Status ?></a></li>
				<li><a href="#"><?php echo $word[75]; //Cancelation ?></a><ul>
					<li><a href="cancelReqBefore.php" ><?php echo $word[76]; //Before Approved ?></a></li>
					<li><a href="cancelReqAfter.php" ><?php echo $word[81]; //After Approved ?></a></li>
					<li><a href="cancelReqStatus.php" ><?php echo $word[82]; //Cancelation Status ?></a></li>
				</ul></li>
				<li><a href="#"><?php echo $word[53]; //History ?></a><ul>
					<li><a href="reqCutiHistory.php" ><?php echo $word[83]; //Leave History ?></a></li>
					<li><a href="reqCutiCancelHistory.php" ><?php echo $word[78]; //Cancelation History ?></a></li>
				</ul></li>

				<?php
					/*$sqlah="select * from tr_login a inner join ms_ModuleAdmin b on a.userLogin =b.UserLogin where
												b.UserLogin ='$NIK'
												and ModuleID = 5 and SpecialID = 6 and
												b.isActive=1 and a.isActive=1";
					$StatusLeaveHRQry=odbc_exec($conn,$sqlah);
					if(odbc_result($StatusLeaveHRQry,"userLogin")==$NIK)
					{
						echo '<li><a href="reqCutiListPendingApprove.php" ><img src="img/icon/alert.png" border="none" />'.$word[84].'</a></li>'; //Leave List Pending Approval
					}	*/
				?>

				<?php
				$queryAdis="SELECT  FirstApproverNIK,SecondApproverNIK,  ThirdApproverNIK from tr_Absence WHERE (AbsenceType = '1001' or AbsenceType = '1002' or AbsenceType = '1003') AND FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK' OR ThirdApproverNIK='$NIK'";
				$queryAdisResult  = odbc_exec($conn, $queryAdis);
				$queryAdis2="SELECT FirstApproverNIK,SecondApproverNIK,  ThirdApproverNIK from tr_AbsenceCancel WHERE (AbsenceType = '1001' or AbsenceType = '1002' or AbsenceType = '1003') AND FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK' OR ThirdApproverNIK='$NIK'";
				$queryAdisResult2  = odbc_exec($conn, $queryAdis2);
				$leave_1 = odbc_result($queryAdisResult,1);
				$leave_2 = odbc_result($queryAdisResult,2);
				$leave_3 = odbc_result($queryAdisResult,3);
				$lvc_1 = odbc_result($queryAdisResult2,1);
				$lvc_2 = odbc_result($queryAdisResult2,2);
				$lvc_3 = odbc_result($queryAdisResult2,3);

				if ( $leave_1== $NIK || $leave_2 == $NIK || $leave_3 == $NIK || $lvc_1 == $NIK ||$lvc_2 == $NIK || $lvc_3 == $NIK ){
					echo '<li><a href="#">'.$word[63].'</a><ul>'; //Approval
					//echo '<li><a href="underMaintenance.php">'.$word[63]; //Approval
					echo '<li><a href="reqCutiApproval.php">'.$word[63]; //Approval
					$rs = odbc_exec($conn, "SELECT COUNT(*) AS counter from dbo.tr_Absence where (AbsenceType = '1001' or AbsenceType = '1002' or AbsenceType = '1003') AND ('".$NIK."'= FirstApproverNIK AND isFirstApproved is NULL OR '".$NIK."'= SecondApproverNIK AND isSecondApproved is NULL OR '".$NIK."'= ThirdApproverNIK AND isThirdApproved is NULL)");
					$arr = odbc_fetch_array($rs);
					if ($arr['counter'] == 0){
						echo "";
					}else{
						echo '<font class="redStrong"> ('.$arr['counter'];
						$totalCutiApp= $arr['counter'];
						echo ')</font> ';
					}
					echo '</a></li>';
					echo '<li><a href="reqCutiApprovalHis.php">'.$word[65].'</a></li>'; //Approval History
					echo '<li><a href="reqCutiApprovalCancelReq.php">'.$word[86]; //Cancelation Request Approval
					$sqlCRA ="select COUNT(*) AS counter from dbo.tr_AbsenceCancel where isApproved is not null AND ('$NIK'= FirstApproverNIK AND isFirstApproved ='1' OR '$NIK'= SecondApproverNIK AND isSecondApproved ='1' OR '$NIK'= ThirdApproverNIK AND isThirdApproved ='1')";
					$totalCutiCancel = odbc_result(odbc_exec($conn, $sqlCRA),1);
					if ($totalCutiCancel==0){
						echo '';
					}else{
						echo '<font class="redStrong"> ('.$totalCutiCancel.')</font>';
					}
					echo '</a></li>';
					$queryAdis2   ="SELECT FirstApproverNIK, SecondApproverNIK, ThirdApproverNIK from tr_AbsenceCancel WHERE FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK' OR ThirdApproverNIK='$NIK'";
					$queryAdisResult2  = odbc_exec($conn, $queryAdis2);
					$lvc_1 = odbc_result($queryAdisResult2,1);
					$lvc_2 = odbc_result($queryAdisResult2,2);
					$lvc_3 = odbc_result($queryAdisResult2,3);
					if ($lvc_1 == $NIK || $lvc_2 == $NIK || $lvc_3 == $NIK){
						echo '<li><a href="reqCutiApprovalCancelHis.php">'.$word[87].'</a></li>';

					}
					echo '</ul></li>';

				}

				?>
			</ul></li><!--End of Leave-->
			<li><a href="Sub_SubtitutionRequestForm1.php"><?php echo $word[88]; //Substitution ?></a><ul>
			<li><a href="Sub_SubtitutionRequestForm1.php"><?php echo $word[57]; //Request ?></a><ul>
				<li><a href="Sub_SubtitutionRequestForm1.php"><?php echo $word[89]; //Request Form ?></a></li>
				<li><a href="Sub_SubtitutionRequestListStatus.php"><?php echo $word[74]; //View Status ?></a></li>
				<li><a href="Sub_SubtitutionRequestHistory.php"><?php echo $word[90]; //Request History ?></a></li>
			</ul></li>
			<li><a href="Sub_SubtitutionRequestConfirmationList.php"><?php echo $word[91]; //Confirmation List ?>
				<?php
					$sqlSubt1 = "SELECT COUNT(*) from Sub_SubtitutionTransaction WHERE FriendNIK = '$NIK' AND IsFriendAccepted = 0";
					$countSubt1 = odbc_result(odbc_exec($conn,$sqlSubt1), 1);
					if ($countSubt1!=0){
						echo ' ('.$countSubt1.')';
					}
				?>
			</a></li>
			<li><a href="Sub_SubtitutionApprovalList.php"><?php echo $word[63]; //Approval ?></a><ul>
			<!--<li><a href="Sub_SubtitutionApprovalList.php">Approval List-->
					<?php
						$sqlSubt2 = "SELECT COUNT(*) FROM Sub_SubtitutionTransaction WHERE (FirstApproverNIK = '$NIK' AND IsFirstApproved NOT IN('1','2') OR SecondApproverNIK = '$NIK' AND IsSecondApproved NOT IN('1','2') OR ThirdApproverNIK = '$NIK' AND IsThirdApproved NOT IN('1','2')) AND IsFriendAccepted = 1";
						$countSubt2 = odbc_result(odbc_exec($conn,$sqlSubt2), 1);
						if ($countSubt2!=0){
							echo '<li><a href="Sub_SubtitutionApprovalList.php">'.$word[92]; //Approval List
							echo ' ('.$countSubt2.')';
							echo '</a></li>';
						}
					?>

				<li><a href="Sub_SubtitutionApprovalHistory.php"><?php echo $word[65]; //Approval History ?></a></li>
				</ul></li>
		</ul></li><!--End of Substitution-->
		<?php if($menuFlag['admin'][16]==1){?>
			<li><a href="#"><?php echo $word[93]; //Online Presence Module ?></a><ul>
				<li><a href="OPS_Terminal.php"><?php echo $word[94]; //Terminal ?></a></li>

			</ul></li><!--End of Online Presence Module / OPM-->
		<?php } ?>
		</ul></li><!--End of Time Management-->
		<li><a href=""><?php echo $word[95]; //Manpower Statistic ?></a><ul><?php
			if ($menuFlag['view'][1]==1){
				echo '<li><a href="99-MPSViewData.php" >View Data</a></li>';
			}
			?>
		<?php
			if ($menuFlag['admin'][1]==1 and isset($menuFlag['special'][1][1])==1){
				echo '<li><a href="MPSAdmin.php">Admin Area</a></li>';
			}
			if ($menuFlag['admin'][1]==1 and isset($menuFlag['special'][1][2])==1){
				echo '<li><a href="MPSAdminApproval.php">Approval</a></li>';
			}
			/*$queryAdis   = "select * from ms_ModuleAdmin WHERE UserLogin='$NIK' AND ModuleID='1' AND SpecialID='1' AND isActive='True'";
			$queryAdisResult  = odbc_exec($conn, $queryAdis);
			if (odbc_result($queryAdisResult,"UserLogin") == $NIK){
				echo '<li><a href="MPSAdmin.php">Admin Area</a></li>';
			}
			$queryAdis   = "select * from ms_ModuleAdmin WHERE UserLogin='$NIK' AND ModuleID='1' AND SpecialID='2' AND isActive='True'";
				$queryAdisResult  = odbc_exec($conn, $queryAdis);
			if (odbc_result($queryAdisResult,"UserLogin") == $NIK){
				echo '<li><a href="MPSAdminApproval.php">Approval</a></li>';
			}*/
		?>
		</ul></li> <!--End of MPS-->
		<li>
		<a href="#"><?php echo $word[96]; //Employees Info ?></a>
		<ul>
		<?php
			$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
                        $md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));
    echo "<li><a href='https://".$_SERVER['HTTP_HOST']."/hr1/index.php/account/login_transfer/".$NIK."/".$md5_password."/EN/emp_search%2Femp_search'>Employee Search</a></li>";
			if ($menuFlag['view'][1]==1)
			{
		?>
					<li><a href="employee_in.php" ><?php echo $word[97]; //New Employee ?></a></li>
			<li><a href="99-employeePermanent.php" ><?php echo $word[98]; //Employee Permanent ?></a></li>
			<li><a href="99-employeeOUT.php" ><?php echo $word[99]; //Employee Termination ?></a></li>
			<li><a href="99-employeeAbsence.php" ><?php echo $word[100]; //Employee Absence/ Leave ?></a></li>
		<?php
			}
		?>
		<li><a href="99-employeeBday.php" ><?php echo $word[101]; //Employee Birthday ?></a></li>
		</ul>
		</li><!--End of Employees Info-->

                <?php
//                PENGECEKAN KHUSUS UNTUK TRIBUN dan CIKARANG TIDAK KELUAR MENU REMUNERATION

                $sqlchecktribun="select COUNT(*) as JUMLAH from ms_niktelp "
                        . "where (PersAdmin >= '026' and PersAdmin <='045') and NIK ='$NIK'";
//                $sqlcheckcikarang="select COUNT(*) as JUMLAH from ms_niktelp "
//                        . "where PersArea='0049' and NIK ='$NIK'";
						$Statustribun=odbc_exec($conn,$sqlchecktribun);
//                                                $Statuscikarang=odbc_exec($conn,$sqlcheckcikarang);
						if(odbc_result($Statustribun,"JUMLAH")==0 )
						{
		?>
                <li><a href=""><?php echo $word[102]; //Remuneration ?></a>
				<ul>
						<li><a href="Travel.php"><?php echo 'Travel'; ?></a></li>
						<?php if($NIK == '005290' || $NIK == '068101' || $NIK == '052962' || $NIK == '000909' || $NIK == '000349' || $NIK == '002367' || $NIK == '003906'){?>
                                                <li><a href="#" ><?php echo 'Travel Online'; //Medical ?></a>
						<ul>
							<li><a href="TravelOnline_req_1.php" ><?php echo 'Travel Online Request';?></a></li>
							<li><a href="TravelOnline_History.php" ><?php echo 'Travel Online History';?></a></li>
                                                        <li><a href="TravelOnline_History_Atasan.php" ><?php echo 'Travel Online History Subordinate';?></a></li>
                                                        <li><a href="TravelOnline_pasca_list.php" ><?php echo 'Travel Online Post Travel';?></a></li>
						</ul>
                                                <?php
						}
                                                $sqlcheckhospitalreimburse="select COUNT(*) as JUMLAH from ms_niktelp "
                                                        . "where (PersArea='0141' "
                                                        . "OR (PersArea='0049' AND SubArea='1753')"
                                                        . "OR (PersArea='0049' AND SubArea='1755')"
                                                        . "OR (PersArea='0050' AND SubArea='1028')"
                                                        . "OR (PersArea='0050' AND SubArea='1029')"
                                                        . "OR (PersArea='0050' AND SubArea='1531')"
                                                        . "OR (PersArea='0050' AND SubArea='1532')"
                                                        . "OR (PersArea='0002' AND SubArea='8011')"
                                                        . ") AND NIK='$NIK'";

                                                $Statusmedicalreimburse=odbc_exec($conn,$sqlcheckhospitalreimburse);
//                                                $Statuscikarang=odbc_exec($conn,$sqlcheckcikarang);
//						if(odbc_result($Statusmedicalreimburse,"JUMLAH")==0 )
//                                                {
                                                ?>
						<li><a href="#" >Medical</a>
						<ul>
							<li><a href="medical.php" ><?php echo $word[103]; //Medical Reimburse ?></a></li>
							<li><a href="hospital_reimburse.php" >Hospital Reimburse</a></li>
							<li><a href="plafon_medical.php" >Plafon Medical</a></li>

						</ul>
                                                <?php
//                                                }
                                                ?>


                <!----------------------- payroll ul start ---------------------------------------->
                                                <?php
                $cekloginpayrollstring="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE (PayArea = 'KA'
                                                and status='Permanent'
                                                and PersAdmin='006'
                                                AND PayAdmin='901' AND	NIK='$NIK')
                                            OR (PayArea='KF' and PersAdmin='017' and PersArea ='0004' AND NIK='$NIK')        
                                            OR (PayArea = 'KA'
                                                and status='Permanent'
                                                and PersAdmin='008'
                                                AND PayAdmin='901'
                                                AND PersArea='0013' AND	NIK='$NIK')
                                            OR
                                                        (PayArea = 'KA'
                                                        and status='Permanent'
                                                        and PersAdmin='009'
                                                        AND PayAdmin='901'
                                                        AND (PersArea='0017' OR PersArea='0140') AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KC'
                                                        and status='Permanent'
                                                        and PersAdmin='002'
                                                        AND PayAdmin='911' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KC'
                                                        and status='Permanent'
                                                        and PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')
                                                        AND PayAdmin='911' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KX'
                                                        and status='Contract'
                                                        and PersAdmin between '160' and '291'
                                                        AND NIK='$NIK')            
                                                OR
                                                        (PayArea = 'KA'
                                                and status='Permanent'
                                                and PersAdmin='009'
                                                AND PayAdmin='901'
                                                AND (PersArea='0017' OR PersArea='0140') AND	NIK='$NIK')
                                            OR
                                                        (PayArea = 'KA'
                                                        and status='Permanent'
                                                        and PersAdmin='008'
                                                        AND PayAdmin='901'
                                                        AND PersArea='0013' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KA'
                                                        and status='Permanent'
                                                        and PersAdmin='006'
                                                        AND PayAdmin='901' AND	NIK='$NIK')
                                                OR
                                                		(PayArea = 'KB'
                                                        and (PersArea = '0023' or PersArea='0060' or PersArea='0245') AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KA'
                                                        and (status='Permanent' or status='Contract')
                                                        and PersAdmin='009'
                                                        AND PayAdmin='901'
                                                        AND (PersArea='0017' OR PersArea='0140') AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KA'
                                                        and (status='Permanent' or status='Contract')
                                                        and PersAdmin='008'
                                                        AND PayAdmin='901' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KA'
                                                        and (status='Permanent' or status='Contract')
                                                        and PersAdmin='006'
                                                        AND PayAdmin='901' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KC'
                                                        and (status='Permanent' or status='Contract')
                                                        and PersAdmin='022'
                                                        AND PayAdmin='911' AND	NIK='$NIK')
                                                OR
                                                        (PayArea = 'KX'
                                                        and (status='Permanent' or status='Contract')
                                                        and PersAdmin='022'
                                                        AND PayAdmin='914' AND	NIK='$NIK')            
                                                OR
                                                        (PayArea = 'KC'
                                                        and status='Permanent'
                                                        and PersAdmin='002' AND PayAdmin='911' AND	NIK='$NIK')
                                                OR
                                                        (status='Contract'
                                                        AND PayAdmin='914' AND	NIK='$NIK')
						OR
                                                        (PayArea = 'KC'
                                                        and status='Permanent'
                                                        and PersAdmin='006' AND PayAdmin='911' AND      NIK='$NIK')                   
                                                OR
                                                        (PayArea = 'KE'
                                                        and (status='Permanent' or status='Contract')
                                                        and (PersAdmin='010' or PersAdmin='009') AND PayAdmin='906' AND NIK='$NIK')"
                        /*. "                     OR
                                                        (PayArea = 'KF'
                                                        and (status='Permanent' or status='Contract')
                                                        AND PayAdmin='904' AND NIK='$NIK')"*/
                        . "                     OR
                                                        (PayArea = 'KF'
                                                        and (status='Permanent' or status='Contract')
                                                        AND PayAdmin='925' AND NIK='$NIK')"

                        ."                      OR 		
				                                        (PayArea = 'KD'
				                                        and (status='Permanent' or status='Contract')
				                                        and (PersArea='0276' or PersArea='0277' or PersArea='0278' or PersArea='0275' or PersArea='1003' or PersArea='0003' or PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
				                                        and (PersAdmin='012')
				                                        AND PayAdmin='908'
				                                        AND NIK='$NIK')

				                                OR
				                                		(PayArea = 'KL'
				                                        and (status='Permanent' or status='Contract')
				                                        and (PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
				                                        and (PersAdmin='012')
				                                        AND PayAdmin='924'
				                                        AND NIK='$NIK')

				                                OR
				                                		(PayArea = 'KH'
				                                        and (status='Permanent' or status='Contract')
				                                        and (PersArea IN('0268'))
				                                        and (PersAdmin='004')
				                                        AND PayAdmin='926'
				                                        AND NIK='$NIK')

						                        OR
				                                        (PayArea = 'KF'
				                                        and (status='Permanent' or status='Contract')
				                                        and PersAdmin='010'
				                                        AND PayAdmin='921'
				                                        AND NIK='$NIK')

				                                OR
				                                        (PersArea
				                                        in(
				                                        /*PT. SIRKULASI KOMPAS GRAMEDIA
				                                        PT. TRANSITO ADIMAN JATI
				                                        PT. RADIO SAFARI BINA BUDAYA
				                                        PT. RADIO SONORA
				                                        PT. RADIO SMART MEDIA UTAMA
				                                        PT. KOMANDO MEDIA TELEVISI
				                                        PT. CIPTA MEGASWARA TELEVISI
				                                        KGXPRESS*/
				                                        '0004',
				                                        '0024',
				                                        '0026',
				                                        '0034',
				                                        '0058',
				                                        '0057',
				                                        '0112')
				                                        AND NIK='$NIK')
				                                ";
                            
                       
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");
                
//                $cekloginpayrollstring;
                
                if($payrolllinkakses==1)
                {
                    echo '<li><a href="#" >Payroll</a>';
                }

                                                ?>
						<ul>
                                                    <?php
                //-----------------------------   E SLIP START -----------------------------------
                //E-SLIP KARYAWAN GORP Contract

                $cekloginpayrollstring="SELECT COUNT(*) as pa "
                                        . "FROM ms_niktelp "
                                        . "WHERE status='Contract' AND "
                                        . "PayAdmin='914' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }
                
                //E-SLIP KARYAWAN SIRKULASI KOMPASGRAMEDIA

                $cekloginpayrollstring="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PersAdmin='017' and PersArea ='0004' and PayArea='KF' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }

                //E-SLIP KARYAWAN SIRKULASI KOMPASGRAMEDIA BARU
                $cekloginpayrollstring="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PersAdmin='007' and PersArea ='0004' and PayArea='KF' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }

                //E-SLIP KARYAWAN GORP PERMANENT

                $cekloginpayrollstring="SELECT COUNT(*) as pa "
                                        . "FROM ms_niktelp "
                                        . "WHERE PayArea = 'KC' and status='Permanent' and (PersAdmin='002' OR PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')) AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }

               //E-SLIP KARYAWAN GRAMEDIA.COM TETAP

                $cekloginpayrollstring_gdn="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
					(PayArea = 'KC' or PayArea='KF')
                                        and PersAdmin='022' AND (PayAdmin='911' OR PayAdmin='925') AND NIK='$NIK'";
                $queryceklogin_gdn = odbc_exec($conn,$cekloginpayrollstring_gdn);
                $payrolllinkakses_gdn = odbc_result($queryceklogin_gdn,"pa");

                if($payrolllinkakses_gdn==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }
                
                
                //E-SLIP KARYAWAN GRAMEDIA.COM KONTRAK

                $cekloginpayrollstring_gdn="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
					PayArea = 'KX'
                                        and status='Kontrak'
                                        and PersAdmin='022' AND PayAdmin='914' AND NIK='$NIK'";
                $queryceklogin_gdn = odbc_exec($conn,$cekloginpayrollstring_gdn);
                $payrolllinkakses_gdn = odbc_result($queryceklogin_gdn,"pa");

                if($payrolllinkakses_gdn==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }


                //E-SLIP KARYAWAN KCM (KOMPAS CYBER MEDIA)

                $cekloginpayrollstring2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='006'
                                                AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }



                //E-SLIP KARYAWAN KONTAN

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='008'
                                                AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                    <?php
                }




                // E-SLIP KARYAWAN OTOMONTIR dan ANGKASA

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='009'
                                                AND PayAdmin='901'
                                                AND (PersArea='0017' OR PersArea='0140') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }
                
                 //GDN YANG TDNYA GORP

                /*$cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KF'
                                                and (status='Permanent' or status='Contract')
                                                AND PayAdmin='904'
                                                AND PersArea='0142' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }*/





                //E-SLIP  KARYAWAN GKU

//		$cekloginpayrollstring2="select COUNT(*) as pa from ms_niktelp where PayArea = 'KB' and (PersArea = '0023' or PersArea='0060') AND NIK='$NIK'";
//                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
//                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");
//
//                if($payrolllinkakses2==1)
//                {
//                
//                }
                
                
                //E-SLIP  KARYAWAN BOLA or GOM majalah
                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KE'
                                                and (status='Permanent' or status='Contract')
                                                and (PersAdmin='009' or PersAdmin='010')
                                                AND PayAdmin='906'
                                                AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }


                //E-SLIP GOMAN
                $cekloginpayrollstring2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KD'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea='0276' or PersArea='0277' or PersArea='0278' or PersArea='0275' or PersArea='0003' or PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='908'
                                        AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }

                //E-Slip Printing KL  
                
     			$cekloginpayrollstring_printing="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KL'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='924'
                                        AND NIK='$NIK'";
                $queryceklogin_printing = odbc_exec($conn,$cekloginpayrollstring_printing);
                $payrolllinkakses_printing = odbc_result($queryceklogin_printing,"pa");

                if($payrolllinkakses_printing==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                	<?php
                }


			    //E-slip KG Pro KH  
			                
			    $cekloginpayrollstring_kgpro="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KH'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0268'))
                                        and (PersAdmin='004')
                                        AND PayAdmin='926'
                                        AND NIK='$NIK'";
                $queryceklogin_kgpro = odbc_exec($conn,$cekloginpayrollstring_kgpro);
                $payrolllinkakses_kgpro = odbc_result($queryceklogin_kgpro,"pa");

                if($payrolllinkakses_kgpro==1)
                {
                    ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                	<?php
                }

                //E-SLIP KOMPAS KB (sementara)
                $cekloginpayrollstring2="SELECT count(a.NIK) as pa FROM ms_niktelp a, ms_EmpOrg b
                                        WHERE
											'00'+a.NIK = b.NIK
											and b.COCODE = '1245'
											and
                                                a.PayArea = 'KB'
                                                and (a.status='Permanent' or a.status='Contract')
                                                and (a.PersAdmin='007')
                                                and a.PersArea = '0245'
                                                AND a.PayAdmin='902'
                                                AND a.NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2>0)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }
                
                
                
                //E-SLIP SPESIAL CASE MAS FANKY
                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KF'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='024'
                                                AND PayAdmin='925'
                                                AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }


                //E-SLIP GDN Persadmin 010
                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KF'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='010'
                                                AND PayAdmin='925'
                                                AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }


                //E-SLIP GDN Persadmin 010 921
                $cekloginpayrollstring3="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KF'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='010'
                                                AND PayAdmin='921'
                                                AND NIK='$NIK'";
                $queryceklogin3 = odbc_exec($conn,$cekloginpayrollstring3);
                $payrolllinkakses3 = odbc_result($queryceklogin3,"pa");

                if($payrolllinkakses3==1)
                {
                ?>
                    <li><a href="payrolllink.php">Payroll</a></li>
                <?php
                }


                //E-SLIP KM Odoo 9 PT
                
        $cekloginpayrollstring_odoo="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KM'
                                        AND PersArea
                                        in(
                                        /*Metrogema,Amaris Internasional Management,Amaris Internasional,
                                        Grahawita Santika,Tribun Digital Online,Multimedia Digital Nusantara,Yayasan Multimedia Nusantara,
                                        Indopersda,Santika Mitra Samaya,*/
                                        '0016','0042','0044','0025','0059','0288','0053','0033','0043')
                                        AND NIK='$NIK'";
                $queryceklogin_odoo = odbc_exec($conn,$cekloginpayrollstring_odoo);
                $payrolllinkakses_odoo = odbc_result($queryceklogin_odoo,"pa");

                if($payrolllinkakses_odoo==1)
                {
		    $cekStatus++;
                    ?>
                     <li><a href="payrolllink.php">Payroll</a></li>    
                    <?php
                }

        //E-SLIP KM Odoo 8 PT Payroll Batch 2
                
        $cekloginpayrollstring_odoobatch2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE PersArea
                                        in(
                                        /*PT. SIRKULASI KOMPAS GRAMEDIA
                                        PT. TRANSITO ADIMAN JATI
                                        PT. RADIO SAFARI BINA BUDAYA
                                        PT. RADIO SONORA
                                        PT. RADIO SMART MEDIA UTAMA
                                        PT. KOMANDO MEDIA TELEVISI
                                        PT. CIPTA MEGASWARA TELEVISI
                                        KGXPRESS*/
                                        '0004',
                                        '0024',
                                        '0026',
                                        '0034',
                                        '0058',
                                        '0057',
                                        '0112')
                                        AND NIK='$NIK'";
                $queryceklogin_odoobatch2 = odbc_exec($conn,$cekloginpayrollstring_odoobatch2);
                $payrolllinkakses_odoobatch2 = odbc_result($queryceklogin_odoobatch2,"pa");

                if($payrolllinkakses_odoobatch2==1)
                {
		    $cekStatus++;
                    ?>
                     <li><a href="payrolllink.php">Payroll</a></li>    
                    <?php
                }


                //E-SLIP  KARYAWAN PT MURID MERDEKA NUSANTARA
                
        		$cekloginpayrollstring_ki="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE PayArea = 'KI'
                                        and (PersArea = '0326')
                                        AND NIK='$NIK'";
                $queryceklogin_ki = odbc_exec($conn,$cekloginpayrollstring_odoobatch2);
                $payrolllinkakses_ki = odbc_result($queryceklogin_ki,"pa");

                if($payrolllinkakses_ki==1)
                {
		    		$cekStatus++;
                    ?>
                     <li><a href="payrolllink.php">Payroll</a></li>    
                    <?php
                }


//              -------------------------------------------
//		---------------- SLIP END -----------------
//		-------------------------------------------

                
//                ------------------------------------------
//                ---------------- E SPT -------------------
//                ------------------------------------------
                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp "
                                        . "where PayArea in ('KA','KB','KC','KX','KF','KE','KI') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    ?>
                    <li><a href="sptlink.php">Form 1721 A1 online</a></li>
                    <?php
                }

                // SPT GOMAN
                $cekloginpayrollstring2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KD'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea='0276' or PersArea='0277' or PersArea='0278' or PersArea='0275' or PersArea='0003' or PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='908'
                                        AND NIK='$NIK'";

                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    ?>
                    <li><a href="sptlink.php">Form 1721 A1 online</a></li>
                    <?php
                }

                //SPT Printing KL
    			$cekloginpayrollstring_printing="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KL'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='924'
                                        AND NIK='$NIK'";
                $queryceklogin_printing = odbc_exec($conn,$cekloginpayrollstring_printing);
                $payrolllinkakses_printing = odbc_result($queryceklogin_printing,"pa");

                if($payrolllinkakses_printing==1)
                {
                    ?>
                    <li><a href="sptlink.php">Form 1721 A1 online</a></li>
                    <?php
                }


			    //SPT KG Pro KH  
			                
			    $cekloginpayrollstring_kgpro="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KH'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0268'))
                                        and (PersAdmin='004')
                                        AND PayAdmin='926'
                                        AND NIK='$NIK'";
                $queryceklogin_kgpro = odbc_exec($conn,$cekloginpayrollstring_kgpro);
                $payrolllinkakses_kgpro = odbc_result($queryceklogin_kgpro,"pa");

                if($payrolllinkakses_kgpro==1)
                {
                    ?>
                    <li><a href="sptlink.php">Form 1721 A1 online</a></li>
                    <?php
                }
                
//                -----------------------------------------
//                ------------- E SPT END -----------------                
//                -----------------------------------------
                
                // RC ditutup dlu smp Feb tgl 24 2023
                if (strtotime(date("Y-m-d")) >= strtotime(date("2023-02-24"))) {
                	
                	//RC Pinjaman GKU

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KB'
                                        and (PersArea = '0023' or PersArea='0060')
                                        and NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
                    	// rc pinjaman ditutup sampai februari 2023
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }

                //RC Pinjaman KOMPAS COM

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='006'
                                        AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }


                //RC Pinjaman KONTAN

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='008'
                                        AND PayAdmin='901'
                                        AND PersArea='0013' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }

                //RC PINJAMAN MAJALAH

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KE'
                                        and status='Permanent'
                                        AND PayAdmin='906'
                                        AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }



                //RC Pinjaman GORP

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PayArea = 'KC' and status='Permanent' and (PersAdmin='002' OR PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')) AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }
                
                
                
                //RC Pinjaman SKG

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp "
                        . "WHERE PayArea = 'KF' and status='Permanent' and PayAdmin='925' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }



                //RC Pinjaman Kompas

                $cekloginpayrollstring2="SELECT count(a.NIK) as pa FROM ms_niktelp a, ms_EmpOrg b
                                        WHERE
											'00'+a.NIK = b.NIK
											and b.COCODE = '1245'
											and
                                                a.PayArea = 'KB'
                                                and (a.status='Permanent' or a.status='Contract')
                                                and (a.PersAdmin='007')
                                                and a.PersArea = '0245'
                                                AND a.PayAdmin='902'
                                                AND a.NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2>0)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
    <?php
                    }
                }


                //RC-Pinjaman GOMAN  
                
     			$cekloginpayrollstring_goman="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KD'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea='0276' or PersArea='0277' or PersArea='0278' or PersArea='0275' or PersArea='0003' or PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='908'
                                        AND NIK='$NIK'";
                $queryceklogin_goman = odbc_exec($conn,$cekloginpayrollstring_goman);
                $payrolllinkakses_goman = odbc_result($queryceklogin_goman,"pa");

                if($payrolllinkakses_goman==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
	                $queryrc = odbc_exec($conn,$tblistrcstring);
	                $rcakses = odbc_result($queryrc,"listrc");

	                    if($rcakses==1)
	                    {?>
	                    	<li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
	                    <?php }
                }

                //RC Pinjaman Printing KL  
                
    			$cekloginpayrollstring_printing="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KL'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='924'
                                        AND NIK='$NIK'";
                $queryceklogin_printing = odbc_exec($conn,$cekloginpayrollstring_printing);
                $payrolllinkakses_printing = odbc_result($queryceklogin_printing,"pa");

                if($payrolllinkakses_printing==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
	                $queryrc = odbc_exec($conn,$tblistrcstring);
	                $rcakses = odbc_result($queryrc,"listrc");

	                    if($rcakses==1)
	                    {?>
	                    	<li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
	                    <?php }
                }


			    //RC Pinjaman KG Pro KH  
			                
			    $cekloginpayrollstring_kgpro="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KH'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0268'))
                                        and (PersAdmin='004')
                                        AND PayAdmin='926'
                                        AND NIK='$NIK'";
                $queryceklogin_kgpro = odbc_exec($conn,$cekloginpayrollstring_kgpro);
                $payrolllinkakses_kgpro = odbc_result($queryceklogin_kgpro,"pa");

                if($payrolllinkakses_kgpro==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
	                $queryrc = odbc_exec($conn,$tblistrcstring);
	                $rcakses = odbc_result($queryrc,"listrc");

	                    if($rcakses==1)
	                    {?>
	                    	<li><a href="rcpinjamanlink.php">RC pinjaman</a></li>
	                    <?php }
                }
                
//              -------------------------------------------
//		--------  RC PINJAMAN END -----------------
//		-------------------------------------------
                }


                //GORP TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PayArea = 'KC' and status='Permanent' and (PersAdmin='002' OR PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')) AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

                //OTOMONTIR DAN ANGKASA TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='009'
                                        AND PayAdmin='901'
                                        AND (PersArea='0017' OR PersArea='0140') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

                //KONTAN TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='008'
                                        AND PayAdmin='901'
                                        AND PersArea='0013' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }



                //KOMPAS COM TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='006'
                                        AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }



                //KOMPAS TKK

                $cekloginpayrollstring2="SELECT count(a.NIK) as pa FROM ms_niktelp a, ms_EmpOrg b
                                        WHERE
											'00'+a.NIK = b.NIK
											and b.COCODE = '1245'
											and
                                                a.PayArea = 'KB'
                                                and (a.status='Permanent' or a.status='Contract')
                                                and (a.PersAdmin='007')
                                                and a.PersArea = '0245'
                                                AND a.PayAdmin='902'
                                                AND a.NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2>0)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }


                //TKK GOMAN 

                $cekloginpayrollstring2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KD'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea='0276' or PersArea='0277' or PersArea='0278' or PersArea='0275' or PersArea='0003' or PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='908'
                                        AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

                //TKK Printing KL  
                
    			/*$cekloginpayrollstring_printing="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KL'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0257', '0279', '0280', '0032', '0031', '0020', '0141'))
                                        and (PersAdmin='012')
                                        AND PayAdmin='924'
                                        AND NIK='$NIK'";
                $queryceklogin_printing = odbc_exec($conn,$cekloginpayrollstring_printing);
                $payrolllinkakses_printing = odbc_result($queryceklogin_printing,"pa");

                if($payrolllinkakses_printing==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }


			    //TKK KG Pro KH  
			                
			    $cekloginpayrollstring_kgpro="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                        PayArea = 'KH'
                                        and (status='Permanent' or status='Contract')
                                        and (PersArea IN('0268'))
                                        and (PersAdmin='004')
                                        AND PayAdmin='926'
                                        AND NIK='$NIK'";
                $queryceklogin_kgpro = odbc_exec($conn,$cekloginpayrollstring_kgpro);
                $payrolllinkakses_kgpro = odbc_result($queryceklogin_kgpro,"pa");

                if($payrolllinkakses_kgpro==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    
                        <li><a href="TKKsliplink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }*/

//              -------------------------------------------
//		--------  TKK PINJAMAN END -----------------
//		-------------------------------------------

				if($NIK=='021620' || $NIK=='007201' || $NIK=="068101")
				{
				?>
				<li><a href="payrolllink.php">Payroll</a></li>
		<?php
				}

                //-----------------------------   E SLIP END -----------------------------------?>


						</ul>

                <!----------------------- payroll ul end ---------------------------------------->

						</li>
						<?php
						$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
							if (! $fce ) {
								echo "Discovering interface of function module failed";
								exit;
							}
							saprfc_import ($fce,"FI_PERNR","$NIK");
							$rfc_rc = saprfc_call_and_receive ($fce);
							if ($rfc_rc != SAPRFC_OK)
							{if ($rfc == SAPRFC_EXCEPTION )
								echo "System Error18. Please Try Again Later.";
							 else
								echo "System Error19. Please Try Again Later."; exit;}
							$rows = saprfc_table_rows ($fce,"FI_ENT");
							if ($rows == 0)
							{$result = '';}
							else
							{$result = saprfc_table_read($fce,"FI_ENT",1);}
			if (isset($result['PERAREA'])) {
				$PersArea=$result['PERAREA'];
			}

			if (isset($result['SUBAREA'])) {
				$SubArea=$result['SUBAREA'];
			}

			if (isset($result['PERSADMIN'])) {
			   $PersAdmin=$result['PERSADMIN'];
			}
			$CekPartnerRoles = odbc_exec($conn,"SELECT * FROM dbo.ms_PartnerRoles where PersArea='$PersArea'");
			if($row=odbc_fetch_row($CekPartnerRoles)){
				$Status=odbc_result($CekPartnerRoles,"Status");
				if($Status=='1'){
                                                                        $sqlcheckhospitalreimburse="select COUNT(*) as JUMLAH from ms_niktelp "
                                                                                . "where (PersArea='0141' "
                                                                                . "OR (PersArea='0049' AND SubArea='1753')"
                                                                                . "OR (PersArea='0049' AND SubArea='1755')"
                                                                                . "OR (PersArea='0050' AND SubArea='1028')"
                                                                                . "OR (PersArea='0050' AND SubArea='1029')"
                                                                                . "OR (PersArea='0050' AND SubArea='1531')"
                                                                                . "OR (PersArea='0050' AND SubArea='1532')"
                                                                                . "OR (PersArea='0002' AND SubArea='8011')"
                                                                                // untuk persarea 0275, yg boleh buka hny subarea 1027
                                                                                . "OR (PersArea='0275' AND SubArea!='1027')"
                                                                                . ") AND NIK='$NIK'";

                                                                        $Statusmedicalreimburse=odbc_exec($conn,$sqlcheckhospitalreimburse);
                        //                                                $Statuscikarang=odbc_exec($conn,$sqlcheckcikarang);
                                                                        if(odbc_result($Statusmedicalreimburse,"JUMLAH")==0 )
                                                                        {
                                                                            echo '<li><a href="hospital.php" >'.$word[104].'</a></li>'; //Hospital Partners
                                                                        }
				//	echo '<li><a href="apotek.php" >'.$word[105].'</a></li>'; //Pharmacy Partners
				}
			}
			?>


						<?php // cek persarea, persadmin, personalarea
						$sqlCek = odbc_exec($conn, "SELECT COUNT(*) as cek FROM s_jaminan_akses where personalSubArea='$SubArea' and persAdmin='$PersAdmin' and personalArea='$PersArea' and active='true'");
						$cekAktif = odbc_result($sqlCek, 'cek');

						if ($cekAktif > 0) {?>
						<li><a href="#"><?php echo $word[232]; ?></a>
							<ul>
								<?php
									// sql notif start
									$suratJaminan = odbc_exec($conn, "SELECT COUNT(*) as counter FROM s_jaminan a, ms_ModuleAdmin b, ms_ModuleSpecial c WHERE (a.status = '0' AND a.persAdmin = b.PersAdmin AND (c.SpecialName LIKE '%HRSS%' AND c.SpecialID = b.SpecialID AND (c.SpecialName = 'HRSS_SJ')) AND b.UserLogin = '$NIK')");
									$totalSuratJaminan = odbc_result($suratJaminan, 1);
									// sql notif end
									
								 ?>

									<li><a href="SuratJaminan_RequestForm.php"><?php echo $word[235]; ?></a></li>
									<li><a href="SuratJaminan_RequestFormStatus.php" ><?php echo $word[236]; ?></a></li>
									<li><a href="SuratJaminan_History.php"><?php echo $word[237]; ?></a></li>
									
		                	</ul>
	                	</li>
				<?php
					}
				?>
	                	<?php
	                	$queryReimburse=odbc_exec($conn, "select count(*) as hitung_reimburse from ms_niktelp where NIK = '$NIK' and status = 'Permanent' and PersAdmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role)");	                	
	                	/*$queryReimburse=odbc_exec($conn, "select count(*) as hitung_reimburse from ms_niktelp where NIK = '$NIK' and status = 'Permanent'");*/
							$resultReimburse=odbc_result($queryReimburse, 'hitung_reimburse');

						$query_module_user_medrem_admin = "Select UserLogin from ms_ModuleAdmin where UserLogin='$NIK'
											and ModuleID=33 and isActive=1";
						$rs_user_medrem_admin  = odbc_exec($conn, $query_module_user_medrem_admin);

						if(odbc_result($rs_user_medrem_admin,"UserLogin")==$NIK)
						{
								if ($PersAdmin == '007') {

									if ($NIK == '024858') {
						            			$msg_khusus_skg = "AND C.PayArea = '$payarea_login'";
							        	}
							        	else{
							            		$msg_khusus_skg = "";
							        	}

									$rs_medrem = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tb_mr_trx_medrem_header A inner join tb_mr_trx_medrem_detail B on A.id_header=B.id_header inner join ms_niktelp C ON A.nik = C.NIK where A.approved_sap is null and persadmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role WHERE NIK = '$NIK') $msg_khusus_skg");
								}
								else{

									if ($PersAdmin == '004') {
								            $value_rec_msg = "INNER JOIN ms_niktelp MN ON A.nik = MN.NIK AND MN.PersArea IN(SELECT PersAreaRole FROM tb_mr_persarea_role WHERE NIK = '$NIK')";
								       }
								       else{
								            $value_rec_msg = "";
								       }

									$rs_medrem = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tb_mr_trx_medrem_header A inner join tb_mr_trx_medrem_detail B on A.id_header=B.id_header $value_rec_msg where A.approved_sap is null and persadmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role WHERE NIK = '$NIK')");
								}
								
								$total_medrem = odbc_result($rs_medrem, 1);
						}

	                	if ($resultReimburse > 0 || $NIK == '076414') {
	                		?>
	                		<li><a href="#" >Reimburse Online</a>
	                		<!-- <li><a href="#" style="color: red">Medical Reimburse Online Under Maintenance</a></li> -->
								<ul>
								<?php
								/*$query_module_user_medrem_admin = "Select UserLogin from ms_ModuleAdmin where UserLogin='$NIK'
											and ModuleID=33 and isActive=1";
								$rs_user_medrem_admin  = odbc_exec($conn, $query_module_user_medrem_admin);*/

							if(odbc_result($rs_user_medrem_admin,"UserLogin")==$NIK)
							{
								// ditakeout pindah ke atas, spy admin yg non permanen, tetep bisa juga
								/*if ($PersAdmin == '007') {

									if ($NIK == '024858') {
						            	$msg_khusus_skg = "AND C.PayArea = '$payarea_login'";
						        	}
						        	else{
						            	$msg_khusus_skg = "";
						        	}

									$rs_medrem = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tb_mr_trx_medrem_header A inner join tb_mr_trx_medrem_detail B on A.id_header=B.id_header inner join ms_niktelp C ON A.nik = C.NIK where A.approved_sap is null and persadmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role WHERE NIK = '$NIK') $msg_khusus_skg");
								}
								else{
									$rs_medrem = odbc_exec($conn, "select COUNT(*) AS counter from dbo.tb_mr_trx_medrem_header A inner join tb_mr_trx_medrem_detail B on A.id_header=B.id_header where A.approved_sap is null and persadmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role WHERE NIK = '$NIK')");
								}
								
								$total_medrem = odbc_result($rs_medrem, 1);*/

								if ($total_medrem == 0){
									echo "";
								} else{
									echo '<font class="redStrong"> ('. $total_medrem.')</font>';
								}
								echo "<li><a href='mr_hrs_medrem_status_history.php?slc_unit=$PersAdmin'>$word[241]</a></li>"; // History Reimburse Medis HRS
							}
								// penjagaan mbak feli ga boleh buka pny diri sndiri
								if ($resultReimburse > 0 || $NIK != '076414') {

							?>
									<li><a href='mr_req_medrem_history.php'>History Request Medical Reimburse</a></li>
									<li><a href="mr_req_medrem.php"><?php echo $word[240]; ?></a></li>
									<li><a href="mr_req_medrem_tutorial.php">Tutorial Medical Reimburse</a></li>
									<li><a href="mr_req_medrem_faq.php">FAQ Medical Reimburse</a></li>
								<?php
								// tutupnya penjagaan mbak feli
								}
								?>
								</ul>
							</li>
						<?php
						}
						/*-----------------------------------------start tunj sekolah------------------------------------------------*/
						$queryTunjSekolah=odbc_exec($conn, "select * from ms_niktelp where NIK = '$NIK' AND (TglFasilitas !='' AND TglFasilitas < '2018-01-01')");
						$resultTunjSekolah=odbc_num_rows($queryTunjSekolah);
						$persareaTunjSek = odbc_result($queryTunjSekolah, "PersArea");
						$subareaTunjSek = odbc_result($queryTunjSekolah, "SubArea");

						$queryTunjSekAkses=odbc_exec($conn, "SELECT COUNT(*) as hitung_sekolah_akses FROM tb_tunjSekolah_akses WHERE PersArea = '$persareaTunjSek' AND PSubarea = '$subareaTunjSek'");
						$resultTunjSekAkses = odbc_result($queryTunjSekAkses, "hitung_sekolah_akses");

					    //cek apakah ada data di 171
					    $fce = saprfc_function_discover($rfc,"ZHRFM_CEK_171");
		                  if (! $fce ) { echo "Discovering interface of function module failed"; exit; }

		                  //Set import parameters. You can use function saprfc_optional() to mark parameter as optional.
		                  saprfc_import ($fce,"FI_PERNR",$NIK);
		                  //Fill internal tables
		                  saprfc_table_init ($fce,"FI_171");
		                  //Do RFC call of function ZHRFM_MR_PATIENT, for handling exceptions use saprfc_exception()
		                  $rfc_rc = saprfc_call_and_receive ($fce);
		                  if ($rfc_rc != SAPRFC_OK) { if ($rfc == SAPRFC_EXCEPTION ) echo ("Exception raised: ".saprfc_exception($fce)); else echo (saprfc_error($fce)); exit; }
		                  //Retrieve export parameters
		                  $rows = saprfc_table_rows ($fce,"FI_171");
		                  saprfc_function_free($fce);

					    /*if ($rows>0 && $resultTunjSekolah>0 && $resultTunjSekAkses) {
					        echo "oke";
					    }
					    else{
					        echo "tidak oke";
					    }*/

	                	// if ($NIK == '005290' || $NIK == '008471' || $NIK == '003705' || $NIK == '002367' || $NIK == '001619' || $NIK == '001404' || $NIK == '000894' || $NIK == '000349' || $NIK == '003118' || $NIK == '068101' || $NIK == '000909') {
	                		if ($rows>0 && $resultTunjSekolah>0 && $resultTunjSekAkses>0) {
	                		?>
		                		<li><a href="#" ><?php echo $word[243] ?></a>
									<ul>
										<li><a href='tunjSekolah_request.php'><?php echo $word[235] ?></a></li>
										<li><a href="tunjSekolah_historyKaryawan.php"><?php echo $word[90]; ?></a></li>
										<!-- tunjangan sekolah SAP -->
										<li><a href="tunjSekolah_historyKaryawan.php?type=sap" ><?php echo $word[260]; //TunjSekolah(SAP) ?></a></li>
									</ul>
								</li>
						<?php 
							}
						// }
					?>
					<?php
						/*-----------------------------------------end tunj sekolah------------------------------------------------*/
						?>
                        <li><a href="PotonganGaji.php" ><?php echo $word[246]; ?></a></li>
			<!--<li><a href="plafon_medical.php" >Pagu Medical</a></li>-->

                        <?php
                        if($menuFlag['admin'][28]==1){
                        ?>
                        <li>
                            <a href="">Koperasi</a>
                            <ul>
                                <?php
                                if(isset($menuFlag['special'][28][52])){
                                ?>
                                    <li><a href="PotonganKoperasiForm.php">Koperasi Upload</a></li>
                                    <li><a href="PotonganDataKoperasi.php">Koperasi View data</a></li>
                                <?php
                                }

                                if(isset($menuFlag['special'][28][53])){
                                ?>
                                <li><a href="PotonganDataKoperasiAdmin.php">Koperasi Admin</a></li>
                                <?php
                                }
                                ?>
                            </ul>
                        </li>
                        <?php
                        }
                        ?>


            <?php




            //----------------------------------------------
            //---------------BPJS START-----------------------
            //------------------------------------------------
            ?>
            <li>
                <a href="">BPJS</a>
                <ul>
                                <?php
                                $sqlBPJSadd="SELECT count(*) as jumlahBPJSubah FROM tb_Bpjs WHERE (Active='1' OR Active='2' OR Active IS NULL) AND NIK='$NIK' and PISA='1'";
                                $queryBPJSadd = odbc_exec($conn,$sqlBPJSadd);
                                $jumlahBPJSadd = odbc_result($queryBPJSadd,"jumlahBPJSubah");
                                if($jumlahBPJSadd>="1")
                                {
                                ?>
                                        <li><a href="BPJSadd.php" >BPJS Kesehatan Pendaftaran</a></li>
                                <?php
                                }

                                ?>
        <?php
            //}
		$sqlBPJSubah="SELECT count(*) as jumlahBPJSubah FROM tb_Bpjs WHERE (Active='8' OR Active='9' OR Active='7' OR Active='6') AND NIK='$NIK' and PISA='1'";
		$queryBPJSubah = odbc_exec($conn,$sqlBPJSubah);
		$jumlahBPJSubah = odbc_result($queryBPJSubah,"jumlahBPJSubah");
		if($jumlahBPJSubah>="1")
		{
		?>
				<li><a href="BPJSupdatedata.php" >BPJS Kesehatan Update Data</a></li>
		<?php
		}
                $sqlBPJSubahRevisi="SELECT count(*) as jumlahBPJSubah FROM tb_Bpjs WHERE Active='10' AND NIK='$NIK' and PISA='1'";
		$queryBPJSubahRevisi = odbc_exec($conn,$sqlBPJSubahRevisi);
		$jumlahBPJSubahRevisi = odbc_result($queryBPJSubahRevisi,"jumlahBPJSubah");
		if($jumlahBPJSubahRevisi>="1")
		{
		?>
				<li><a href="BPJSupdatedatarevisi.php" >BPJS Kesehatan Update Data Revisi</a></li>
		<?php
		}


                $sqlBPJSRevisi="SELECT count(*) as jumlahBPJSubah FROM tb_Bpjs WHERE Active='5' AND NIK='$NIK' and PISA='1'";
		$queryBPJSRevisi = odbc_exec($conn,$sqlBPJSRevisi);
		$jumlahBPJSRevisi = odbc_result($queryBPJSRevisi,"jumlahBPJSubah");
		if($jumlahBPJSRevisi>="1")
		{
		?>
				<li><a href="BPJSrevisi.php" >BPJS Kesehatan Revisi</a></li>
		<?php
		}
        $sqlBPJS="SELECT count(*) as jumlahBPJSadmin FROM ms_ModuleAdmin WHERE ModuleID='27' AND UserLogin='$NIK'";
        $queryBPJS = odbc_exec($conn,$sqlBPJS);
        $jumlahBPJSadmin = odbc_result($queryBPJS,"jumlahBPJSadmin");

        if($jumlahBPJSadmin>="1")
        {
        ?>
            <li><a href="BPJSadmin.php" ><?php echo "BPJS Administrator"; ?></a></li>
            <li><a href="BPJSreport.php" ><?php echo "BPJS Report"; ?></a></li>
        <?php
        }

        if($NIK=='021620' OR $NIK=='006332' OR $NIK=='007201')
        {
            ?>
                <li><a href="BPJSuploadexcel.php" ><?php echo "BPJS Upload Excel"; ?></a></li>
            <?php
        }

        ?>
                </ul>
            </li>
        <?php
        //----------------------------------------------
        //---------------BPJS END-----------------------
        //------------------------------------------------



			/* Motorcycle Menu */
			//echo "SELECT * FROM dbo.tb_MotorcycleLoanPA where PersonalArea='$PersArea'
			//									and PersonalSubArea='$SubArea' and PersAdmin='$PersAdmin' and //Active='true'";
			//echo "SELECT * FROM dbo.tb_MotorcycleLoanPA where PersonalArea='$PersArea'
				//								and PersonalSubArea='$SubArea' and PersAdmin='$PersAdmin' and Active='true'";



				$mLoanQry   = "select FirstApproverNIK as atasan1,SecondApproverNIK as atasan2, ThirdApproverNIK as atasan3 from VW_MotorCycleLoanDetail WHERE
								(FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK'
								OR ThirdApproverNIK='$NIK')";
					$mLoanResult  = odbc_exec($conn, $mLoanQry);
					if (odbc_result($mLoanResult,1) == $NIK ||
					odbc_result($mLoanResult,2) == $NIK ||
					odbc_result($mLoanResult,3) == $NIK)
				{

					echo '<ul>
						<li><a href="mLoanHistoryApprovalList.php">'.$word[65].'</a></li>'; //Approval History
					$mLoanApprove   = "select *, FirstApproverNIK as atasan1,SecondApproverNIK as atasan2, ThirdApproverNIK as atasan3
								from VW_MotorCycleLoanDetail WHERE
								(FirstApproverNIK='$NIK' OR SecondApproverNIK='$NIK'
								OR ThirdApproverNIK='$NIK')";
					$mLoanAppResult  = odbc_exec($conn, $mLoanApprove);
					while($row = odbc_fetch_row($mLoanAppResult))
            		{
						if (odbc_result($mLoanAppResult,"atasan1") != NULL  &&
							odbc_result($mLoanAppResult,"atasan2")==NULL &&
							odbc_result($mLoanAppResult,"atasan3") ==NULL)
						{
							if(odbc_result($mLoanAppResult,"atasan1") ==$NIK &&
							odbc_result($mLoanAppResult,11) ==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanFirst="select count(*) as TotalFirstMloan
														from VW_MotorCycleLoanDetail
														WHERE FirstApproverNIK='$NIK' and isFirstApproved is NULL";
								$resultCountAppMloanFirst  = odbc_result(odbc_exec($conn, $sqlCountAppMloanFirst),1);
								if($resultCountAppMloanFirst != 0)
								{
									echo "(".$resultCountAppMloanFirst.")";
								}
								echo '</a></li>';
							}

						}
						elseif (odbc_result($mLoanAppResult,"atasan1")!= NULL &&
							odbc_result($mLoanAppResult,"atasan2") !=NULL &&
							odbc_result($mLoanAppResult,"atasan3") ==NULL)
						{
							if(odbc_result($mLoanAppResult,"atasan1") ==$NIK &&
							odbc_result($mLoanAppResult,11) ==NULL &&
							odbc_result($mLoanAppResult,"atasan2") !=$NIK &&
							odbc_result($mLoanAppResult,12)==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanSecond="select count(*) as TotalSecond
												from VW_MotorCycleLoanDetail
												WHERE FirstApproverNIK='$NIK'
												and isSecondApproved is NULL
												and isFirstApproved is null";
						$resultCountAppMloanSecond  = odbc_result(odbc_exec($conn, $sqlCountAppMloanSecond),1);
								if($resultCountAppMloanSecond != 0)
								{
									echo "(".$resultCountAppMloanSecond.")";
								}
								echo '</a></li>';
							}
							elseif(odbc_result($mLoanAppResult,"atasan2") ==$NIK &&
							odbc_result($mLoanAppResult,11)==1 &&
							odbc_result($mLoanAppResult,12)==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanSecond="select count(*) as TotalSecond
												from VW_MotorCycleLoanDetail
												WHERE SecondApproverNIK='$NIK'
												and isSecondApproved is NULL
												and isFirstApproved='1'";
						$resultCountAppMloanSecond  = odbc_result(odbc_exec($conn, $sqlCountAppMloanSecond),1);
								if($resultCountAppMloanSecond != 0)
								{
									echo "(".$resultCountAppMloanSecond.")";
								}
								echo '</a></li>';
							}
						}
						elseif (odbc_result($mLoanAppResult,"atasan1")!= NULL &&
							odbc_result($mLoanAppResult,"atasan2") !=NULL &&
							odbc_result($mLoanAppResult,"atasan3")!=NULL)
						{
							if(odbc_result($mLoanAppResult,"atasan1") ==$NIK &&
							odbc_result($mLoanAppResult,11) ==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanThird="select count(*) as TotalSecond
												from VW_MotorCycleLoanDetail
												WHERE FirstApproverNIK='$NIK' and isThirdApproved is NULL
												and isFirstApproved is NULL and
												isSecondApproved is NULL";
								$resultCountAppMloanThird  = odbc_result(odbc_exec($conn, $sqlCountAppMloanThird),1);
								if($resultCountAppMloanThird != 0)
								{
									echo "(".$resultCountAppMloanThird.")";
								}
								echo '</a></li>';
							}
							elseif(odbc_result($mLoanAppResult,"atasan2") ==$NIK &&
							odbc_result($mLoanAppResult,11)==1 &&
							odbc_result($mLoanAppResult,12)==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanThird="select count(*) as TotalSecond
												from VW_MotorCycleLoanDetail
												WHERE SecondApproverNIK='$NIK' and
												isThirdApproved is NULL
												and isFirstApproved='1' and
												isSecondApproved is NULL";
						$resultCountAppMloanThird  = odbc_result(odbc_exec($conn, $sqlCountAppMloanThird),1);
								if($resultCountAppMloanThird != 0)
								{
									echo "(".$resultCountAppMloanThird.")";
								}
								echo '</a></li>';
							}
							elseif(odbc_result($mLoanAppResult,"atasan3") ==$NIK &&
							odbc_result($mLoanAppResult,11)==1 &&
							odbc_result($mLoanAppResult,12)==1 &&
							odbc_result($mLoanAppResult,13)==NULL)
							{
								echo '<li><a href="mLoanApprovalStatus.php">'.$word[63]; //Approval
								$sqlCountAppMloanThird="select count(*) as TotalSecond
												from VW_MotorCycleLoanDetail
												WHERE ThirdApproverNIK='$NIK' and isThirdApproved is NULL
												and isFirstApproved='1' and
												isSecondApproved='1'";
						$resultCountAppMloanThird  = odbc_result(odbc_exec($conn, $sqlCountAppMloanThird),1);
								if($resultCountAppMloanThird != 0)
								{
									echo "(".$resultCountAppMloanThird.")";
								}
								echo '</a></li>';
							}
						}
					}
					echo '
						</ul>
						</li>';
				}


/*			$CekRolesSubAreaPA=odbc_exec($conn,"SELECT * FROM dbo.tb_MotorcycleLoanPA where PersonalArea='$PersArea'
							and PersonalSubArea='$SubArea' and PersAdmin='$PersAdmin' and Active='true'"); */
//echo 	"SELECT * FROM dbo.tb_MotorcycleLoanPA where PersonalArea='$PersArea' and PersonalSubArea='$SubArea' and PersAdmin='$PersAdmin' and Active='true'";

			$CekRolesSubAreaPA=odbc_exec($conn,"SELECT * FROM dbo.tb_MotorcycleLoanPA where
							PersonalSubArea='$SubArea' and PersAdmin='$PersAdmin' and Active='true'");
			if($rowMloanPA=odbc_fetch_row($CekRolesSubAreaPA))
			{
				$StatusPA=odbc_result($CekRolesSubAreaPA,"Active");
				$PersonalArea=odbc_result($CekRolesSubAreaPA,"PersonalArea");
				$PersonalSubArea=odbc_result($CekRolesSubAreaPA,"PersonalSubArea");
				if($StatusPA=='1')
				{
					echo '<li>
					<a href="#" >'.$word[106].'</a>
					<ul>'; //Motorcycle Loan

					/*echo 	'<li><a href="mLoanStep1.php" >'.$word[115].'</a></li>'; //Request Motorcycle Loan
					echo '<li><a href="mLoanStatusHistory.php" >'.$word[116].'</a></li>'; //Status/History Motorcycle Loan*/





				$mLoanAdmin ="select * from ms_ModuleAdmin WHERE UserLogin='$NIK' and ModuleID=14 and SpecialID=17 and isActive=1";
				$mLoanAdminResult  = odbc_exec($conn, $mLoanAdmin);
				if (odbc_result($mLoanAdminResult,"UserLogin") == $NIK)
				{
				echo '<li><a href="#">'.$word[107].'</a>'; //Master Data Motorcycle
				echo '<ul>
					<li><a href="mLoanMerkListMaster.php">'.$word[108].'</a></li>'; //Master Merk Motorcycle
				echo '<li><a href="mLoanTypeListMaster.php">'.$word[109].'</a></li>'; //Master Type Motorcycle
				echo '<li><a href="mLoanAreaListMaster.php">Master Area Motorcycle</a></li>'; //Master Area Admin
				echo '</ul></li>';
				}

				$mLoanAdminHR ="select * from ms_ModuleAdmin WHERE UserLogin='$NIK' and ModuleID=14 and
								SpecialID=18 and isActive=1";
				$mLoanAdminHRResult  = odbc_exec($conn, $mLoanAdminHR);

				if(odbc_result($mLoanAdminHRResult,"UserLogin") == $NIK)
				{
					echo'<li><a href="mLoanHRApprovalListHistory.php" >'.$word[110]; //HR Approval List/History
					$sqlTotalHRAppMloan="select count(*) as total from VW_MotorCycleLoanDetail
										WHERE LastStatus=1 and isFirstHRApproved is NULL";
					$queryTotalHRAppMloan  = odbc_exec($conn, $sqlTotalHRAppMloan);

					if (odbc_result($queryTotalHRAppMloan,"total")!=NULL)
					{
						$sqlCountHRAppMloan="select * from VW_MotorCycleLoanDetail
											WHERE LastStatus=1 and isFirstHRApproved is NULL";
						$queryCountHRAppMloan=odbc_exec($conn, $sqlCountHRAppMloan);
						$resultCountHRAppMloan=0;
						while($row = odbc_fetch_row($queryCountHRAppMloan))
						{
							//validasi personal admin sama dengan personal admin nik yang diminta lembur
							$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
							if (! $fce )
							{echo "System Error17. Please Try Again Later."; exit;}
							saprfc_import ($fce,"FI_PERNR",odbc_result($queryCountHRAppMloan,"NIK"));
							saprfc_table_init ($fce,"FI_ENT");
							$rfc_rc = saprfc_call_and_receive ($fce);
							if ($rfc_rc != SAPRFC_OK)
							{if ($rfc == SAPRFC_EXCEPTION )
								echo "System Error18. Please Try Again Later.";
							 else
								echo "System Error19. Please Try Again Later."; exit;}
							$rows = saprfc_table_rows ($fce,"FI_ENT");
							if ($rows == 0)
							{$FI_ENT2 = '';}
							else
							{$FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);}

							$PersArea2= $FI_ENT2['PERAREA'];
							$SubArea2= $FI_ENT2['SUBAREA'];
							$PersAdmin2= $FI_ENT2['PERSADMIN'];


							if ($PersAdmin==$PersAdmin2)
							{
								$resultCountHRAppMloan=$resultCountHRAppMloan+1;
							}
						}

						if($resultCountHRAppMloan != 0)
						{echo "<font color='#FF0000'>(".$resultCountHRAppMloan.")</font>";}
					}

					echo '</a></li>';
				}

				else
				{

					$ModulAdminHRmLoan = "Select * from ms_ModuleAdmin where ModuleID=14 and SpecialID=18 and isActive=1";
					$resultModulAdminHRmLoan  = odbc_exec($conn, $ModulAdminHRmLoan);

					while($row = odbc_fetch_row($resultModulAdminHRmLoan))
					{

						//validasi personal admin sama dengan personal admin nik yang diminta lembur
						$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
						if (! $fce )
						{echo "System Error23. Please Try Again Later."; exit;}
						saprfc_import ($fce,"FI_PERNR",odbc_result($resultModulAdminHRmLoan,"UserLogin"));
						saprfc_table_init ($fce,"FI_ENT");
						$rfc_rc = saprfc_call_and_receive ($fce);
						if ($rfc_rc != SAPRFC_OK)
						{if ($rfc == SAPRFC_EXCEPTION )
							echo "System Error15. Please Try Again Later.";
						 else
							echo "System Error16. Please Try Again Later."; exit;}
						$rows = saprfc_table_rows ($fce,"FI_ENT");
						if ($rows == 0)
						{$FI_ENT2 = '';}
						else
						{$FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);}


						$PersAreaHRAdmin= $FI_ENT2['PERAREA'];
						$SubAreaHRAdmin= $FI_ENT2['SUBAREA'];
						$PersAdminHRAdmin= $FI_ENT2['PERSADMIN'];

						$mLoanApproveHRAdmin   = "select * from VW_MotorCycleLoanDetail WHERE LastStatus=1
													and isFirstHRApproved is null";
						$mLoanAppResultHRAdmin  = odbc_exec($conn, $mLoanApproveHRAdmin);

						while($row = odbc_fetch_row($mLoanAppResultHRAdmin))
						{
							$flag=0;
							//validasi personal admin sama dengan personal admin nik yang diminta lembur
							$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
							if (! $fce )
							{echo "System Error25. Please Try Again Later."; exit;}
							saprfc_import ($fce,"FI_PERNR",odbc_result($mLoanAppResultHRAdmin,"NIK"));
							saprfc_table_init ($fce,"FI_ENT");
							$rfc_rc = saprfc_call_and_receive ($fce);
							if ($rfc_rc != SAPRFC_OK)
							{if ($rfc == SAPRFC_EXCEPTION )
								echo "System Error12. Please Try Again Later.";
							 else
								echo "System Error13. Please Try Again Later."; exit;}
							$rows = saprfc_table_rows ($fce,"FI_ENT");
							if ($rows == 0)
							{$FI_ENT2 = '';}
							else
							{$FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);}

							$PersAreaEmployee= $FI_ENT2['PERAREA'];
							$SubAreaEmployee= $FI_ENT2['SUBAREA'];
							$PersAdminEmployee= $FI_ENT2['PERSADMIN'];

							if ($PersAdminEmployee == $PersAdminHRAdmin)
							{
								$flag=1;
							}
						}
					}

					//if($flag==1)
					//{

						$cekModuleAdminHRManager = "Select * from ms_ModuleAdmin where UserLogin='$NIK'
										and ModuleID=14 and SpecialID=19 and isActive=1";
						$resultModuleAdminHRManager  = odbc_exec($conn, $cekModuleAdminHRManager);

						if(odbc_result($resultModuleAdminHRManager,"UserLogin")==$NIK)
						{
							echo'<li><a href="mLoanHRApprovalListHistory.php" >'.$word[110]; //HR Approval List/History
							$sqlTotalHRAppMloan="select count(*) as total from VW_MotorCycleLoanDetail
											WHERE LastStatus=1 and (isFirstHRApproved='1')";
							$queryTotalHRAppMloan  = odbc_exec($conn, $sqlTotalHRAppMloan);

							if (odbc_result($queryTotalHRAppMloan,"total")!=NULL || odbc_result($queryTotalHRAppMloan,"total")!=0)
							{

							$sqlCountHRAppMloan="select * from VW_MotorCycleLoanDetail
												WHERE LastStatus=1 and (isFirstHRApproved='1')";
							$queryCountHRAppMloan  = odbc_exec($conn, $sqlCountHRAppMloan);
							$resultCountHRAppMloan=0;
							$get_persadmin_rep = "SELECT rep_persadmin FROM tbl_rep_mloan where nik=$NIK";
							$exec_get_persadmin_rep = odbc_exec($conn, $get_persadmin_rep);
							$pers_admin_rep = odbc_result($exec_get_persadmin_rep,"rep_persadmin");							

							while($row = odbc_fetch_row($queryCountHRAppMloan))
							{
								//validasi personal admin sama dengan personal admin nik yang diminta lembur
								$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
								if (! $fce )
								{echo "System Error22. Please Try Again Later."; exit;}
								saprfc_import ($fce,"FI_PERNR",odbc_result($queryCountHRAppMloan,"NIK"));
								saprfc_table_init ($fce,"FI_ENT");
								$rfc_rc = saprfc_call_and_receive ($fce);
								if ($rfc_rc != SAPRFC_OK)
								{if ($rfc == SAPRFC_EXCEPTION )
									echo "System Error21. Please Try Again Later.";
								 else
									echo "System Error20. Please Try Again Later."; exit;}
								$rows = saprfc_table_rows ($fce,"FI_ENT");
								if ($rows == 0)
								{$FI_ENT2 = '';}
								else
								{$FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);}

								$PersArea2= $FI_ENT2['PERAREA'];
								$SubArea2= $FI_ENT2['SUBAREA'];
								$PersAdmin2= $FI_ENT2['PERSADMIN'];
								
								if($pers_admin_rep!=NULL)
								{
									if ($pers_admin_rep == $PersAdmin2)
									{
										$resultCountHRAppMloan=$resultCountHRAppMloan+1;
									}
							
								}else{
									if ($PersAdmin == $PersAdmin2)
									{
										$resultCountHRAppMloan=$resultCountHRAppMloan+1;
									}
								}
							}

							if($resultCountHRAppMloan != 0)
							{echo "<font color='#FF0000'>(".$resultCountHRAppMloan.")</font>";}
							}

						echo '</a></li>';

						}
					//}
				}

				$mLoanAdminPendingApp ="select * from ms_ModuleAdmin WHERE UserLogin='$NIK' and
									ModuleID=14 and SpecialID=17 and isActive=1";
				$ResultmLoanAdminPendingApp  = odbc_exec($conn, $mLoanAdminPendingApp);
					if(odbc_result($ResultmLoanAdminPendingApp,"UserLogin") == $NIK)
					{
						echo'<li><a href="mLoanPendingAppList.php" ><img src="img/icon/alert.png" />'.$word[111].'</a></li>'; //List Pending Approval
					}

				$tgllahir=substr(odbc_result($queryall,"TTL"),8,2).substr(odbc_result($queryall,"TTL"),5,2).substr(odbc_result($queryall,"TTL"),0,4);

                include "include/selisihDate.php";
				$tglDiangkatSAPHitung= substr(odbc_result($queryall,"TGLDIANGKAT"),0,4).'/'.substr(odbc_result($queryall,"TGLDIANGKAT"),5,2).'/'.substr(odbc_result($queryall,"TGLDIANGKAT"),8,2);
                $tglsekarang= date("Y/m/d/ h:m:s");
				$a = selisihTanggal($tglDiangkatSAPHitung,$tglsekarang);

				if($a['years'] == 0 && odbc_result($queryall,"Status")=='Permanent ' || odbc_result($queryall,"Status")!='Permanent ')
				{
				   	//echo "Anda belum berhak mengajukan pinjaman motor";
				}
				elseif($a['years'] == 1 && odbc_result($queryall,"Status")=='Permanent ' || odbc_result($queryall,"Status")!='Permanent ')
				{
				 	echo '<li><a href="files/FORMMOTORDINJAB1.doc" >'.$word[112].'</a></li>'; //Download Formulir Pinjaman Motor Dinas Jabatan
				}
				else
				{
					echo '<li><a href="files/FORMMOTORDINJAB1.doc" >'.$word[112].'</a></li>'; //Download Formulir Pinjaman Motor Dinas Jabatan
					echo '<li><a href="files/FORMMOTORMASAKERJA1.doc" >'.$word[113].'</a></li>'; //Download Formulir Pinjaman Motor Masa Kerja
				}

					echo '</ul></li>';

				}
			}
		?>
        <!-- End Motorcycle menu -->

			<!-- <li><a href="files/SuratPernyataanAnakTertanggungUsiadiatas21.xls" ><?php echo $word[114]; //Download Formulir Anak Tertanggung > 21 Tahun ?></a></li>-->
		</ul></li><!--End of ComBen-->
		

                <?php
                                                }
                ?>

        <?php 
				// Ini Code Beasiswa
				$queryAccBeasiswa = "SELECT TOP 1 status_daftar,status_test,st_notif_daftar,st_notif_test FROM bsiswa_tb_pdaftaran WHERE NIK = '$NIK' ORDER BY NIS DESC";
				$execAccBeasiswa = odbc_exec($conn,$queryAccBeasiswa);
				//$resAccBeasiswa = odbc_fetch_row($execAccBeasiswa);
				$key = odbc_fetch_array($execAccBeasiswa);
				$accBeasiswa = $key['status_daftar'];
				$tesBeasiswa = $key['status_test'];
				$ntAccBeasiswa = $key['st_notif_daftar'];
				$ntTesBeasiswa = $key['st_notif_test'];

				$tgl_daftar = odbc_exec($conn, "SELECT TOP 1 tgl_mulai, tgl_selesai FROM bsiswa_tg_pdaftaran ORDER BY id desc");
				$fetch = odbc_fetch_array($tgl_daftar);
				$tgl_mulai = $fetch['tgl_mulai'];
				$tgl_selesai = $fetch['tgl_selesai'];
				$today = date('Y-m-d');
				$tgl_mulai = date("Y-m-d", strtotime($tgl_mulai));
				$tgl_selesai = date("Y-m-d", strtotime($tgl_selesai));
				
				$sqlBeasiswa="SELECT count(*) as jumlahbeasiswa FROM ms_ModuleAdmin a, ms_Module b WHERE a.ModuleID=b.ModuleID and b.ModuleName='Beasiswa' AND UserLogin='$NIK'";
				$queryBeasiswa = odbc_exec($conn,$sqlBeasiswa);
				$totalAdmin = odbc_result($queryBeasiswa,"jumlahbeasiswa");
				?>
		<li><a href="">Beasiswa</a>
			<ul>
				<?php if($totalAdmin>="1") {?>
					<li><a href="Beasiswa_BukaPendaftaran.php">Buka Pendaftaran</a></li>
					<li><a href="Beasiswa_PendaftaranSiswa_Admin.php">Data Pendaftar</a></li>
					<li><a href="Beasiswa_MaintainData_Overview.php">Maintain Data Siswa</a></li>
					<li><a href="Beasiswa_TambahData_Lama.php">Upload Data Lama</a></li>
				<?php } if($today >= $tgl_mulai && $today <= $tgl_selesai) { ?>
				 <li><a href="homeBeaSiswa.php">Pengajuan Beasiswa</a></li> 
				<?php } ?>
				 <li><a href="Beasiswa_HistoryPengajuan.php">History Pengajuan</a></li> 
			</ul>
		</li>
		
		<li><a href=""><?php echo $word[117]; //Legal ?></a><ul>
			<li><a href="ppoindex.php"><?php echo $word[118]; //Peraturan Perusahaan Online ?></a></li>
			<?php
				if ((isset($menuFlag['admin'][9]) && $menuFlag['admin'][9]==1 )and (isset($menuFlag['special'][9][11] ) && $menuFlag['special'][9][11]==1)){ //for officer law consultation
					echo '<li><a href="">'.$word[119].'</a><ul>'; //Labor Law Consultation
					$sqlLLC1 = "select COUNT(*) from LLC_V_Consultation where approveFlag = 0 and Answer is NULL";
					$sqlLLC2 = "select COUNT(*) from LLC_V_Consultation where approveFlag = 0 and Answer is not NULL";
					$countLLC1 = odbc_result(odbc_exec($conn,$sqlLLC1), 1);
					$countLLC2 = odbc_result(odbc_exec($conn,$sqlLLC2), 1);
					$countLLC = $countLLC1+$countLLC2;
					echo '<li><a href="LLC_QuestionList.php">Question List';
					if ($countLLC !=0){
						echo '<font class="redStrong"> ('.$countLLC .')</font>';
					}
					echo '</a></li>';
					echo '<li><a href="LLC_FAQHistory.php">FAQ History</a></li>';
					echo '</ul></li>';
				}elseif($menuFlag['admin'][9]==1 and $menuFlag['special'][9][12]==1){
					echo '<li><a href="">Labor Law Consultation</a><ul>';
					$sqlLLC1 = "select COUNT(*) from LLC_V_Consultation where approveFlag = 0 and Answer is NULL";
					$sqlLLC2 = "select COUNT(*) from LLC_V_Consultation where approveFlag = 0 and Answer is not NULL";
					$countLLC1 = odbc_result(odbc_exec($conn,$sqlLLC1), 1);
					$countLLC2 = odbc_result(odbc_exec($conn,$sqlLLC2), 1);
					$countLLC = $countLLC1;
					echo '<li><a href="LLC_QuestionList.php">Question List';
					if ($countLLC!=0){
						echo '<font class="redStrong"> ('.$countLLC.')</font>';
					}
					echo '</a></li>';
					$sqlLLC3 = "select COUNT(*) from LLC_V_Consultation where approveFlag = 1 and Answer is not NULL";
					$countLLC3 = odbc_result(odbc_exec($conn,$sqlLLC3), 1);
					echo '<li><a href="LLC_ApprovalList.php">Approval List';
					if ($countLLC3!=0){
						echo '<font class="redStrong"> ('.$countLLC3.')</font>';
					}
					echo '</a></li>';
					echo '<li><a href="LLC_FAQHistory.php">FAQ History</a></li>';
					echo '<li><a href="LLC_OfficerMaster.php">Master Officer</a></li>';
					echo '</ul></li>';
				}else{
					echo '<li><a href="userAgreement.php?mode=LLC">'.$word[119].'</a></li>'; //Labor Law Consultation
				}
			?>
<!--			<li><a href="gov_regulation.php">Undang Undang Pemerintah</a></li> -->
		</ul></li> <!--End of Legal-->
		<?php
				if (isset($menuFlag['admin'][13])==1 and isset($menuFlag['special'][13][15])==1){ //for officer Help Desk
					echo '<li><a href="">Help Desk</a><ul>';
					$sqlHRHD1 = "select COUNT(*) from HRHD_V_Consultation where approveFlag = 0";
					$sqlHRHD2 = "select COUNT(*) from HRHD_V_Consultation where approveFlag = 3";
					$countHRHD1 = odbc_result(odbc_exec($conn,$sqlHRHD1), 1);
					$countHRHD2 = odbc_result(odbc_exec($conn,$sqlHRHD2), 1);
					$countHRHD = $countHRHD1+$countHRHD2;
					echo '<li><a href="HRHD_QuestionList.php">'.$word[120]; //Question List
					if ($countHRHD !=0){
						echo '<font class="redStrong"> ('.$countHRHD .')</font>';
					}
					echo '</a></li>';
					echo '<li><a href="HRHD_FAQHistory.php">'.$word[121].'</a></li>'; //FAQ History
					echo '</ul></li>';
				}elseif($menuFlag['admin'][13]==1 and $menuFlag['special'][13][16]==1){
					echo '<li><a href="">Help Desk</a><ul>';
					$sqlHRHD1 = "select COUNT(*) from HRHD_V_Consultation where approveFlag = 0 and Answer is NULL";

					$countHRHD1 = odbc_result(odbc_exec($conn,$sqlHRHD1), 1);
					$countHRHD2 = 0;
					$countHRHD = $countHRHD1;
					echo '<li><a href="HRHD_QuestionList.php">'.$word[120]; //Question List
					if ($countHRHD!=0){
						echo '<font class="redStrong"> ('.$countHRHD.')</font>';
					}
					echo '</a></li>';
					$sqlHRHD3 = "select COUNT(*) from HRHD_V_Consultation where approveFlag = 1 and Answer is not NULL";
					$countHRHD3 = odbc_result(odbc_exec($conn,$sqlHRHD3), 1);
					echo '<li><a href="HRHD_ApprovalList.php">'.$word[92]; //Approval List
					if ($countHRHD3!=0){
						echo '<font class="redStrong"> ('.$countHRHD3.')</font>';
					}
					echo '</a></li>';
					echo '<li><a href="HRHD_FAQHistory.php">'.$word[121].'</a></li>'; //FAQ History
					echo '<li><a href="HRHD_OfficerMaster.php">'.$word[122].'</a></li>'; //Master Officer
					echo '</ul></li>';
				}
			?>
		<?php
		$cek_CHR = odbc_exec($conn, "SELECT count(*) as jml FROM ms_EmpOrg WHERE Unit1 = '50002605' AND NIK = '00$NIK'");
		$count_CHR = odbc_result($cek_CHR, "jml");

		//Chr Organizer
		if (($menuFlag['admin'][2]==1 or $menuFlag['admin'][3]==1 or $menuFlag['admin'][6]==1 or $menuFlag['view'][2]==1 or $menuFlag['view'][3]==1 or $menuFlag['view'][6]==1 or $menuFlag['view'][20]==1) && $count_CHR > 0){
			echo '<li><a href="#">'.$word[123].'</a><ul>'; //CHR Organizer
			if($menuFlag['admin'][18]!=0 and isset($menuFlag['special'][18][27])&& $menuFlag['special'][18][27]==1)
			{

				$sqlTotalStatus="select count(*) as total from tb_MonitoringSMSGateway  WHERE [Status]='1' AND AdminNIK != ''";
				$queryCountSMSGateway  = odbc_exec($conn, $sqlTotalStatus);
				$totalSMSGateway = odbc_result($queryCountSMSGateway, "total");
				echo '<li><a href="frmSMSGatewayStatus.php">'.$word[178].'</a></li>'; //SMS Gateway
			}

			if($menuFlag['admin'][18]!=0 and $menuFlag['special'][18][26]==1)
			{
					echo '<li><a href="frmSMSGatewayRequest.php">'.$word[177].'</a></li>'; //SMS Gateway
					echo '<li><a href="frmSMSGatewayStatus.php">'.$word[178].'</a></li>'; //SMS Gateway
			}

			if ($menuFlag['view'][2]==1){
			echo '<li><a href="redirect.php?to=hrss&redirect=agenda">Agenda</a></li>';

			// echo '<li><a href="agendaList.php?id=all&agendaDate='. date('Y-m-d').'">'.$word[124].'</a></li>'; //Agenda
			echo '<li><a href="roomEquipmentUsingList.php?id=all&agendaDate='. date('Y-m-d').'">'.$word[125].'</a></li>'; //Room/Equipment
			}
			if ($menuFlag['admin'][6]==1){
				$_SESSION['typeLogin_logbook']='admin';
				echo '<li><a href="#">'.$word[126].'</a><ul>'; //Logbook+
				echo '<li><a href="logbook_invTech_list.php">'.$word[127]; //Support Request List
				$sqlLogbookReq = "select count(*) from inv_techReq where status_flag in ('U')";
				$totalLogbookReq = odbc_result(odbc_exec($conn,$sqlLogbookReq), 1);
				if ($totalLogbookReq !=0){
					echo '<font class="redStrong"> ('.$totalLogbookReq.')</font>';
				}
				echo '</a></li>';//end of request List
				echo '<li><a href="logbook_invTech_history.php">'.$word[53].'</a></li>';//end of history
				echo '<li><a href="logbook_invTech_req.php">'.$word[128].'</a></li>'; //Support Request Form
				echo '</ul></li>';//end of logbook (admin)
				echo '<li><a href="#">'.$word[129].'</a><ul>'; //Master Data Logbook
				echo '<li><a href="#">'.$word[130].'</a><ul>'; //Inventory Item
				echo '<li><a href="logbook_invMaster_itemDetail.php">'.$word[131].'</a></li>'; //Item Unit
				echo '<li><a href="logbook_invMaster_item.php">'.$word[132].'</a></li>'; //Item Series
				echo '<li><a href="logbook_invMaster_type.php">'.$word[133].'</a></li>'; //Item Type
				echo '<li><a href="logbook_invMaster_merk.php">'.$word[134].'</a></li>'; //Item Brand
				echo '</ul></li>';//end of Inventory Item
				echo'<li><a href="logbookAdminListMaster.php">'.$word[135].'</a></li>'; //Technical Support Master
				echo '<li><a href="logbookUserListMaster.php?deptID=ALL">'.$word[136].'</a></li>'; //Logbook User Master
				echo '</ul></li>';//end of master data logbook
			}
			if ($menuFlag['view'][6]==1){
				echo '<li><a href="#">Logbook+</a><ul>';
				echo '<li><a href="logbook_invTech_req.php">'.$word[128].'</a></li>'; //Support Request Form
				echo '<li><a href="logbook_invTech_progres.php">'.$word[137]; //Progress
				$sqlFeedback ="select count(*) from inv_techReq where request_by = (select ViewID from ms_ModuleView where UserLogin = '$NIK' and ModuleID = 6 and isActive=1) and status_flag ='F'";
				$countFeedback =odbc_result(odbc_exec($conn,$sqlFeedback),1);
				if ($countFeedback!=0){
					echo '<font class="redStrong"> ('.$countFeedback.')</font>';

				}

				echo '</a></li>';//end of progress
				echo '</ul></li>';//end of logbook (user)
			}
			if ($menuFlag['view'][3]==1){
				echo '<li><a href="documentationList.php?documentDate='. date('Y-m-d'). '&documentDate2='.date('Y-m-d').'">'.$word[138].'</a></li>'; //E-Documentation
			}
			if ($menuFlag['admin'][3]==1 and $menuFlag['admin'][2]==1){
				echo '<li><a href="#">'.$word[139].'</a><ul>'; //Master Data
				echo '<li><a href="#">'.$word[140].'</a><ul>'; //Master Data Agenda
				echo '<li><a href="roomEquipmentUsingListMaster.php">'.$word[141].'</a></li>'; //Agenda Room/Equipment Master
				echo '<li><a href="agendaUserListMaster.php">'.$word[142].'</a></li>'; //Agenda User Master
				echo '<li><a href="agendaBirthdayListMaster.php">'.$word[143].'</a></li>'; //Agenda Birthday Master
				echo '</ul></li>';//end of master data agenda;
				echo '<li><a href="#">'.$word[144].'</a><ul>'; //Master Data Documentation
				echo '<li><a href="documentCategoryListMaster.php">'.$word[145].'</a></li>'; //Documentation Category Master
				echo '<li><a href="documentArchiveListMaster.php">'.$word[146].'</a></li>'; //Documentation Archive Master
				echo '<li><a href="documentUserListMaster.php">'.$word[147].'</a></li>'; //Documentation User Master
				echo '</ul></li>';//end of Master Data Documentation
				echo '<li><a href="agendaDepartementListMaster.php">'.$word[148].'</a>'; //Departement Master Data
				echo '</ul></li>';//end of Master Data
			}
			if ($menuFlag['admin'][11]==1){
				echo '<li><a href="userAgreementList.php">'.$word[149].'</a></li>'; //User Agreement List
			}
			if ($menuFlag['admin'][17]==1){
				echo '<li><a href="#">'.$word[150].'</a><ul>'; //Polling Admin
				echo '<li><a href="#">'.$word[151].'</a><ul>'; //Polling Theme
				echo '<li><a href="PollingThemeInput.php">'.$word[152].'</a></li>'; //Add Theme
				echo '<li><a href="PollingThemeEdit.php">'.$word[153].'</a></li>'; //Edit Theme
				echo '</ul></li>';
				echo '<li><a href="#">'.$word[154].'</a><ul>'; //Polling Question
				echo '<li><a href="PollingQuestionInput.php">'.$word[155].'</a></li>'; //Add Question
				echo '<li><a href="PollingQuestionEdit.php">'.$word[156].'</a></li>'; //Edit Question
				echo '</ul></li>';
				echo '<li><a href="PollingResult.php">'.$word[157].'</a></li>'; //Polling Result
				echo '</ul></li>'; // end of Polling
			}
			if ($menuFlag['admin'][15]==1){
				echo '<li><a href="#">'.$word[158].'</a><ul>'; //Personal Info
				echo '<li><a href="PersonalInfoInput.php">'.$word[159].'</a></li>';
				echo '<li><a href="PersonalInfoList.php">'.$word[160].'</a></li>';
				echo '</ul></li>'; // end of Personal Info
			}
			if ($menuFlag['admin'][21]==1){		// Manager Agenda
				echo '<li><a href="DeptAgendaList.php?id=all&agendaDate=">Activity of CHR Department</a></li>';
			}
			if($menuFlag['view'][20]==1){
				//$sql_query="SELECT convert(varchar(16),decryptbypassphrase(userLogin,password)) as password FROM tr_login where userLogin ='$NIK'";
				//$md5_password = md5(odbc_result(odbc_exec($conn, $sql_query),1));
				//echo '<li><a href="/SKC/index.php/account/portal_login/'.$NIK.'/'. $md5_password.'">Sharing Knowledge Competition</a></li>';// end of SKC
			}
				echo '<li><a href="0-logout.php?ref=https://culture.happy5.co" target="_blank">KG Life</a></li>';
				echo '<li><a href="0-logout.php?ref=https://performance.happy5.co" target="_blank">KG Impact</a></li>';

			echo '</ul></li>';//end of chr organizer
		}
		if ($menuFlag['admin'][7]==1){
			echo '<li><a href="">Talent Management</a><ul>';
			echo '<li><a href="">Analysis</a><ul>';
			echo '<li><a href="TaM_An_MatchCompt_1.php">Matching Competency</a></li>';
			echo '<li><a href="TaM_An_Fullfillment.php">Fullfillment Plan</a></li>';
			echo '<li><a href="TaM_An_AssessmentNeed.php">Assessment Needs Mapping</a></li>';
			echo '<li><a href="#">Nine Box</a><ul>';
			echo '<li><a href="TaM_An_TalentQuadrant.php">Mapping Talent Quardant</a></li>';
			echo '<li><a href="TaM_An_FitCadreQuadrant.php">Mapping Fit Cadre Quardant</a></li>';
			echo '</ul></li>'; //end of Nine Box
			echo '<li><a href="TaM_An_TalentPool.php">Talent Pool Summary</a></li>';
			echo '</ul></li>'; //end of Analysis
			echo '<li><a href="">Report</a><ul>';
			echo '<li><a href="TaM_Report_PositionStatus.php">Organization Position Status</a></li>';
			echo '<li><a href="TaM_Report_Demographic.php">Demographic</a></li>';
			echo '<li><a href="TaM_Report_AssessmentNeed.php">Assessment Needs</a></li>';
			echo '<li><a href="TaM_Report_PointPA.php">Point PA</a></li>';
			echo '</ul></li>'; //end of Report
			echo '<li><a href="">Talent Dictionary</a><ul>';
			echo '<li><a href="">Competency</a><ul>';
			echo '<li><a href="TaM_KompetensiMaster.php" >Master</a></li>';
		 	echo '<li><a href="TaM_KompetensiLayer.php" >Layer/Jobs</a></li>';
			echo '</ul></li>';//end of Competency
			echo '<li><a href="TaM_Big5.php" > Big 5</a></li>';
			echo '<li><a href="" >Feedback 360</a><ul>';
			echo '<li><a href="TaM_360Topic.php">Topic</a></li>';
			echo '<li><a href="TaM_360Type.php">Evaluator Type</a></li>';
			echo '</ul></li>';//end of Feedback 360
			echo ' <li><a href="TaM_Managerial.php" > Managerial Style</a></li>';
			echo '<li><a href="#" > Development Program</a><ul>';
			echo '<li><a href="TaM_DevProgram_Type.php" >Program</a></li>';
			echo '<li><a href="TaM_DevProgram_CourseCategory.php" >Phase</a></li>';
			echo '<li><a href="TaM_DevProgram_Course.php" >Course</a></li>';
			echo '</ul></li>';//end of Development Program
			echo '</ul></li>';//end of Talent Dictionary
			echo '<li><a href="TaM_TalentAssessment.php" >Talent Assessment</a></li>';
			echo '<li><a href="TaM_TalentListMaster.php" >Talent Pool</a></li>';
			echo '<li><a href="">Master Data</a><ul>';
			echo '<li><a href="TaM_AdminListMaster.php" >Admin</a></li>';
		 	echo '<li><a href="TaM_UserListMaster.php?deptID=ALL" >User</a></li>';
	 		echo '</ul></li>';//end of Master Data
			echo '</ul></li>'; //end of talent Management (admin)
		}
		if ($menuFlag['view'][7]==1){
			echo '<li><a href="">Talent Management</a><ul>';
			echo '<li><a href="TaM_Report.php">View Report</a></li>';
			echo '</ul></li>'; //end of talent Management (view)
		}
		?>


	<li><a href=""><?php echo $word[161]; //Links ?></a><ul>
		<?php

		$today=date('Y-m-d');
		if (odbc_result($queryall,"Unit1Txt")=='CORPORATE HUMAN RESOURCES'){
			echo '<li><a href="Kamus_Kompetensi.php?page=p">'.$word[162].'</a></li>'; //Kamus Kompetensi
		}
		?>
		<!-- <li><a href="0-logout.php?ref=https://km.kompasgramedia.com/?u=<?php //echo $NIK.'-'.$tgllahir.'-'.md5($today); ?>" target="_blank"><?php //echo $word[163]; //Knowledge Management ?></a></li> -->
		<?php
		// $today=date('Y-m-d');
		// $getQryEmail   = "select * from tr_login WHERE userLogin='$NIK' ";
		// $queryEmailResult  = odbc_exec($conn, $getQryEmail);
		// 	$url="http://".$_SERVER['HTTP_HOST'];
		// 	if($url != "http://10.10.55.7"){
		// 		echo '<li><a href="0-logout.php?ref=https://elearning.kompasgramedia.com/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email"). '" target="_blank">'.$word[164].'</a></li> '; //E-Learning
		// 	}else{
		// 		echo '<li><a href="0-logout.php?ref=http://10.10.55.8/redirect/catch.php?u='. $NIK.'-'.md5($today).'-'.odbc_result($queryEmailResult,"email") .'" target="_blank">'.$word[164].'</a></li>'; //E-Learning
		// 	}
		?>
		<li><a href="0-logout.php?ref=http://www.workwithkg.com/ats/eRecruitment/admin" target="_blank"><?php echo $word[165]; //Recruiter ?></a></li>
                <?php

                //WISMA

	            ?>
	            <!--<li><a href="wismalink.php"><? //$word[226]?></a></li>-->
	            <?php



//-----------------------------   E SLIP START -----------------------------------
                //E-SLIP KARYAWAN GORP Contract

                $cekloginpayrollstring="SELECT COUNT(*) as pa FROM ms_niktelp WHERE status='Contract' AND PayAdmin='914' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                    <?php
                }

                //E-SLIP KARYAWAN GORP PERMANENT

                $cekloginpayrollstring="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PayArea = 'KC' and status='Permanent' and PersAdmin='002' AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                    <?php
                }




                //E-SLIP KARYAWAN KCM (KOMPAS CYBER MEDIA)

                $cekloginpayrollstring2="SELECT COUNT(*) as pa
                                        FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='006'
                                                AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                    <?php
                }



                //E-SLIP KARYAWAN KONTAN

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='008'
                                                AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses = odbc_result($queryceklogin,"pa");

                if($payrolllinkakses==1)
                {
                    ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                    <?php
                }




                // E-SLIP KARYAWAN OTOMONTIR dan ANGKASA

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                        WHERE
                                                PayArea = 'KA'
                                                and (status='Permanent' or status='Contract')
                                                and PersAdmin='009'
                                                AND PayAdmin='901'
                                                AND (PersArea='0017' OR PersArea='0140') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                <?php
                }





                //E-SLIP  KARYAWAN GKU

		$cekloginpayrollstring2="select COUNT(*) as pa from ms_niktelp where PayArea = 'KB' and (PersArea = '0023' or PersArea='0060') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    ?>
                    <li><a class="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
                    <?php
                }

//              -------------------------------------------
//		---------------- SLIP END -----------------
//		-------------------------------------------

                //RC Pinjaman GKU

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KB'
                                        and (PersArea = '0023' or PersArea='0060')
                                        and NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">RC pinjaman</a></li>
    <?php
                    }
                }

                //RC Pinjaman KOMPAS COM

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='006'
                                        AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">RC pinjaman</a></li>
    <?php
                    }
                }


                //RC Pinjaman KONTAN

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='008'
                                        AND PayAdmin='901'
                                        AND PersArea='0013' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">RC pinjaman</a></li>
    <?php
                    }
                }

                //RC PINJAMAN OTOMONTIR dan ANGKASA

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='009'
                                        AND PayAdmin='901'
                                        AND (PersArea='0017' OR PersArea='0140') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">RC pinjaman</a></li>
    <?php
                    }
                }



                //RC Pinjaman GORP

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PayArea = 'KC' and status='Permanent' and PersAdmin='002' AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {

                $tblistrcstring="select count(*) as listrc from tb_listRC where PERNR='$NIK'";
                $queryrc = odbc_exec($conn,$tblistrcstring);
                $rcakses = odbc_result($queryrc,"listrc");

                    if($rcakses==1)
                    {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">RC pinjaman</a></li>
    <?php
                    }
                }
//              -------------------------------------------
//		--------  RC PINJAMAN END -----------------
//		-------------------------------------------


                //GORP TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp WHERE PayArea = 'KC' and status='Permanent' and (PersAdmin='002' OR PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '002')) AND PayAdmin='911' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

                //OTOMONTIR DAN ANGKASA TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='009'
                                        AND PayAdmin='901'
                                        AND (PersArea='0017' OR PersArea='0140') AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

                //KONTAN TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='008'
                                        AND PayAdmin='901'
                                        AND PersArea='0013' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }



                //KOMPAS COM TKK

                $cekloginpayrollstring2="SELECT COUNT(*) as pa FROM ms_niktelp
                                WHERE
                                        PayArea = 'KA'
                                        and status='Permanent'
                                        and PersAdmin='006'
                                        AND PayAdmin='901' AND NIK='$NIK'";
                $queryceklogin2 = odbc_exec($conn,$cekloginpayrollstring2);
                $payrolllinkakses2 = odbc_result($queryceklogin2,"pa");

                if($payrolllinkakses2==1)
                {
                    $tblistrcstring="select count(*) as listrc from tb_listTKK where PERNR='$NIK'";
                    $queryrc = odbc_exec($conn,$tblistrcstring);
                    $rcakses = odbc_result($queryrc,"listrc");

                        if($rcakses==1)
                        {
    ?>
                        <li><a class="linkmenupayroll" href="pindahlink.php">Tunj. Kesejahteraan Karyawan (TKK)</a></li>
    <?php
                        }
                }

//              -------------------------------------------
//		--------  TKK PINJAMAN END -----------------
//		-------------------------------------------

				if($NIK=='021620' || $NIK=='007201')
				{
				?>
				<li><a id="linkmenupayroll" href="pindahlink.php">Payroll</a></li>
		<?php
				}

                //-----------------------------   E SLIP END -----------------------------------
                ?>
                <li><a href="0-logout.php?ref=https://pmo.kompasgramedia.com/pmo/" target="_blank"><?php echo $word[166]; //PM Online ?></a></li>
		<li><a href="0-logout.php?ref=http://hrbuspro.kompasgramedia.com/" target="_blank"><?php echo 'HR Business Process'; //PM Online ?></a></li>
		<?php
		if ($menuFlag['view'][8]==1){
			echo '<li><a href="CCIReport_Home.php">'.$word[167].'</a></li>'; //CCI Report
		}

		?>

		<!-- menu inovasi -->
		<?php
		$FI_DEPARTEMEN = odbc_result($queryall,"Unit3Txt");
		$FI_DIVISI = odbc_result($queryall,"Unit2Txt");
		if(odbc_result($queryall,"Unit1Txt")=='')
		{
			$FI_GROUP_ID = odbc_result($queryall,"Unit1");
			$FI_GROUP = odbc_result($queryall,"Unit2Txt");
		}else{
			$FI_GROUP_ID = odbc_result($queryall,"Unit1");
			$FI_GROUP = odbc_result($queryall,"Unit1Txt");
		}
		$FI_SEKSI = odbc_result($queryall,"Unit4Txt");
		$UNIT_EMP = str_replace("&", "AND", $FI_GROUP);
		$KD_UNIT_EMP = str_replace("&", "AND", $FI_GROUP_ID);
		$DIVISI_EMP = str_replace("&", "AND", $FI_DIVISI);
		$SEKSI_EMP = str_replace("&", "AND", $FI_SEKSI);
		$DEPT_EMP = str_replace("&", "AND", $FI_DEPARTEMEN);

		$getQryData   = "select userLogin,convert(varchar(16),decryptbypassphrase(userLogin,password)) as keyword,convert(varchar(16),decryptbypassphrase(userLogin,generatedPassword)),isActive, lock FROM tr_login where userLogin ='$NIK' ";
		$queryDataResult  = odbc_exec($conn, $getQryData);


		//$url="http://".$_SERVER['HTTP_HOST'];
		$key = 'kompasgramedia';

		$string_param = 'u='. $NIK.'-nama='.$namalengkapquery.'-
			alamat='.md5(odbc_result($queryDataResult,"keyword")).'-
			org_id='.$KD_UNIT_EMP.'-unit='.$UNIT_EMP.'-dept='.$DEPT_EMP.'-division='.$DIVISI_EMP.'-section='.$SEKSI_EMP.'-
			time='.date('YmdH:i:s');


		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string_param, MCRYPT_MODE_CBC, md5(sha1($key))));
		$parameterurl= str_replace('+','%2B',$encrypted);


		if($url == "http://10.9.70.30"){

				echo '<li><a href="0-logout.php?ref=http://10.9.60.47/index.php/redirect/catch?p='. $parameterurl .'" target="_blank">'.$word[174].'</a></li> '; //Inovasi
			}else{
				echo '<li><a href="0-logout.php?ref=http://inovasi.kompasgramedia.com/index.php/redirect/catch?p='. $parameterurl.'" target="_blank">'.$word[174].'</a></li>'; //Inovasi
			}
		?>
            <!-- <li><a href="bookinghotel.php" target="_blank">Booking Hotel</a></li> -->
            <?php
            	// if ($NIK=="005290" || $NIK=="007201" || $NIK=="000909" || $NIK=="052962" || $NIK=="001535" || $NIK=="068101" || $NIK=="051855" || $NIK=="066690" || $NIK=="054666" || $NIK=="041132" || $NIK=="056518" || $NIK == "056517" || $NIK == "003478" || $NIK == "015440" || $NIK=="006224" || $NIK == '071046' || $NIK == '064160' || $NIK == '033092') {?>
            		<li><a href="GDN_loginAPI.php">Gramedia.com</a></li>
            <?php 
        	// }

        	$sqlhrss="SELECT COUNT(b.UserLogin) AS JUMLAH FROM ms_ModuleSpecial a,ms_ModuleAdmin b  WHERE a.SpecialName like '%HRSS%' and b.UserLogin='".$NIK."' and a.SpecialID=b.SpecialID and b.isActive=1";
            $StatusHrss=odbc_exec($conn,$sqlhrss);

            if(odbc_result($StatusHrss,"JUMLAH")==0)
            {
            	$sqlidcard="select count(b.UserLogin) as Total_ID from ms_Module a, ms_ModuleAdmin b, ms_ModuleSpecial c
				where b.ModuleID=29 and b.UserLogin='".$NIK."'and c.SpecialID=54";
				        $query_idcard=odbc_exec($conn,$sqlidcard);
				        	$url="http://".$_SERVER['HTTP_HOST'];
				        if(odbc_result($query_idcard,"Total_ID")>0)
				        {
				                 if(trim($url) == "http://10.9.70.30"){
				?>                
				    <li><a href="">ID Card Online</a>
				            <ul>
				                <li><a href="0-logout.php?ref=http://10.9.70.44/backend/portal_login/<?php echo $NIK; ?>/<?php echo $md5_password; ?>/1"><?php echo "ID Card Online SAP"; //  ?></a></li>
				                <li><a href="0-logout.php?ref=http://10.9.70.44/backend/"><?php echo "ID Card Online Non SAP"; // ?></a></li>
				            </ul>
				    </li>
				<?php      }else{  ?>
				    <li><a href="">ID Card Online</a>
				            <ul>
				                <li><a href='0-logout.php?ref=https://idcard.kompasgramedia.com/backend/portal_login/<?php echo $NIK; ?>/<?php echo $md5_password; ?>/1'>ID Card Online SAP</a></li>
				                <li><a href="https://idcard.kompasgramedia.com/backend/"><?php echo "ID Card Online Non SAP"; // ?></a></li>
				            </ul>
				    </li>
				<?php }
				}
            }
            ?>
	</ul></li>
	<li><a href=""><?php echo $word[168]; //Personal Data ?></a><ul>
		<?php if (check_access_odoo($NIK)!=1): ?>
		<li><a href="list_profile_anjab.php"><?php echo $word[169]; //View Data ?></a></li>
		<?php endif ?>
         <?php
		$sqlAdmin="select * from tr_login a inner join ms_ModuleAdmin b
                                on a.userLogin =b.UserLogin where
                                b.UserLogin ='$NIK' and (ModuleID = 12 and SpecialID = 21) and b.isActive=1";
		$Qryadmin=odbc_exec($conn,$sqlAdmin) or die("<p>".odbc_errormsg());

			if(odbc_result($Qryadmin,1)!='')
			{

			if(odbc_result($Qryadmin,1)==$NIK)
			{

				//untuk notif memo bulanan HR
				$akhirBulan = date("Ymt");
				$ms_niktelp = odbc_exec($conn, "SELECT PersAdmin FROM ms_niktelp WHERE NIK = '$NIK'");
				$persadmin = odbc_result($ms_niktelp, "PersAdmin");
				$today = date("d");
				if ($today<5) {
					$awalBulan = date("Ymd", strtotime(date("Y-n-01", strtotime("first day of previous month"))));
				}
				else{
					$awalBulan = date("Ym01");
				}

				function cek_memo_blnan($persadmin, $absenceType, $NIK, $awalBulan, $akhirBulan){
					$persadmin_msg = "((b.PersAdmin = '$persadmin') OR (b.PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '$persadmin')))";

					// untuk membedakan select dan absence type yg digunakan antara cuti melahirkan dan yg lain
					if ($absenceType == 'cm') {
						$select_msg = "DISTINCT(a.AbsenceID), a.*, b.PersAdmin, b.Unit";
						$absenceType_msg = "'1003'";
						$absenceDate_msg="";
					}
					else{
						$select_msg = "b.PersAdmin, b.Unit, a.*, c.AbsenceDate";
						$absenceType_msg = "'1006', '1007'";
					}

					// untuk tambah anak, tambah istri, dan ganti status nikah
					if ($absenceType == "fam") {
						$sql_notif_memo_blnan = "SELECT DISTINCT(C.ChangeRequestID), C.RequestDate, E.NIK, A.UserNIK as NIKAtasan, E.Nama, E.Unit, D.FieldID from PD_ChangeRequestDetailUpdate A
										inner join PD_ChangeRequestInfotype B on B.ChangeRequestInfotypeID=A.ChangeRequestInfotypeID
										INNER JOIN PD_ChangeRequest C ON C.ChangeRequestID = B.ChangeRequestID
										INNER JOIN PD_ChangeRequestDetail D ON D.ChangeRequestID = B.ChangeRequestID
										inner join ms_niktelp E ON C.NIK = E.NIK
										where A.Status = 3 AND A.FlagPA = 1 AND A.StatusReadEmployee = 1 AND A.Date BETWEEN '$awalBulan' AND '$akhirBulan'
										AND B.InfotypeID = 4 AND ((E.PersAdmin = '$persadmin') OR (E.PersAdmin IN(SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '$persadmin'))) AND D.FieldID IN(7, 128)";
					}
					else{
						$sql_notif_memo_blnan = "SELECT $select_msg from tr_Absence a inner join ms_niktelp b on a.NIK = b.NIK inner join tr_absenceDate c ON a.AbsenceID = c.AbsenceID where a.AbsenceType in($absenceType_msg) and (c.AbsenceDate between '$awalBulan' and '$akhirBulan') and a.isApproved = 1 and $persadmin_msg";
					}
					return $sql_notif_memo_blnan;
				}

				$total_notif_memo_blnan = odbc_num_rows(odbc_exec($conn, cek_memo_blnan($persadmin, 'etc', $NIK, $awalBulan, $akhirBulan)));
				$total_notif_memo_blnan += odbc_num_rows(odbc_exec($conn, cek_memo_blnan($persadmin, 'cm', $NIK, $awalBulan, $akhirBulan)));
				$total_notif_memo_blnan += odbc_num_rows(odbc_exec($conn, cek_memo_blnan($persadmin, 'fam', $NIK, $awalBulan, $akhirBulan)));
				/*if ($persadmin != '002') {
					$sql_notif_memo_blnan = odbc_exec($conn, "SELECT b.PersAdmin, a.*, c.AbsenceDate from tr_Absence a inner join ms_niktelp b on a.NIK = b.NIK inner join tr_absenceDate c ON a.AbsenceID = c.AbsenceID where a.AbsenceType in('1003', '1006', '1007') and (c.AbsenceDate between '$awalBulan' and '$akhirBulan') and a.isApproved = 1 and b.PersAdmin = (SELECT PersAdmin FROM ms_niktelp WHERE NIK = '$NIK')");
					// var_dump("SELECT b.PersAdmin, a.*, c.AbsenceDate from tr_Absence a inner join ms_niktelp b on a.NIK = b.NIK inner join tr_absenceDate c ON a.AbsenceID = c.AbsenceID where a.AbsenceType in('1003', '1006', '1007') and (c.AbsenceDate between '$awalBulan' and '$akhirBulan') and a.isApproved = 1 and b.PersAdmin = (SELECT PersAdmin FROM ms_niktelp WHERE NIK = '$NIK')");
				}
				else{
					$sql_notif_memo_blnan = odbc_exec($conn, "SELECT b.PersAdmin, a.*, c.AbsenceDate from tr_Absence a inner join ms_niktelp b on a.NIK = b.NIK inner join tr_absenceDate c ON a.AbsenceID = c.AbsenceID where a.AbsenceType in('1003', '1006', '1007') and (c.AbsenceDate between '$awalBulan' and '$akhirBulan') and a.isApproved = 1 and (b.PersAdmin = (SELECT PersAdmin FROM ms_niktelp WHERE NIK = '$NIK') OR (b.PersAdmin BETWEEN '160' and '287'))");
				}

				$total_notif_memo_blnan = odbc_num_rows($sql_notif_memo_blnan);*/
				// var_dump($total_notif_memo_blnan);
				// end notif memo bulanan hr

				$fce = saprfc_function_discover($rfc,"ZHRFM_GETENTSTRUCTURE");
				if (! $fce ){
					echo "System Error4. Please Try Again Later."; exit;
				}
				saprfc_import ($fce,"FI_PERNR",$NIK);
				saprfc_table_init ($fce,"FI_ENT");
				$rfc_rc = saprfc_call_and_receive ($fce);
				if ($rfc_rc != SAPRFC_OK){
					$FI_ENT2='';
				}else{
					$FI_ENT2 = saprfc_table_read($fce,"FI_ENT",1);
				}
		//		$PersArea=$result['PERAREA'];
				$PersAdm= $FI_ENT2['PERSADMIN'];
				$persadmin_parent_sql = "OR B.PersAdmin in (SELECT PersAdmin_Id FROM HRSS_PersAdmin WHERE PersAdmin_parent = '$PersAdm')";
				$getRequestTotal="select COUNT(DISTINCT A.ChangeRequestID) as total from PD_ChangeRequest A
						inner join ms_niktelp B on A.NIK=B.NIK
						inner join PD_ChangeRequestDetail C on A.ChangeRequestID=C.ChangeRequestID
						where A.Finish = 0 and ChangeRequestInfotypeID!=0 and (PersAdmin='$PersAdm'".$persadmin_parent_sql.") and C.ExecFlagHR is null";
				$resultTotalRequestKaryawan  = odbc_exec($conn, $getRequestTotal);
				$totalReqUpdateKaryawan = odbc_result($resultTotalRequestKaryawan,"total");

				if ($totalReqUpdateKaryawan == 0)
				{
					echo "";
				}else{
					//echo ' ('.$arr['total'];
					$totalReqUpdateKaryawan;
					//echo ')';
				}

		?>
    	<li><a href="PDHR_ApprovalList.php"><?php echo $word[170].' '; //Personal Data HR Approval List ?><font color='#FF0000'><?php echo $totalReqUpdateKaryawan; ?></font></a></li>
        <?php
			}
			}
		?>
		<li><a href="home_update_identity.php"> Update KTP</a></li>
    <li><a href="chpass.php"><?php echo $word[171]; //Change Password ?></a></li>
		<li><a href="0-logout.php"><?php echo $word[172]; //Logout ?></a></li>
	</ul></li>


    <!-- Menu Administrator -->
    <?php
	$sqlAdmin="select * from tr_login a inner join ms_ModuleAdmin b
				on a.userLogin =b.UserLogin where
				b.UserLogin ='$NIK' and (ModuleID = 12 and SpecialID = 11) and b.isActive=1";
	$ResultAdmin=odbc_exec($conn,$sqlAdmin);
	if(odbc_result($ResultAdmin,"userLogin")==$NIK)
	{
	?>
    <li><a href=""><?php echo $word[173]; //Administration ?></a>
        <ul>
            <li><a href="">Module Personal Data Request</a>
                <ul>
                	<li><a href="PDMstListField.php">Master Data Field</a></li>
                    <li><a href="PDMstListDataType.php">Master Data DataType</a></li>
                    <li><a href="PDMstListSection.php">Master Data Section</a></li>
                    <li><a href="PDMstListInfoType.php">Master Data InfoType</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <?php
	}
	?>
    <!-- end Administrator -->

		</ul>
		</div>
		<!-- end of menu-->
		<!-- end of menu-->
