<?php
	$pageTitle="SKKL";
	include "template/top3.php";
	include "include/date_lib.php"; 
	$task	=	$_REQUEST['task'];
	$task_user = '';
			
	//for edit
	if ($task == md5('confirmSKKL'))
	{
		$task_user = 'confirmSKKL';
		$id = $_REQUEST['SKKLid'];
		$firstApprove = $_REQUEST['firstNIK'];
		$secondApprove=$_REQUEST['secondNIK'];
		// $SKKLQRY	= odbc_exec($conn,"select * from tb_SKKLTransaction where SKKLID='$id'");

		odbc_execute($SKKLQRY = odbc_prepare($conn,"select * from tb_SKKLTransaction where SKKLID=?"),array($id));

		//get total data nik lembur
		$agenda = odbc_result($SKKLQRY,'Agenda');
		$beginTime = odbc_result($SKKLQRY,'BeginDate');
		$endTime = odbc_result($SKKLQRY,'EndDate');
		$agenda = odbc_result($SKKLQRY,'Agenda');
		$assignDate= odbc_result($SKKLQRY,'AssignmentDate');
		$superior = odbc_result($SKKLQRY,'SuperiorNIK');
	}
			
?>
<h2>Confirmation SKKL</h2><br />
<div id="poppage-wrap">
<div class="well span9">
        <form autocomplete="off" name="confSKKL" id="confSKKL" action="skklpopUpBawahanProses.php?id=<?php echo $id ?>&task=<?php echo md5($task_user) ?>" method="POST" onsubmit="return validateForm(this)">

<div class="row">
    		<div class="span1">NIK :</div>
    		<div class="span2"><?php	echo $NIK; ?>
        </div>
    </div>
<div class="row">
    		<div class="span1">Name :</div>
    		<div class="span3">
			<?php echo	$nama=odbc_result($SKKLQRY,3); 
				?>
                </div>
            </div>
<div class="row">
    		<div class="span1">SKKL</div>
            </div>
            	<div class="row">
    		<div class="span1">Date :</div>
    		<div class="span4">
                <?php
					echo convertDateAdis($assignDate);
				?>
                </div>
            </div>
	<div class="row">
    		<div class="span1">Time :</div>
    		<div class="span4">
			  <?php
			  		echo $beginTime;
			  ?>
				To
			 <?php
			  		echo $endTime;
			  ?>
			</div>
	</div>
    	<div class="row">
    		<div class="span2">Agenda :</div>
    		<div class="span8">
			  <?php echo $agenda ?></div>
            </div>

<div class="row">
    		<div class="span1">
            <table class="table table-striped table-bordered table-condensed">
<div class="row">
    		<div class="span1">Superior :</div>
    		<div class="span3">
				  <?php
				  	
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
						if ($rows == 0)
						{
							$FI_CV = '';
						}
						else
						{
							$FI_CV = saprfc_table_read($fce,"FI_CV",1);
							$namaSuperior=$FI_CV['NAMALENGKAP']; 
						}
				
					
					//jika superior ada, firstsuperior tidak ada, secondsuperior tidak ada muncul ini
					
					if($firstApprove ==NULL && $secondApprove == NULL)
					{
						echo $superior.'-'.$namaSuperior;
					}
					elseif($firstApprove !=NULL && $secondApprove == NULL)
					{
						$fce = saprfc_function_discover($rfc,"ZHRFM_CV");
						if (! $fce )
						{
							echo "System Error. Please Try Again Later."; exit;
						}
							saprfc_import ($fce,"FI_PERNR",$firstApprove);
							saprfc_import ($fce,"FI_PERNR_DIAKSES",$firstApprove);
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
							if ($rows == 0)
							{
								$FI_CV = '';
							}
							else
							{
								$FI_CV = saprfc_table_read($fce,"FI_CV",1);
								$firstnama=$FI_CV['NAMALENGKAP']; 
							}
							
							//jika superior ada, firstsuperior ada, secondsuperior tidak ada muncul ini
							
							echo $superior.'-'.$namaSuperior.'<br>'.$firstApprove.'-'.$firstnama;
					}
					elseif($firstApprove !=NULL && $secondApprove != NULL)
					{
						
						// $QryAtasan1= odbc_exec($conn,"select Nama,NIK from ms_niktelp where NIK='$firstApprove'");

						odbc_execute($QryAtasan1 = odbc_prepare($conn,"select Nama,NIK from ms_niktelp where NIK=?"),array($firstApprove));

                        			$firstnama=odbc_result($QryAtasan1, 1);
						
						// $QryAtasan2= odbc_exec($conn,"select Nama,NIK from ms_niktelp where NIK='$secondApprove'");

						odbc_execute($QryAtasan2 = odbc_prepare($conn,"select Nama,NIK from ms_niktelp where NIK=?"),array($secondApprove));

                        			$secondnama=odbc_result($QryAtasan2, 1);
					
	
							
							
							//jika superior ada, firstsuperior ada, secondsuperior ada muncul ini
							
							echo $superior.'-'.$namaSuperior.'<br>'.$firstApprove.'-'.$firstnama.
							'<br>'.$secondApprove.'-'.$secondnama;
					
					
					
					}
					 
					
				  ?>
                  </div>
                </div>
              </table>
              </div>
              </div>


<br>
		<?php 
				if  (odbc_result($SKKLQRY,'Accepted') == NULL)
				{
                	echo "<center><button class='btn btn-primary' type='submit' onclick='this.disabled=true;document.getElementById('poploading').style.display=block;document.getElementById('poppage-wrap').style.visibility=hidden;' value='Accept'>Accept</button></center>";
				}
				else
				{
					echo '<center><input type="button" value="Close" onClick="self.parent.tb_remove(true);" /></center>';
				}
		?>
      </form>
            </div>
            </div>
            
       <div id="poploading">
	  <h3>Loading page...</h3>
        <img src="img/loadingAnimation.gif" alt="loader">
        <small><br>...please wait data on processing.</small>
	</div>
<?php
  include "template/bottom3.php"
?>
