<?php
	$pageTitle="SKKL";
	include "template/top3.php";
	include "include/date_lib.php"; 
	$task	=	$_REQUEST['task'];
  	$task_user = '';
	
	if ($task == md5('viewSKKL'))
	{
		$task_user = 'viewSKKL';
		$id = $_REQUEST['SKKLid'];
		// $TotalSKLL	= odbc_exec($conn,"select COUNT(*) as TotalKaryLembur from tb_SKKLTransaction where SKKLID=$id");
		odbc_execute($TotalSKLL = odbc_prepare($conn,"select COUNT(*) as TotalKaryLembur from tb_SKKLTransaction where SKKLID=?"),array($id));
		// $SKKLQry	= odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID=$id");
		odbc_execute($SKKLQry = odbc_prepare($conn,"select * from tb_SKKLTransaction where SKKLID=?"),array($id));
				
		//get total data nik lembur
		$totalNIKLembur = odbc_result($TotalSKLL,'TotalKaryLembur');
		$assignmentDate = odbc_result($SKKLQry,'AssignmentDate');
		$agenda = odbc_result($SKKLQry,'Agenda');
		$beginTime = odbc_result($SKKLQry,'BeginDate');
		$endTime = odbc_result($SKKLQry,'EndDate');
		$atasanNIK = odbc_result($SKKLQry,'SuperiorNIK');
		$firstApprove = odbc_result($SKKLQry,14);
		$secondApprove = odbc_result($SKKLQry,16);
	}
?>
<h2>View SKKL</h2>
<div class="well span9">

            <div class="row">
            <div class="span1">NIK :</div>
	        <div class="span3"><?php echo $NIK ?></div>
            </div>
<div class="row">
            <div class="span1">Name :</div>
            <div class="span3"><?php 
				// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$NIK'");

				odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array($NIK));

                		echo $nama=odbc_result($getnama, "Nama");	
				?>
                </div>
            </div>
            <div class="row">
                          <div class="span1">Assign To :</div>
            <div class="span2">&nbsp;</div>
            </div>
            <div class="row">
            <div class="span1">Assignment Number :</div>
            <div class="span3">
				  <?php 
				  	echo $totalNIKLembur;
				  ?></div>
                </div>

				<table class="table table-striped table-bordered table-condensed">
                  	<?php
				  		// $TotalSKLL	= odbc_exec($conn,"select COUNT(*) as TotalKaryLembur from tb_SKKLTransaction where 
						// 					AssignmentDate='$assignmentDate' and SuperiorNIK='$atasanNIK' and BeginDate='$beginTime' and EndDate='$endTime'
						// 					and Agenda='$agenda'");

						odbc_execute($TotalSKLL = odbc_prepare($conn,"select COUNT(*) as TotalKaryLembur from tb_SKKLTransaction where 
											AssignmentDate=? and SuperiorNIK=? and BeginDate=?' and EndDate=?
											and Agenda=?"),array($assignmentDate,$atasanNIK,$beginTime,$endTime,$agenda));
				
						// $SKKLQry	= odbc_exec($conn,"select * from tb_SKKLTransaction where 
						// 					AssignmentDate='$assignmentDate' and SuperiorNIK='$atasanNIK' and BeginDate='$beginTime' and EndDate='$endTime'
						// 					and Agenda='$agenda'");

						odbc_execute($SKKLQry = odbc_prepare($conn,"select * from tb_SKKLTransaction where 
											AssignmentDate=? and SuperiorNIK=? and BeginDate=? and EndDate=?
											and Agenda=?"),array($assignmentDate,$atasanNIK,$beginTime,$endTime,$agenda));
				
				//get total data nik lembur
				$totalNIKLembur = odbc_result($TotalSKLL,'TotalKaryLembur');
				
				//looping nik
				$b=0;
				for($b=0;$b<$totalNIKLembur;$b++)
				{
					while($rowlembur=odbc_fetch_row($SKKLQry))
					{
				
					?>
                    <div class="row">
                    <div class="span1">NIK :</div>
                    <div class="span1"><?php echo odbc_result($SKKLQry,'NIK'); ?></div>
                    
                    <div class="span1">Nama :</div>
                    <div class="span6">
					<?php
			// $getnama = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '".odbc_result($SKKLQry,'NIK')."'");

			odbc_execute($getnama = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array(odbc_result($SKKLQry,'NIK')));

                echo $nama=odbc_result($getnama, "Nama");
                        
                    ?></div>
                    </div>
                    <?php
						}}
					?>
                  </table>
                <div class="row">
                <div class="span1">Date :</div>
                <div class="span3"><?php echo convertDateAdis($assignmentDate) ?></div>
                </div>
   <div class="row">
                <div class="span1">Time :</div>
                <div class="span3">
				  <?php echo $beginTime ?>
                    To <?php echo $endTime ?></div>
                </div>
   <div class="row">
                <div class="span1">Agenda :</div>
                <div class="span3"><?php echo $agenda ?></div>
                </div>
              </table>
   <div class="row">
                <div class="span2">Superior to Approve :</div>
                <div class="span1">&nbsp;</div>
            </div>
               <div class="row">
                <div class="span2">Superior number :</div>
                <div class="span2">
              <?php
			  
			  	//kondisi jika firstapprover dan secondapprover kosong tidak ada TR
				if($firstApprove == NULL && $secondApprove == NULL)
					{
						echo '0';
					}
					elseif($firstApprove != NULL && $secondApprove == NULL)	
					{
						echo '1';
					}
					elseif($firstApprove != NULL && $secondApprove != NULL)	
					{
						echo '2';
					}
              ?>
			  </div>
            </div>
   			<div class="row">
                <div class="span1">
                <table class="table table-striped table-bordered table-condensed">
<div class="row">
                <div class="span1">
                <table class="table table-striped table-bordered table-condensed">
                  <?php
				  	
					//kondisi jika firstapprover dan secondapprover kosong tidak ada TR
					if($firstApprove == NULL && $secondApprove == NULL)
					{
						//nothing
					}
					elseif($firstApprove != NULL && $secondApprove == NULL)
					{
						// $getnama1 = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$firstApprove'");

						odbc_execute($getnama1 = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array($firstApprove));

               					 $firstnama=odbc_result($getnama1, "Nama");
									
						 echo '<div class="row">
									<div class="span10">NIK : '.$firstApprove.'&nbsp;&nbsp;Nama : '.$firstnama.'</div>
                    			</div>';
					}elseif($firstApprove != NULL && $secondApprove != NULL)
					{
						// $getnama1 = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$firstApprove'");
						odbc_execute($getnama1 = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array($firstApprove));

                                                $firstnama=odbc_result($getnama1, "Nama");
						// $getnama2 = odbc_exec($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = '$secondApprove'");
						odbc_execute($getnama2 = odbc_prepare($conn,"SELECT Nama FROM ms_niktelp WHERE NIK = ?"),array($secondApprove));
                                                $secondnama=odbc_result($getnama2, "Nama");
						
						echo '<div class="row">
									<div class="span10">NIK : '.$firstApprove.'&nbsp;&nbsp;Nama : '.$firstnama.'</div>
                    			</div>
								
								<div class="row">
									<div class="span10">NIK : '.$secondApprove.'&nbsp;&nbsp;Nama : '.$secondnama.'</div>
                    			</div>';
								
					}
					//kondisi jika firstapprover tidak kosong dan secondapprover kosong ada TR satu
					//kondisi jika firstapprover tidak kosong dan secondapprover tidak kosong ada TR dua
                  
                  ?>
                  </table></div>
                </div>
              </table></div>
            </div>
          
    <br></center>
                <center><input type="Submit"  value="Close" onClick="self.parent.tb_remove(true);"  /></center>
            </div>
  

<?php
  include "template/bottom3.php"
?>


