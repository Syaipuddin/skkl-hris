<!DOCTYPE HTML>
<?php
	$pageTitle ='Pop Up Training Schedule'; //setting title html
	include "template/top3.php"; //header
	include "TMS/connection.php";//connection

	if(!empty($_GET['id']))
		$id = addslashes($_GET['id']);
	else	
		$id = NULL;
		
	/*LnD Validation*/
	include "Training_ListFunction.php";
	
	if(!is_LnD($NIK))
		echo "<script>window.parent.parent.tb_remove();</script>";
		
	
	/*query for details*/
	if ($id)
	{
		$q = "SELECT	ts.Announcement, t.duration, ts.training_id, ts.training_sch_id, ts.batch AS batch,t.code AS training_code, 
						t.title AS training_title, ts.quota AS quota, i.label AS institution, ts.start_time AS [start], 
						ts.end_time AS [end], ts.place, ts.notes, ts.institution_id, ts.PersAdmin AS admin_id, ts.is_Active
				FROM	trm_training t, trm_training_sch ts, trm_institution i
				WHERE	
						ts.training_sch_id  = ? AND 
						t.training_id 		= ts.training_id AND
						ts.institution_id   = i.institution_id";
		// $r = odbc_exec($connTMS,$q);
		odbc_execute($r = odbc_prepare($connTMS,$q), array($id));
		
		//get sum of trainer that will be used
		$q2 = "SELECT count(*) AS total FROM trm_training_sch_trainer tst WHERE tst.training_sch_id  = ?"; 
		// $r2 = odbc_exec($connTMS,$q2);
		odbc_execute($r2 = odbc_prepare($connTMS,$q2), array($id));
	}
			
?>

<div class="row">
	<div class="span12">
		<?php 
		 echo ($id?"<h1> Update Training Schedule </h1>":"<h1> Add Training Schedule </h1>");
		?>
		<div class="row">
			<form autocomplete="off" name="ScheduleAdd" id="ScheduleAdd" action="Training_PopUpTrainingScheduleProses.php?tu=<?php echo ($id!=NULL?md5("editTrainingSchedule"):md5("addTrainingSchedule")); ?>" method="POST" class="well span9">
				<table style="background-color:transparent;" class="span13">
					<tr>
						<td>Training Code</td>
						<td>:&nbsp </td>
						<td>
							<select name="trainingCode" id="trainingCode" style="width:70%;"><?php
									//string query
									$queryTrainingCode = "SELECT training_id,code,title FROM trm_training WHERE is_Active = 1";
									//result set
									$rsTrainingCode	   = odbc_exec($connTMS, $queryTrainingCode);
									$tr_id = odbc_result($r,'training_sch_id');
									while(odbc_fetch_row($rsTrainingCode))
									{
										if(odbc_result($rsTrainingCode,'training_id') == odbc_result($r,'training_id')){
											echo "<option value='".odbc_result($rsTrainingCode,'training_id')."' selected>".odbc_result($rsTrainingCode,'code').' - '.odbc_result($rsTrainingCode,'title')."</option>";
										}
										else{
											echo "<option value='".odbc_result($rsTrainingCode,'training_id')."'>".odbc_result($rsTrainingCode,'code').' - '.odbc_result($rsTrainingCode,'title')."</option>";
										}
									}?></select>
						</td>
					</tr>
					
					<tr id="feedBackTrainingCode" style="display:none; color:red;">
						<td>&nbsp </td>
						<td>&nbsp </td>
						<td>This field is required.</td>
					</tr>
					
					<tr>
						<td>Batch</td>
						<td>:&nbsp </td>
						<td><input type="text" name="batch" id="batch" value="<?php echo odbc_result($r,'batch'); ?>" style="width:10%;"/></td>
					</tr>
					
					<tr>
						<td>No of day(s)</td>
						<td>:&nbsp </td>
						<td><input type="text" name="day" id="day" value="<?php echo odbc_result($r,'duration'); ?>" style="width:10%;" readonly /></td>
					</tr>
					
					<!-- for ajax date details-->
					<tr style="display:block;" id="expandParentDay">
						<td colspan="3" >
							<div id="expandDay" name="expandDay" style="width:90px;">
							</div>
						</td>
					</tr>
					
					<input type="hidden" value="<?php echo $id;?>" id="idTrainingSchedule" name="idTrainingSchedule"/>
					<tr>
						<td>Quota</td>
						<td>:&nbsp </td>
						<td><input type="text" name="quota" id="quota" value="<?php echo odbc_result($r,'quota'); ?>" style="width:10%;"/> person(s)
						</td>
					</tr>
					
					<tr id="feedBackQuota" style="display:none; color:red;">
						<td>&nbsp </td>
						<td>&nbsp </td>
						<td>This field is required.</td>
					</tr>
					
					<tr>
						<td>Institution</td>
						<td>:&nbsp </td>
						<td>
							<select name="institution" id="institution" class="span4">
								<?php
									//string query
									$queryInstitution = "SELECT institution_id, label FROM trm_institution WHERE is_Active = 1";
									//result setnya
									$rsInstitution    = odbc_exec($connTMS, $queryInstitution);
									
									while(odbc_fetch_row($rsInstitution)){
										if(odbc_result($rsInstitution,'institution_id') == odbc_result($r,'institution_id')){
											echo "<option value='".odbc_result($rsInstitution,'institution_id')."' selected>".odbc_result($rsInstitution,'label')."</option>";
										}
										else{
											echo "<option value='".odbc_result($rsInstitution,'institution_id')."'>".odbc_result($rsInstitution,'label')."</option>";
										}
									}
								?>
							</select>
						</td>
					</tr>
					
					<tr id="feedBackInstitution" style="display:none; color:red;">
						<td>&nbsp </td>
						<td>&nbsp </td>
						<td>This field is required.</td>
					</tr>
					
					<tr>
						<td>Trainer(s)</td>
						<td>:&nbsp </td>
						<?php
							$queryTrainer = "SELECT count(trainer_id) AS count FROM trm_training_sch_trainer WHERE training_sch_id = ?";
							// $rsTrainer    = odbc_exec($connTMS, $queryTrainer);
							odbc_execute($rsTrainer 	 = odbc_prepare($connTMS,$queryTrainer), array($tr_id));
						?>
						<td> <input type="text" name="trainer" id="trainer" style="width:10%" value="<?php echo odbc_result($rsTrainer,'count'); ?>" onready="ajaxFunction('trainer');" maxlength="2"/> person(s)</td>
					</tr>
					
					<!-- for ajax trainer-->
					<tr style="display:none;" id="expandParentTrainer">
						<td colspan="3">
							<div id="expandTrainer" name="expandTrainer" style="width:90px;">
							</div>
						</td>
					</tr>
					
					<tr>
						<td>Start Time</td>
						<td>:</td>
						<td>
							<table style="background-color:transparent;">
							<tr>
								<td>
									<div id="timePickerStart" class="input-append" style="width:100px; "> 
										<input data-format="hh:mm" type="text" style="width:50%;" maxlength="5" value="<?php if($id){$tm1 = odbc_result($r,'start'); $tm = explode(":",$tm1,-1); echo $tm[0].':'.$tm[1];} ?>" id="startTime" name="startTime" />
										<span class="add-on">&nbsp
											<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										</span>
									</div>
								</td>
								<td>&nbsp End Time:&nbsp
								</td>
								<td>
									<div id="timePickerEnd" class="input-append" style="margin-left:7px; width:100px; float:none; "> 
										<input data-format="hh:mm" type="text" style="width:50%;" maxlength="5" value="<?php if($id){$tm2 = odbc_result($r,'end'); $tm = explode(":",$tm2,-1); echo $tm[0].':'.$tm[1];} ?>" id="endTime" name="endTime"/>
										<span class="add-on">&nbsp
											<i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
										</span>
									</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					
					<tr>
						<td>Place</td>
						<td>:&nbsp </td>
						<td><input type="text" name="place" id="place" value="<?php echo odbc_result($r,'place'); ?>" class="span5"/></td>
					</tr>
					
					<tr>
						<td valign="top" >Notes</td>
						<td valign="top">:&nbsp </td>
						<td><textarea name="notes" id="notes" style="width:480px; resize:vertical;" rows="5"><?php echo odbc_result($r,'notes'); ?></textarea> </td>
					</tr>
					
					<tr>
						<td valign="top">Training Announcement &nbsp </td>
						<td valign="top">:&nbsp </td>
						<td ><textarea name="announcement" id="announcement" style="width:480px; resize:vertical;"  rows="5" ><?php echo html_entity_decode(odbc_result($r,'Announcement')); ?></textarea></td>
					</tr>
					
					<tr>
						<td>Active</td>
						<td>:&nbsp </td>
						<td><input type="checkbox" name="active" id="active" style="margin-bottom:5px;" checked /></td>
					</tr>
					
					<tr id="rowPersAdmin" style="display:none;">
						<td>HR Admin</td>
						<td>:&nbsp </td>
						<td>
							<select name="admin" id="admin">
								<?php
									//string query
									$queryAdmin = "SELECT pers_admin_code, pers_admin_text FROM trm_pers_admin";
									//result setnya
									$rsAdmin    = odbc_exec($connTMS, $queryAdmin);
									while(odbc_fetch_row($rsAdmin))
									{
										if(odbc_result($rsAdmin,'pers_admin_code') == odbc_result($r,'admin_id')){
											echo "<option value='".odbc_result($rsAdmin,'pers_admin_code')."' selected>".odbc_result($rsAdmin,'pers_admin_text')."</option>";
										}
										else{
											echo "<option value='".odbc_result($rsAdmin,'pers_admin_code')."'>".odbc_result($rsAdmin,'pers_admin_text')."</option>";
										}
									}
								?>
							</select></td>
					</tr>
					
					<tr id="feedBackPersAdmin" style="display:none; color:red;">
						<td>&nbsp </td>
						<td>&nbsp </td>
						<td>This field is required.</td>
					</tr>
					
					<?php 
						/*Showing update by and update on when edit training schedule was selected*/
						if($id)
						{ 	//get resultset from trm_training_sch
							// $rslt 	 = odbc_exec($connTMS,"SELECT update_by, update_on FROM trm_training_sch WHERE training_sch_id = $id");

							odbc_execute($rslt 	 = odbc_prepare($connTMS,"SELECT update_by, update_on FROM trm_training_sch WHERE training_sch_id = ?"), array($id));

							$n   	 = odbc_result($rslt,'update_by');
							// $getNama = odbc_result(odbc_exec($conn,"SELECT Nama FROM PORTAL.dbo.ms_niktelp WHERE NIK = '$n'"),'Nama');	
							$getNama = odbc_result(odbc_execute(odbc_prepare($connTMS,"SELECT Nama FROM PORTAL.dbo.ms_niktelp WHERE NIK = ?"), array($n)),'Nama');
					?>
							<tr style="margin-top:15px;">
								<td style="font-size:90%;">Last Update By</td>
								<td>:&nbsp </td>
								<td><i><?php echo $getNama; ?></i></td>
							</tr>
							
							<tr>
								<td style="font-size:90%;">Last Update On</td>
								<td>:&nbsp </td>
								<td><i><?php echo date("l, j F Y, H:i:s",strtotime(odbc_result($rslt,'update_on'))); ?></i></td>
							</tr>
					<?php 
						} 
					?>
					
					<tr><td colspan="3">&nbsp </td></tr>
					<tr>
						<td colspan="3">
							<center>
								<button class="btn btn-primary" type="submit" onclick="return checkInput();" ><i class="icon-white icon-file"></i> Save</button>
								&nbsp
								<a href="#"><div class="btn" onclick="javascript:window.parent.parent.tb_remove();"><i class="icon-remove"></i> Close</div></a>
							</center>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>


<script type="text/javascript" src="TMS/bootstrap-timepicker/jquery-1.8.3.min.js"></script> 
<script type="text/javascript" src="TMS/bootstrap-timepicker/bootstrap.min.js"></script>
<script type="text/javascript" src="TMS/bootstrap-timepicker/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="TMS/bootstrap-timepicker/bootstrap-datetimepicker.pt-BR.js"></script>
<link href="TMS/bootstrap-timepicker/bootstrap-combined.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" media="screen" href="TMS/bootstrap-timepicker/bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>

<script type="text/javascript">
	
	//check input, if null show error message and mark red the textboxes
	function checkInput()
	{	
		var flag = 1;
		if (!$("#quota").val() || $("#quota").val() == 0 )
		{
			//glow textbox for checking validity for input
			$("#quota").css( "border", "1px solid red" );
			$("#feedBackQuota").show();
			flag = 0;
		}
		else 
			$("#feedBackQuota").hide();
		
		if ($("#trainingCode").val() == "" || !$("#trainingCode").val() )
		{	
			$("#trainingCode").css( "border", "1px solid red" );
			$("#feedBackTrainingCode").show();
			flag = 0;
		}
		else
			$("#feedBackTrainingCode").hide();
			
		if ($("#institution").val() == "" || !$("#institution").val())
		{	
			$("#institution").css( "border", "1px solid red" );
			$("#feedBackInstitution").show();
			flag = 0;
		}
		else 
			$("#feedBackInstitution").hide();
		
		//if training code isn't null, check admin if admin null, hide validation report
		if( $("#trainingCode").val() )
		{
			//it's in house training
			if ( document.getElementById("isInHouse").value == 1)
			{
				//if admin value was null show validation report
				if ($("#admin").val() == "" || !$("#admin").val()  )
				{	
					$("#admin").css( "border", "1px solid red" );
					$("#feedBackPersAdmin").show();
					flag = 0;
				}
				else 
					$("#feedBackPersAdmin").hide();
			}
			else
			{
				$("#feedBackPersAdmin").hide();
			}
		}
		
		if (flag == 0)
			return false;
		else
			return true;
	}
	function ajaxFunction(task)
	{	
		if (task == "day")
		{
			var x = $("#trainingCode").val();	
			var epd =document.getElementById("expandParentDay");
			
			if (x > 0)
				epd.style.display="block";
			else
				epd.style.display="none";
			
		}else if (task == "trainer")
		{
			document.getElementById("expandParentTrainer").style.display="block";
		}
		
		var ajaxRequest;  // The variable that makes Ajax possible!
		
		try{
			// Opera 8.0+, Firefox, Safari
			ajaxRequest = new XMLHttpRequest();
		} catch (e){
			// Internet Explorer Browsers
			try{
				ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try{
					ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e){
					// Something went wrong
					alert("Your browser broke!");
					return false;
				}
			}
		}
		// Create a function that will receive data sent from the server
		ajaxRequest.onreadystatechange = function()
		{
			if(ajaxRequest.readyState == 4)
			{
				
				if ( task == "day")
				{
					var ajaxDisplay = document.getElementById('expandDay');
					
					//add textbox(s)
					ajaxDisplay.innerHTML = ajaxRequest.responseText;
					
					//check is it a inhousetraining?
					if ( document.getElementById("isInHouse").value == 1)
					{
						$('#admin').prop('selectedIndex', -1);
						$("#admin").val("<?php echo ($id?odbc_result($r,'admin_id'):"");?>");
						$("#rowPersAdmin").show();
					}
					else
					{	//set by default '000', true the validation
						//$("#admin").val('000');
						$("#feedBackPersAdmin").hide();
						$("#admin").css( "border", "" );
						$("#rowPersAdmin").hide();
					}
					
					//set jquery for each date time picker
					$(function() {
						
						$('[id^=datetimepicker]').datetimepicker({
							format: 'yyyy-MM-dd',
							language: 'en',
							pickTime: false
						  });
					  });
					  
					//set duration value
					document.getElementById("day").value = document.getElementById("durValue").value;
					
				}
				else if (task == "trainer")
				{
					var ajaxDisplay = document.getElementById('expandTrainer');
					
					//add textbox(s)
					ajaxDisplay.innerHTML = ajaxRequest.responseText;
				
					var j = 0;
					for (j ;j < document.getElementById("trainer").value ; j++)
					{
						document.getElementById("cboTrainer["+j+"]").selectedIndex = -1;
					}
					
					<?php
						if ($id)
						{	
							$arrayTrainer = array();
		
							//get resultset from query Trainer
							// $resultQueryTrainer = odbc_exec($connTMS, "SELECT trainer_id FROM trm_training_sch_trainer tst WHERE tst.training_sch_id = $id"); 
							odbc_execute($resultQueryTrainer = odbc_prepare($connTMS,"SELECT trainer_id FROM trm_training_sch_trainer tst WHERE tst.training_sch_id = ?"), array($id));
							
							$ct = 0;//counter
							
							while(odbc_fetch_row($resultQueryTrainer))
							{	
								$arrayTrainer[$ct++] = odbc_result($resultQueryTrainer,'trainer_id');
							}
							
							for($i = 0 ; $i < $ct ; $i++)
							{
								echo "document.getElementById('cboTrainer[$i]').value = $arrayTrainer[$i];";
							}
						}
					?>
				}
			}
		}
		
		if (task == "day")
		{
			var queryString = "?d="+x;
			
			queryString+="<?php echo ($id?"&i=$id":"");?>";
			ajaxRequest.open("GET", "Training_ExpandSchedule.php" + queryString, true);
		}
		else if (task == "trainer")
		{
			var queryString = "?t=" + document.getElementById("trainer").value;
			queryString+="<?php echo ($id?"&i=$id":"");?>";
			
			ajaxRequest.open("GET", "Training_ExpandTrainer.php" + queryString, true);
		}
		ajaxRequest.send(null); 
	}//end of ajax function
	
	//handling validation quota
	$("#quota").keyup(function(){
		if ( $("#feedBackQuota").is(':visible') )
		{
			if ( $("#quota").val())
			{
				$("#feedBackQuota").hide();
				$("#quota").css( "border", "" );
			}
		}
	});
	
	//handling validation institution
	$("#institution").change(function(){
		if ( $("#feedBackInstitution").is(':visible') )
		{
			if ( $("#institution").val())
			{
				$("#feedBackInstitution").hide();
				$("#institution").css( "border", "" );
			}
		}
	});
	
	//handling validation admin
	$("#admin").change(function(){
		if ( $("#feedBackPersAdmin").is(':visible') )
		{
			if ( $("#admin").val())
			{
				$("#feedBackPersAdmin").hide();
				$("#admin").css( "border", "" );
			}
		}
	});
	
	//on change event for training code
	$("#trainingCode").change(function() {
		ajaxFunction("day");
		if ( $("#feedBackTrainingCode").is(':visible') )
		{
			if ( $("#trainingCode").val())
			{
				$("#feedBackTrainingCode").hide();
				$("#trainingCode").css( "border", "" );
			}
		}

	});
	
	$("#trainer").keyup(function(){
		//jalankan jika dia angka dan tidak null
		if (!isNaN($("#trainer").val()) && $("#trainer").val())
			ajaxFunction("trainer");
		else
			document.getElementById("expandParentTrainer").style.display="none";}	
	);
	
	$("#trainer").ready(function(){
		//jalankan jika dia angka dan tidak null
		if (!isNaN($("#trainer").val()) && $("#trainer").val())
			ajaxFunction("trainer");
		else
			document.getElementById("expandParentTrainer").style.display="none";}	
	);
	
	$("#day").change(function(){
		//jalankan jika dia angka dan tidak null
		if (!isNaN($("#day").val()) && $("#day").val())
			ajaxFunction("day");
		else
			document.getElementById("expandDay").style.display="none";}	
	);
	
	/*
	$("#day").ready(function(){
		//jalankan jika dia angka dan tidak null
		if (!isNaN($("#day").val()) && $("#day").val())
			ajaxFunction("day");
		else
			document.getElementById("expandDay").style.display="none";}	
	);*/
	
	
	CKEDITOR.replace( 'announcement' ,{
		customConfig : '',
		extraPlugins : 'uicolor',
		resize_enabled : false,
		width:490,
		toolbarCanCollapse : true,
		uiColor: '#7F9FE6',
		disableNativeSpellChecker:true,
		scayt_autoStartup:false,
		toolbar :[['Cut','Copy','Paste','-','Undo','Redo'],[ 'Find','Replace','-','SelectAll'],['Bold','Italic','Underline'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ]]
	} );
	  
	$(document).ready(function(){
		
		//default choose -1, except admin choose 1
		$('#trainingCode').prop('selectedIndex', -1);
		$('#institution').prop('selectedIndex', -1);
		$('#admin').prop('selectedIndex', -1);
		
		//load jquery for time picker
		$('#timePickerStart,#timePickerEnd').datetimepicker({
		  format: 'hh:mm',
		  pickSeconds: false,  
		  pickDate: false
		});
		
		
		//fill the form if update
		$("#trainingCode").val("<?php echo ($id?odbc_result($r,'training_id'):"");?>");
		$("#batch").val("<?php echo ($id?odbc_result($r,'batch'):"");?>");
		$("#quota").val(<?php echo ($id?odbc_result($r,'quota'):"");?>);
		$("#notes").val("<?php echo ($id?preg_replace('/^\s+|\n|\r|\s+$/m', '',(odbc_result($r,'notes'))):"");?>");
		$("#place").val("<?php echo ($id?odbc_result($r,'place'):"");?>");
		$("#startTime").val("<?php echo ($id?substr(odbc_result($r,'start'),0,5):"");?>");
		$("#endTime").val("<?php echo ($id?  substr(odbc_result($r,'end'),0,5):"");?>");
		$("#institution").val(<?php echo ($id?odbc_result($r,'institution_id'):"");?>);
		$("#trainer").val(<?php echo ($id?odbc_result($r2,'total'):"");?>);
		$("#announcement").val("<?php /*regex to remove line breaks*/echo ($id?
		 preg_replace('/^\s+|\n|\r|\s+$/m', '',html_entity_decode( odbc_result($r,'Announcement'))):"");?> ");
		 
		<?php
			if ($id)
			{
				if(odbc_result($r,'is_Active') == 0)
					echo "$('#active').attr('checked',false);";

			}
		
			//jika update, maka tampilkan detail datenya
			echo ($id?"ajaxFunction('day');":"");
			echo ($id?"ajaxFunction('trainer');":"");
		   
		   //hide default choice for persadmin 000
		   //$("#admin").find("option").eq(0).hide();
		?>
		
			
		
	});   
</script>
	