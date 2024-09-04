		</div>

		<hr>  
		</div>
			<footer>
        		<p>HR Portal ver 2.0 &copy; Corporate Human Resources - Kompas Gramedia 2012
					<br /> Best view Firefox 4+, Chrome, Internet Explorer 8+, Safari 5+
                </p>
			</footer>
		</div> <!-- /container --> 
	<!-- Le javascript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/jquery-1.7.2.min.js"></script>		
		<script src="js/jquery-ui-1.8.16.custom.min.js"></script>
		<script src="js/jquery.validate.js"></script>
		<script src="js/fg.menu.js"></script>
		<script src="js/fg.menu.setting.js"></script>
	
		<script src="js/f_clone_Notify.js"></script>
		<script src="js/thickbox.js"></script>
		<script type="text/javascript">$('.row div[class^="span"]:last-child').addClass('last-child');</script>

</body>
</html>

<?php

if ($resultCountHRAppMloan !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan HR:</font></strong><br><a href=mLoanHRApprovalListHistory.php>Need approval $resultCountHRAppMloan for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ($resultCountAppMloanFirst !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanFirst for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ($resultCountAppMloanSecond !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanSecond for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ($resultCountAppMloanThird !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Motorcycle Loan :</font></strong><br><a href=mLoanApprovalStatus.php>Need approval $resultCountAppMloanThird for MotorcycleLoan</a>');sNotify.alterNotifications('chat_msg');</script>";
}


if ($countSubt1 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Substitution Notification :</font></strong><br><a href=Sub_SubtitutionRequestConfirmationList.php>$countSubt1 pending confirmation</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($countSubt2 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Substitution Notification :</font></strong><br><a href=Sub_SubtitutionApprovalList.php>$countSubt2 pending approval</a>');sNotify.alterNotifications('chat_msg');</script>";
} 
if ($countHRHD3 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Help Desk Notification :</font></strong><br><a href=HRHD_ApprovalList.php>$countHRHD3 answer(s) waiting to approve</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($countHRHD !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Help Desk Notification :</font></strong><br><a href=HRHD_QuestionList.php>$countHRHD question(s) waiting to answer</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($countLLC3 !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Labor Law Consultation Notification :</font></strong><br><a href=LLC_ApprovalList.php>$countLLC3 answer(s) waiting to approve</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($countLLC !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Labor Law Consultation Notification :</font></strong><br><a href=LLC_QuestionList.php>$countLLC question(s) waiting to answer</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($countFeedback !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Logbook+ Feedback Notification :</font></strong><br><a href=logbook_invTech_progres.php>$countFeedback Logbook+ Feedback(s) waiting to fill</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($totalLogbookReq !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Logbook+ Notification :</font></strong><br><a href=logbook_invTech_list.php>Unread request $totalLogbookReq for Technical Support</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($totaldataApproveSKKL !=0)
{
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklStatusSuperior.php>Need approval $totaldataApproveSKKL for SKKL Status Superior</a>');sNotify.alterNotifications('chat_msg');</script>";
}

if ($totaldata2 !=0){
		echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklStatusBawahan.php>Need accepted $totaldata2 for SKKL Status</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($totalHR !=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>SKKL Notification :</font></strong><br><a href=skklHRList.php> Need Approval $totalHR for SKKL HR</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if ($totalAbsenceCuti!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Absence Notification :</font></strong><br><a href=absenceRequestApproval.php>Need Approval $totalAbsenceCuti for Absence</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if($totalCutiCancel!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Leave Notification :</font></strong><br><a href=reqCutiApprovalCancelReq.php>Need Approval $totalCutiCancel for Cancelation Request Approval</a>');sNotify.alterNotifications('chat_msg');</script>";
}
if($totalCutiApp!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Leave Notification :</font></strong><br><a href=reqCutiApproval.php>Need Approval $totalCutiApp for Leave</a>');
					sNotify.alterNotifications('chat_msg');</script>";
}
if($totalAttendance!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Attendance Notification :</font></strong><br><a href=reqAttendanceApproval.php>Need Approval $totalAttendance for Attendance</a>');sNotify.alterNotifications('chat_msg');</script>";
}
odbc_close($conn);
odbc_close($connSISDM);
include "include\sapdisconnect.php";
ob_flush() ?>