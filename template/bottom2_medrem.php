		</div>

		<hr>  
		</div>
			<footer>
        		<p>&copy; Corporate Human Resources - Kompas Gramedia 2012 
					<br /> <?php 
					include "language/Bottom_library_word.php";
					echo $word[0]; //Best view ?> Firefox 4+, Chrome, Internet Explorer 8+, Safari 5+
                </p>
			</footer>
		</div> <!-- /container --> 
	<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-1.7.2.min.js"></script>		
		<script src="js/jquery-ui.js"></script>
		<script src="js/jquery.validate.js"></script>
		<script src="js/fg.menu.js"></script>
		<script src="js/fg.menu.setting.js"></script>
		<script src="js/bootstrap-popover.js" ></script>
		<script src="js/f_clone_Notify.js"></script>
		<script src="js/thickbox.js"></script>
		<!--<script type="text/javascript" src="js/jquery.snow.min.1.0.js" ></script>-->
		<script type="text/javascript">$('.row div[class^="span"]:last-child').addClass('last-child');</script>
    <script>
            $(function(){
                $(".linkmenupayroll").click(function(e){
                    e.preventDefault();
                    return false;
                });
             });
//		$(document).ready( function(){
//    			$.fn.snow();
//		});
    </script>
</body>
</html>

<?php

if(isset($total_medrem) && $total_medrem!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Employee Request Medical Notification :</font></strong><br><a href=mr_hrs_medrem_status.php><font style=font-size:12px;>Need Approval $total_medrem for Employee Request Medical</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($total_tunjSekolah) && $total_tunjSekolah!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Child Benefit Request Notification :</font></strong><br><a href=tunjSekolah_approval.php><font style=font-size:12px;>Need Approval $total_tunjSekolah for Child Benefit Request</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($idcardComment) && $checkIdCard){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Employee Request ID Card Notification :</font></strong><br><font style=font-size:12px;>$idcardComment</font>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($totalSuratJaminan) && $totalSuratJaminan!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Hospital Guarantee Letter Notification :</font></strong><br><a href=SuratJaminan_ApprovalStep1.php><font style=font-size:12px;>Need Approval $totalSuratJaminan for Hospital Guarantee Letter</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($accBeasiswa) && $accBeasiswa!=NULL && $today<=$tgl_selesai+14){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Beasiswa Register Notification : </font></strong><br><a href=Beasiswa_PendaftaranSiswa_Hasil.php><font style=font-size:12px;>Please Check Registration result</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($tesBeasiswa) && $tesBeasiswa!=NULL && $today<=$tgl_selesai+14){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Beasiswa Acceptance Notification : </font></strong><br><a href=Beasiswa_TesBeasiswa_Hasil.php><font style=font-size:12px;>Please Check Scholarship result</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($totalReqUpdateKaryawan) && $totalReqUpdateKaryawan!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Employee Request Update Notification :</font></strong><br><a href=PDHR_ApprovalList.php><font style=font-size:12px;>Need Approval $totalReqUpdateKaryawan for Employee Request Update</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if (isset($totalSMSGateway) &&  $totalSMSGateway !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SMS Gateway:</font></strong><br><a href=frmSMSGatewayStatus.php>Need approval $totalSMSGateway for SMS Gateway</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if (isset($resultCountHRAppMloan) &&  $resultCountHRAppMloan !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan HR:</font></strong><br><a href=mLoanHRApprovalListHistory.php>Need approval $resultCountHRAppMloan for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ( isset($resultCountAppMloanFirst) &&  $resultCountAppMloanFirst !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanFirst for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ( isset($resultCountAppMloanSecond) && $resultCountAppMloanSecond !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanSecond for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ( isset($resultCountAppMloanThird) && $resultCountAppMloanThird !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanThird for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}


if (isset($countSubt1) &&  $countSubt1 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Substitution Notification :</font></strong><br><a href=Sub_SubtitutionRequestConfirmationList.php>$countSubt1 pending confirmation</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countSubt2) &&  $countSubt2 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Substitution Notification :</font></strong><br><a href=Sub_SubtitutionApprovalList.php>$countSubt2 pending approval</a>');sNotify.alterNotifications('chat_msg');</script>";
} 
if (isset($countHRHD3) &&  $countHRHD3 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Help Desk Notification :</font></strong><br><a href=HRHD_ApprovalList.php>$countHRHD3 answer(s) waiting to approve</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countHRHD1) && $countHRHD1 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Help Desk Notification :</font></strong><br><a href=HRHD_QuestionList.php>$countHRHD1 question(s) waiting to answer</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countHRHD2) &&  $countHRHD2 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Help Desk Notification :</font></strong><br><a href=HRHD_QuestionList.php>$countHRHD2 answer(s) reject</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countLLC3) && $countLLC3 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Labor Law Consultation Notification :</font></strong><br><a href=LLC_ApprovalList.php>$countLLC3 answer(s) waiting to approve</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countLLC) && $countLLC !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Labor Law Consultation Notification :</font></strong><br><a href=LLC_QuestionList.php>$countLLC question(s) waiting to answer</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($countFeedback) && $countFeedback !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Logbook+ Feedback Notification :</font></strong><br><a href=logbook_invTech_progres.php>$countFeedback Logbook+ Feedback(s) waiting to fill</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($totalLogbookReq) && $totalLogbookReq !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Logbook+ Notification :</font></strong><br><a href=logbook_invTech_list.php>Unread request $totalLogbookReq for Technical Support</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($totaldataApproveSKKL) && $totaldataApproveSKKL !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklStatusSuperior.php>Need approval $totaldataApproveSKKL for SKKL Status Superior</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if (isset($totaldata2) && $totaldata2 !=0){
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklStatusBawahan.php>Need accepted $totaldata2 for SKKL Status</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($totalHR) && $totalHR !=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklHRList.php> Need Approval $totalHR for SKKL HR</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if (isset($totalAbsenceCuti) && $totalAbsenceCuti!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Absence Notification :</font></strong><br><a href=absenceRequestApproval.php>Need Approval $totalAbsenceCuti for Absence</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalCutiCancel) && $totalCutiCancel!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Leave Notification :</font></strong><br><a href=reqCutiApprovalCancelReq.php>Need Approval $totalCutiCancel for Cancelation Request Approval</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalCutiApp) && $totalCutiApp!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Leave Notification :</font></strong><br><a href=reqCutiApproval.php>Need Approval $totalCutiApp for Leave</a>');
					sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalAttendance) && $totalAttendance!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Attendance Notification :</font></strong><br><a href=reqAttendanceApproval.php>Need Approval $totalAttendance for Attendance</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalAssessment) && $totalAssessment!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Assessment Notification :</font></strong><br><a href=AIS_approval.php>Need Approval $totalAssessment for Assessment</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalHRPOnline) && $totalHRPOnline!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>HRP Online Notification :</font></strong><br><a href=MSS_HRPonline_Approval.php>Need Approval $totalHRPOnline for HRP Online</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalHRPOnlineHR) && $totalHRPOnlineHR!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>HRP Online Notification :</font></strong><br><a href=MSS_HRPonline_ApprovalHR.php>Need Approval $totalHRPOnlineHR for HRP Online As HR</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if(isset($totalLoan) && $totalLoan!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Object On Loan Notification :</font></strong><br><a href=list_profile_anjab.php?id=3>Item on loan near due date</a>');sNotify.alterNotifications('chat_msg');</script>";
}
odbc_close($conn);
odbc_close($connSISDM);
unset($_SESSION['auth_mykg']);
include "include/sapdisconnect.php";
ob_flush();
// include "survey_dobloo_script.php";
include "popup_banner_djp_script.php";
include "heregistrasi_script.php";
?>
