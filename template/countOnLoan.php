<?php
	function countLoan($NIK){
		include "include/sapconnect.php";
		$endDate = date('Ymd');
		$startDate = date('Ymd',strtotime('-30 days'));
		$endDate2 = date('Ymd', strtotime('+30 days'));
		$fce = saprfc_function_discover($rfc,"ZHRFM_OBJECTSONLOAN");
		if (! $fce ){
			echo "System Error. Please Try Again Later."; exit;
		}
		saprfc_import ($fce,"FI_ENDDA",$endDate);
		saprfc_import ($fce,"FI_BEGDA",$startDate);
		saprfc_import ($fce,"FI_PERNR",$NIK);
		saprfc_table_init ($fce,"FI_LOAN");
		$rfc_rc = saprfc_call_and_receive ($fce);
		if ($rfc_rc != SAPRFC_OK){
			if ($rfc == SAPRFC_EXCEPTION )
				echo "System Error. Please Try Again Later.";
			else
				echo "System Error. Please Try Again Later."; exit;
		}
		//$FI_RTEXT = saprfc_export ($fce,"FI_RTEXT");
		$rows = saprfc_table_rows ($fce,"FI_LOAN");
		$totalLoanSoonEnd = 0;

		if ($rows!=0){
			for ($i=1;$i<=$rows;$i++){
				$FI_LOAN = saprfc_table_read ($fce,"FI_LOAN",$i);
				if($FI_LOAN['ENDDA'] > $endDate && $FI_LOAN['ENDDA'] < $endDate2 && $FI_LOAN['HISTO'] != "X" && $FI_LOAN['HISTO'] != "E"){
					$totalLoanSoonEnd++;
				}
			}
		}
		return $totalLoanSoonEnd;
	}
?>
