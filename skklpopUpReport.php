<?php
	$pageTitle="SKKL";
	include "template/top3.php";
	include "include/date_lib.php"; 

    $task	=	$_REQUEST['task'];
	$task_user = '';
			
	//for edit
	if ($task == md5('viewSKKLReport'))
	{
		$skklid = $_REQUEST['id'];
		// $ReportQry=odbc_exec($conn,"select * from tb_SKKLTransaction where isApproved='true' 
		// 							and SKKLID='$skklid'");

		odbc_execute($ReportQry = odbc_prepare($conn,"select * from tb_SKKLTransaction where isApproved='true' 
									and SKKLID=?"),array($skklid));

		$nikLembur = odbc_result($ReportQry,'NIK');						
		$assignDate = odbc_result($ReportQry,'AssignmentDate');
		$begin = odbc_result($ReportQry,'BeginDate');
		$end = odbc_result($ReportQry,'EndDate');
		$agenda = odbc_result($ReportQry,'Agenda');
		$revNum = odbc_result($ReportQry,'RevisionNum');
		$superior = odbc_result($ReportQry,'SuperiorNIK');
		$firstSuperior = odbc_result($ReportQry,15);
		$secondSuperior = odbc_result($ReportQry,16);
		$inputDate = odbc_result($ReportQry,'InputDate');
		$firstApproveDate = odbc_result($ReportQry,21);
		$secondApproveDate = odbc_result($ReportQry,22);
	}
			
?>
<h2>Report SKKL</h2>
<div class="well span9">

<div class="row">
<div class="span2">NIK :</div>
<div class="span3"><?php echo $NIK;?></div>
</div>
<div class="row">
<div class="span2">Name :</div>
<div class="span5">
<?php 
		//call SAP Data
		$fce = saprfc_function_discover($rfc,"ZHRFM_CV");
		if (! $fce )
		{
			echo "System Error. Please Try Again Later."; exit;
		}
			saprfc_import ($fce,"FI_PERNR",$nikLembur);
			saprfc_import ($fce,"FI_PERNR_DIAKSES",$nikLembur);
			saprfc_table_init ($fce,"FI_CV");
			$rfc_rc = saprfc_call_and_receive ($fce);
			if ($rfc_rc != SAPRFC_OK)
			{
					if ($rfc == SAPRFC_EXCEPTION )
							echo "System Error. Please Try Again Later.";
					else
							echo "System Error. Please Try Again Later."; exit;
			}
			$rows = saprfc_table_rows ($fce,"FI_CV");
			$FI_CV = saprfc_table_read($fce,"FI_CV",1);
					
		echo	$nama=$FI_CV['NAMALENGKAP']; ?>
        </div>
	</div>
<div class="row">
    <div class="span2">Unit :</div>
	<div class="span5">
	<?php 
					
		//call SAP Data
		$fce = saprfc_function_discover($rfc,"ZHRFM_CV");
		if (! $fce )
		{
			echo "System Error. Please Try Again Later."; exit;
		}
			saprfc_import ($fce,"FI_PERNR",$nikLembur);
			saprfc_import ($fce,"FI_PERNR_DIAKSES",$nikLembur);
			saprfc_table_init ($fce,"FI_CV");
			$rfc_rc = saprfc_call_and_receive ($fce);
			if ($rfc_rc != SAPRFC_OK)
			{
					if ($rfc == SAPRFC_EXCEPTION )
							echo "System Error. Please Try Again Later.";
					else
							echo "System Error. Please Try Again Later."; exit;
			}
			$rows = saprfc_table_rows ($fce,"FI_CV");
			$FI_CV = saprfc_table_read($fce,"FI_CV",1);
					
		echo	$nama=$FI_CV['UNIT']; 
		?></div>
            </div>
            <div class="row">
			<div class="span2">Assignment Date :</div>
				<div class="span2"><?php echo convertDateAdis($assignDate) ?></div>
            </div>
			<div class="row">
            <div class="span2">Time :</div>
			<div class="span2"><?php echo $begin ?> To <?php echo $end ?></div>
            </div>
			<div class="row">
            <div class="span2">Agenda :</div>
            <div class="span7"><?php echo $agenda ?></div>
            </div>
   			<div class="row">
            <div class="span2">Time Event :</div>
            <div class="span5">
              <?php
			  
			  	//variable time
			  	$timeEvent= substr($assignDate,0,4).substr($assignDate,5,2).substr($assignDate,8,2);
				
				$fce = saprfc_function_discover($rfc,"ZHRFM_LIST_TIME");
				if (! $fce )
				{
					echo "System Error. Please Try Again Later."; exit;
				}
				saprfc_import ($fce,"FI_AKHIR",$timeEvent);
				saprfc_import ($fce,"FI_AWAL",$timeEvent);
				saprfc_import ($fce,"FI_PERNR",$nikLembur);
				saprfc_table_init ($fce,"FI_ABSEN");
				$rfc_rc = saprfc_call_and_receive ($fce);
				if ($rfc_rc != SAPRFC_OK)
				{
					if ($rfc == SAPRFC_EXCEPTION )
						echo "System Error. Please Try Again Later.";
					else
						echo "System Error. Please Try Again Later."; exit;
				}
				$FI_RTEXT = saprfc_export ($fce,"FI_RTEXT");
				$rows = saprfc_table_rows ($fce,"FI_ABSEN");
				$FI_ABSEN = saprfc_table_read ($fce,"FI_ABSEN",$rows);
				
				echo $FI_ABSEN['JAMMASUK'].'&nbsp;To&nbsp;'.$FI_ABSEN['JAMPULANG']; 
				
				?>
              </div>
            </div>
			<div class="row">
            <div class="span2">HR Revision Number :</div>
            <div class="span5"><?php echo $revNum ?></div>
            </div>
			<div class="row">
            <div class="span1">
                <table class="table table-striped table-bordered table-condensed">                
                <!-- superior -->
                <?php
					
					if($superior != NULL)
					{
				?>		
			<div class="row">
            <div class="span1">
Superior, InputDate : <?php echo convertDateAdis($inputDate) ?></div>
                </div>
			<div class="row">
                <div class="span1">
			   <?php echo $superior ?> - <?php 
					
					//call SAP Data
					$fce = saprfc_function_discover($rfc,"ZHRFM_CV");
					if (! $fce )
					{
						echo "System Error. Please Try Again Later."; exit;
					}
						saprfc_import ($fce,"FI_PERNR",$superior);
						saprfc_import ($fce,"FI_PERNR_DIAKSES",$superior);
						saprfc_table_init ($fce,"FI_CV");
						$rfc_rc = saprfc_call_and_receive ($fce);
						if ($rfc_rc != SAPRFC_OK)
						{
								if ($rfc == SAPRFC_EXCEPTION )
										echo "System Error. Please Try Again Later.";
								else
										echo "System Error. Please Try Again Later."; exit;
						}
						$rows = saprfc_table_rows ($fce,"FI_CV");
						$FI_CV = saprfc_table_read($fce,"FI_CV",1);
								
				echo		$nama=$FI_CV['NAMALENGKAP']; 
							$unit=$FI_CV['UNIT']; 
				?> <br /><?php echo $unit ?></div>
                </div>
                
						<?php 
                        if($superior != NULL && $firstSuperior != NULL && $secondSuperior == NULL)
                            {
                        ?>
                        
                      <div class="row">
                <div class="span5">Superior 1, Approve Date :<?php echo convertDateAdis($firstApproveDate) ?></div>
                        </div>
                        <div class="row">
                <div class="span8">

                          <?php echo $firstSuperior ?> - <?php 
                            
                            //call SAP Data
                            $fce = saprfc_function_discover($rfc,"ZHRFM_CV");
                            if (! $fce )
                            {
                                echo "System Error. Please Try Again Later."; exit;
                            }
                                saprfc_import ($fce,"FI_PERNR",$firstSuperior);
                                saprfc_import ($fce,"FI_PERNR_DIAKSES",$firstSuperior);
                                saprfc_table_init ($fce,"FI_CV");
                                $rfc_rc = saprfc_call_and_receive ($fce);
                                if ($rfc_rc != SAPRFC_OK)
                                {
                                        if ($rfc == SAPRFC_EXCEPTION )
                                                echo "System Error. Please Try Again Later.";
                                        else
                                                echo "System Error. Please Try Again Later."; exit;
                                }
                                $rows = saprfc_table_rows ($fce,"FI_CV");
                                $FI_CV = saprfc_table_read($fce,"FI_CV",1);
                                        
                        echo		$namaFirst=$FI_CV['NAMALENGKAP']; 
                                    $unitFirst=$FI_CV['UNIT']; 
                        ?> <br /><?php echo $unitFirst ?></div>
                        </div>
                        
                        <?php
                            }
                            else if($superior != NULL && $firstSuperior != NULL && $secondSuperior != NULL)
                            {
                        ?>
				<div class="row">
                <div class="span5">Superior 2, Approve Date : <?php echo convertDateAdis($secondApproveDate) ?></div>
                        </div>
                        <div class="row">
                <div class="span8">
                          <?php echo $secondSuperior ?> - <?php 
                            
                            //call SAP Data
                            $fce = saprfc_function_discover($rfc,"ZHRFM_CV");
                            if (! $fce )
                            {
                                echo "System Error. Please Try Again Later."; exit;
                            }
                                saprfc_import ($fce,"FI_PERNR",$secondSuperior);
                                saprfc_import ($fce,"FI_PERNR_DIAKSES",$secondSuperior);
                                saprfc_table_init ($fce,"FI_CV");
                                $rfc_rc = saprfc_call_and_receive ($fce);
                                if ($rfc_rc != SAPRFC_OK)
                                {
                                        if ($rfc == SAPRFC_EXCEPTION )
                                                echo "System Error. Please Try Again Later.";
                                        else
                                                echo "System Error. Please Try Again Later."; exit;
                                }
                                $rows = saprfc_table_rows ($fce,"FI_CV");
                                $FI_CV = saprfc_table_read($fce,"FI_CV",1);
                                        
                        echo		$namaSecond=$FI_CV['NAMALENGKAP']; 
                                    $unitSecond=$FI_CV['UNIT']; 
                        ?> <br /><?php echo $unitSecond ?>
                        </div>
                        </div>
                        
                        <?php
                            }
					}
				?>
                
              </table></div>
            </div>
                            <center><input type="button"  value="Close" onClick="self.parent.tb_remove(true);" /></center>

          
      </form>
<?php
  include "template/bottom3.php"
?>

