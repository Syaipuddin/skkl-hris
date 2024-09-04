<?php
function checkFlag($NIK, $conn=NULL){
	if (!$conn) {
		include "include/connection.php";
	}
	
	$query = "SELECT * FROM tb_survey_dobloo WHERE NIK = ?";
	$query_flag = odbc_prepare($conn, $query);
	odbc_execute($query_flag, array($NIK));
	$flag = odbc_result($query_flag, 'status');
	$jml_skip = odbc_result($query_flag, 'skip');
	$survey_unique_link = odbc_result($query_flag, "unique_link");
	$jml_skip = ($jml_skip==NULL) ? 0 : $jml_skip;

	if ($flag == 0 || $flag == 2) {
		include "survey_dobloo_status.php";
		$survey_result = apiGetDoblooStatus($NIK);
		$survey_result = json_decode($survey_result);

		if (!$survey_result) {
			$return = array('flag' => 1, 'skip_flag' => 0);
			return $return;
		}

		$status = $survey_result[0]->Status;
		if ($status == "Completed") {
			$flag = 1;
		}
		elseif ($status == "Partial") {
			$flag = 2;
		}
		elseif ($status == "NotStarted") {
			$flag = 0;
		}

		if ($flag == 1) {
			$status_update = apiSetDoblooStatus(array($NIK));
			if ($status_update['status'] == 2) {
				echo "<script>alert('Oops! Something error, please refresh page again!')</script>";
			}
		}
		if ($survey_unique_link && ($flag == 2 || $flag == 0) && $jml_skip != 3) {
			echo '<div data-remodal-id="modal_survey_dobloo">
				   <p>
				    <a href="'.$survey_unique_link.'" target="_parent"><img src="img/banner_home/survey_dobloo_smallest.png"></img></a>
				   </p>
				 </div>';
		}
		
	}
	$return = array('flag' => $flag, 'skip_flag' => $jml_skip);
	return $return;
}
?>
