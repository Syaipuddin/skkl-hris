<?php 
//CREATED BY DIMAS PN
//USED BY
// MANAGER SELF SERVICE MODUL
?>
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
		<script src="js/jquery-1.9.1.js"></script>		
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.validate.js"></script>
		<script src="js/bootstrap.min.js" ></script>
		<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="js/dataTables.material.min.js"></script>
		<!-- <script type="text/javascript" src="js/html2canvas.min.js"></script> -->
		<script src="js/printPreview.js"></script>

		<script src="js/fg.menu.js"></script>
		<script src="js/fg.menu.setting.js"></script>
		<script src="js/bootstrap-popover.js" ></script>
		<script src="js/bootstrap-tooltip.js" ></script>
		<script src="js/f_clone_Notify.js"></script>
		<script src="js/thickbox.js"></script>
		<script type="text/javascript">$('.row div[class^="span"]:last-child').addClass('last-child');</script>
    <link rel="stylesheet" type="text/css" href="js/fullcalendar/fullcalendar/fullcalendar.css" />
   <!--  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <script type="text/javascript" src="js/fullcalendar/fullcalendar/fullcalendar.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/fontawesome/css/fontawesome-all.css" />
<!--<script type="text/javascript" src="js/jquery.snow.min.1.0.js" ></script>-->
    <style>
    	#TB_window {
								position: fixed;
								background: #ffffff;
								z-index: 102;
								color: #000000;
								display: none;
								border: 4px solid #525252;
								text-align: left;
								top: 0%;
								left: 50%;
								}
    </style>

    <style>
    	

    	.body.modal-open {
    overflow: visible;
}

    </style>
    <script>
        $(function(){
//            alert();
            $("#linkmenupayroll").click(function(e){
                alert("test");
                e.preventDefault();
                return false;
            });
            
            $(".linkmenupayroll").click(function(e){
                alert("test");
                e.preventDefault();
                return false;
            });
         });
//	$(document).ready( function(){
//    $.fn.snow();
//});
$(document).ready(function(){
 	});
    </script>
    <script>

			function checkAuth(){
				$.ajax({
                    url: "http://202.146.1.111/API/checkAuth_myKG.php",
                    type: "POST",
                    data: "token="+"'.$_SESSION['token'].'",
                    cache: false,
                    success: function(msg){
                    	console.log(msg);
                    }
                  });
			}
			</script>
    
    <!-- chatBot Thomas -->
    <!-- <div>
	    <div id="chat-boxBtn" class="chat-boxBtn">
	    	<a id="chatAssist" type="button" class="btn btn-primary"><i class="fas fa-comments"></i> Chat Assistant</a>
	    </div>

	    <div id="chatClose" class="judul pull-right text-right" style="display: none;background: transparent;">
	    	<h6 id="title" class="pull-left">Chat 1.0 Beta Version</h6>
	    	<a id="chatBtnClose" type="button" class="pull-right"><i class="fas fa-times " style="padding: 4px"></i></a>
	    </div>

	    <div id="chatbox">
	    	 <iframe  height="360" id="iframeChatbox" class="chatbox" style="display: none;width:350px" src="http://10.10.55.25/chat/ChatController/index/<?php echo $NIK;?>"></iframe>
	    </div>
    </div> -->

    <!-- Modal Thomas-->
	<!-- <div  class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	        <div id="modalBodyTable"></div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button type="button" id="mauPrint" class="btn btn-primary">Print Preview</button>
	      </div>
	    </div>
	  </div>
	</div>

    <a href="http://10.10.55.25/chat/" target="_blank"><?php echo $NIK;?></a> -->
    <!-- chatBot Thomas -->

    <style>
    #exampleModal{
    	position: absolute;
    	width: 850px;
    	left: 45%;
    	top:100%;
    	overflow: visible;
    	max-height: 1000px;
    	z-index: -999999;
    }
    	#iframeChatbox{
    		margin-top: 20px;
    }
    	}
    	#title{
    		background-color: white;
    		-moz-border-radius: 12px;
			-webkit-border-radius: 12px;
			border-radius: 12px;

			-moz-box-shadow: 4px 4px 14px #000;
			-webkit-box-shadow: 4px 4px 14px #000;
			box-shadow: 4px 4px 14px #000;
			z-index: 9999;
    		cursor: default;
    		padding: 0px 5px 0px 5px
    
    	}
    	#title:hover{
    		cursor: default;
    	}

    	#chatBtnClose{
    		background-color: red;
    		-moz-border-radius: 12px;
		  -webkit-border-radius: 12px;
		  border-radius: 12px;
		  z-index: 9999;

		  -moz-box-shadow: 4px 4px 14px #000;
		  -webkit-box-shadow: 4px 4px 14px #000;
		  box-shadow: 4px 4px 14px #000;

    	}
    	#chatBtnClose:hover{
    		cursor: pointer;
    	}

    	.judul{
 			position:fixed;
 			width:324px;
 			background-color:rgba(255,255,255,1);
		  	right:55px;
		  	bottom:388px;
    	}
        .chat-boxBtn {
	/*		  font:normal normal 11px/1.4 Tahoma,Verdana,Sans-Serif;*/
			  color:#333;
			  width:132px; 
			  border-bottom:none;
			  background-color:rgba(0,0,0,0);
			  position:fixed;
			  right:55px;
			  bottom:0;
			  z-index:999;
			  -webkit-box-shadow:1px 1px 5px rgba(0,0,0,0);
			  -moz-box-shadow:1px 1px 5px rgba(0,0,0,0);
			  box-shadow:1px 1px 5px rgba(0,0,0,0);
		}
		.chatbox {
			  font:normal normal 11px/1.4 Tahoma,Verdana,Sans-Serif;
			  color:#333;
			  width:320px; 
			  border-bottom:none;
			  background-color:rgba(0,0,0,0);
			  position:fixed;
			  right:55px;
			  bottom:28px;
			  z-index:999;
			  -webkit-box-shadow:1px 1px 5px rgba(0,0,0,0);
			  -moz-box-shadow:1px 1px 5px rgba(0,0,0,0);
			  box-shadow:1px 1px 5px rgba(0,0,0,0);
		}
    </style>
</body>
</html>
<!-- script Thomas -->
 <script language="javascript">
  function hello(string){
   var name=string
   $('#containerTabel').remove();
   $('#modalBodyTable').append(string);
    $("#exampleModal").css('z-index',999999);
   $("#exampleModal").modal('show');



   // document.getElementById('myAnchor').value=name;
   }

$("#mauPrint").printPreview({
  obj2print:'#containerTabel'
});

// function printData(){
// 	var divToPrint= document.getElementById("#containerTabel");
// 	newWin = window.open("");
// 	newWin.document.write($('.#containerTabel').html());
// 	newWin.print();
// 	newWin.close();
// }

  </script>



<script>
	var i = 0;
	$("#chatAssist").click(function(){
	 	i++;
	 	if(i%2 == 1){
	 		$("#iframeChatbox").show();
	 		$("#chatClose").show();
	 	} else {
	 		$("#iframeChatbox").hide();
	 		$("#chatClose").hide();
	 	}
	})
	$("#chatBtnClose").click(function(){
	 		$("#iframeChatbox").hide();
	 		$("#chatClose").hide();
	 		i=0;
	})
</script>

<?php

//MULAI QUERY JOHAN untuk IDCARD
///
$query='select *   FROM [IDCARD].[dbo].[tb_stock] where qty_limit >= stock and flag=1';
$hasil= odbc_exec($conn,$query);
$a=0;



while($row_unit=odbc_fetch_object($hasil))
	{
	$x=null;
	$nama_item=$row_unit->nama_item;
	$stock_now=$row_unit->stock;
	$limit_item=$row_unit->qty_limit;
	$queryx='select ma.UserLogin
  					from ms_ModuleAdmin ma, ms_niktelp nt, ms_ModuleSpecial ms 
  					where (ma.ModuleID = 26 and ma.UserLogin = nt.NIK) and (ma.SpecialID = ms.SpecialID ) and ms.SpecialName like'."'HRSS_IDCARD_INVENTORY'" ;
	$hasilx= odbc_exec($conn,$queryx);
	while($x=odbc_fetch_object($hasilx))
		{
		if ($NIK==$x->UserLogin)
			 {
				echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Inventory Notification:</font></strong><br><font style=font-size:12px;>$nama_item has reached limit</font></a>');sNotify.alterNotifications('chat_msg');</script>";
			}
		}
	
	}
////
$queryxy='select ma.*,ms.SpecialName, nt.Nama 
  					from ms_ModuleAdmin ma, ms_niktelp nt, ms_ModuleSpecial ms 
  					where (ma.ModuleID = 26 and ma.UserLogin = nt.NIK) and (ma.SpecialID = ms.SpecialID ) and ms.SpecialName like'."'HRSS_IDCARD_BILLING_MASTER'";
$get_unitxy = odbc_exec($conn,$queryxy);
$nik_master=odbc_result($get_unitxy, 'UserLogin');

$queryx="  select * from [IDCARD].[dbo].[tb_bill_history_header]
  						where flag_Payed is null and flag_complaint is not NULL ";
$get_unitx = odbc_exec($conn,$queryx);
$has_complaint=0;
$complain_counter=0;
$complain_note='z';

while ($e=odbc_fetch_object($get_unitx)) 
	{
	$x=$e->id_Transaction;

	if($x=='' || $x==NULL)
		{
		$has_complaint=0;
		//echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>no complaint</font></strong><br><a href=billingReport_idcard.php><font style=font-size:12px;> aa5</font></a>');sNotify.alterNotifications('chat_msg');</script>";
		break;
		}
	else
		{
		$has_complaint=1;
		$complain_counter++;
		if($complain_counter>1)
			{
			$complain_note=$complain_counter." complains ";
			}
		else $complain_note=$complain_counter." complain ";
		}

	}


if($NIK==$nik_master && $has_complaint==1)
{
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Idcard Complain Notification :</font></strong><br><a href=billingReport_idcard.php><font style=font-size:12px;> $complain_note</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}


////
///
////start for bilconfirm
$query11="SELECT top 1 PersAdminText
    			FROM [PORTAL].[dbo].[ms_niktelp]
    			where NIK =" ."'".$NIK."'";
    $get_unitx = odbc_exec($conn,$query11);
    $persAdminText_now=odbc_result($get_unitx, 'PersAdminText');

$query=' select count(unit) as jumlah
  FROM [IDCARD].[dbo].[tb_bill_history_header]
  where (flag_Payed is null and flag_complaint is null ) and  SUBSTRING(CONVERT(varchar,bill_end_date,112),0,9) >= SUBSTRING(CONVERT(varchar,GETDATE(),112),0,9) and hr_Unit ='."'".$persAdminText_now."'";
$hasil= odbc_exec($conn,$query);
$total_need_to_confirm=odbc_result($hasil, 'jumlah');

$x=null;
if($total_need_to_confirm>0)
{	
	// echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>ID Card Confirmationa Notification:</font></strong><br><a href=billingConfirm.php><font style=font-size:12px;> $persAdminText_now to confirm(s)</font></a>');sNotify.alterNotifications('chat_msg');</script>";	
/////

	$queryx='select ma.*,ms.SpecialName, nt.Nama from ms_ModuleAdmin ma, ms_niktelp nt, ms_ModuleSpecial ms
   where ma.ModuleID = 29 and ma.UserLogin = nt.NIK and ma.SpecialID = ms.SpecialID  Order by UserLogin
			';
	$hasilx= odbc_exec($conn,$queryx);
	while($x=odbc_fetch_object($hasilx))
		{
		if ($NIK==$x->UserLogin)
			 {
			 	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>ID Card Confirmation Notification:</font></strong><br><a href=billingConfirm.php><font style=font-size:12px;> $total_need_to_confirm Billing to confirm(s)</font></a>');sNotify.alterNotifications('chat_msg');</script>";

				
			}
		}
}
////end bill confirm 

//END QUERY JOHAN untuk IDCARD

if(isset($jumlahTravelonlinePASKA) && $jumlahTravelonlinePASKA!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Travel Online Pasca Request Notification :</font></strong><br><a href=TravelOnline_Pasca_Approval.php><font style=font-size:12px;>Need Approval $jumlahTravelonlinePASKA for Employee Request Pasca Travel Online </font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($jumlahTravelonline) && $jumlahTravelonline!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Travel Online Request Notification :</font></strong><br><a href=TravelOnline_Approval.php><font style=font-size:12px;>Need Approval $jumlahTravelonline for Employee Request Travel Online</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}
// var_dump('total_medrem'.$total_medrem);
if(isset($total_medrem) && $total_medrem!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Employee Request Medical Notification :</font></strong><br><a href=mr_hrs_medrem_status.php><font style=font-size:12px;>Need Approval $total_medrem for Employee Request Medical</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($idcardComment) && $checkIdCard){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Employee Request ID Card Notification :</font></strong><br><font style=font-size:12px;>$idcardComment</font>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($totalSuratJaminan) && $totalSuratJaminan!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Hospital Guarantee Letter Notification :</font></strong><br><a href=SuratJaminan_ApprovalStep1.php><font style=font-size:12px;>Need Approval $totalSuratJaminan for Hospital Guarantee Letter</font></a>');sNotify.alterNotifications('chat_msg');</script>";
}

if(isset($total_tunjSekolah) && $total_tunjSekolah!=0){
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline;font-size:12px;>Child Benefit Request Notification :</font></strong><br><a href=tunjSekolah_approval.php><font style=font-size:12px;>Need Approval $total_tunjSekolah for Child Benefit Request</font></a>');sNotify.alterNotifications('chat_msg');</script>";
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

if(isset($totalDecree) && $totalDecree!="0"){
//	var_dump($totalDecree);
	echo "<script type='text/javascript'>sNotify.addToQueue('<strong><font style=text-decoration:underline>Online Decree Notification :</font></strong><br><a href=redirect.php?to=hrss&redirect=decree%2Fdecree>".$totalDecree." decree Need to be print</a>');sNotify.alterNotifications('chat_msg');</script>";
}

odbc_close($conn);
odbc_close($connSISDM);
unset($_SESSION['auth_mykg']);
include "include/sapdisconnect.php";
ob_flush();
// include "survey_dobloo_script.php";
include "heregistrasi_script.php";
include "popup_banner_djp_script.php";
echo "test hohohohohoho";
?>