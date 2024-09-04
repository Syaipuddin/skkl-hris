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

	//untuk notifikasi object on loan
        // include "countOnLoan.php";
        // $totalLoan = countLoan($NIK);
        // echo $totalLoan;
		//end object on loan

		$sqlall="SELECT * FROM ms_EmpOrg WHERE NIK='00".$NIK."'";
		$queryall = odbc_exec($conn,$sqlall);
			$PersAdmin=odbc_result($queryall, 'PERSADMIN');

		$sqlallname="SELECT Nama, PayArea, Unit FROM ms_niktelp WHERE NIK= $NIK";
		$queryallname = odbc_exec($conn,$sqlallname);
		$namalengkapquery = odbc_result($queryallname,"Nama");
		// buat medrem
		$payarea_login = odbc_result($queryallname,"PayArea");
                
                //CARI TRAVEL ONLINE PASKA
                $sqltravelonlinePASKA="select COUNT(*) jumlahTravelonline from tb_travel a, tb_travel_pasca b where b.isApproved = 0 and a.FirstApproverNIK='$NIK' and a.TravelID=b.TravelID";
		$querytravelonlinePASKA = odbc_exec($conn,$sqltravelonlinePASKA);
		$jumlahTravelonlinePASKA = odbc_result($querytravelonlinePASKA,"jumlahTravelonline");
                
                //CARI TRAVEL ONLINE
                $sqltravelonline="select COUNT(*) jumlahTravelonline from tb_travel where isApproved = 0 and FirstApproverNIK='$NIK'";
		$querytravelonline = odbc_exec($conn,$sqltravelonline);
		$jumlahTravelonline = odbc_result($querytravelonline,"jumlahTravelonline");

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
		//mapping akses userLogin ke module / menu yang ada (selesai)

?>
	<div id="menuBar" >
		<a tabindex="0"  class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="hierarchybreadcrumb"><span class="ui-icon ui-icon-triangle-1-s">
		</span><?php echo $word[45]; //Menu ?></a>
		<div id="news-items" class="hidden">
		<ul style="min-height: 300px;">
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
				$StatusAtasanQry=odbc_exec($conn,"select * from tr_login where userLogin = '$NIK'");
				if (odbc_result($StatusAtasanQry,"ModuleRoleID") == 2 ){
					//echo '<li><a href="underMaintenance.php" >'.$word[70].'</a></li>'; //Create SKKL
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
				<li><a href="#"><?php echo $word[75]; //Cancelation ?></a>
					<ul>
						<li><a href="cancelReqBefore.php" ><?php echo $word[76]; //Before Approved ?></a></li>
						<li><a href="cancelReqAfter.php" ><?php echo $word[81]; //After Approved ?></a></li>
						<li><a href="cancelReqStatus.php" ><?php echo $word[82]; //Cancelation Status ?></a></li>
					</ul>
				</li>
				<li><a href="#"><?php echo $word[53]; //History ?></a>
					<ul>
						<li><a href="reqCutiHistory.php" ><?php echo $word[83]; //Leave History ?></a></li>
						<li><a href="reqCutiCancelHistory.php" ><?php echo $word[78]; //Cancelation History ?></a></li>
					</ul>
				</li>

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
	</ul>
</li>
<li><a href=""><?php echo $word[168]; //Personal Data ?></a><ul>
		<li><a href="list_profile_anjab.php"><?php echo $word[169]; //View Data ?></a></li>
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
</ul>
</div>
		<!-- </div> -->
		<!-- end of menu-->
		<!-- end of menu-->