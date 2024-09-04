<?php
function CheckFlagHeregistrasi($NIK, $conn=NULL){
	$current_file = $_SERVER['PHP_SELF'];
	// kalau di halaman profile ga perlu munculin popup
	if ($current_file == "/list_profile_anjab.php") {
		$return = array('flag' => 1);
		return $return;
	}

	if (!$conn) {
		include "include/connection.php";
	}
	
	$query = "SELECT * FROM ms_FlagHeregistrasi WHERE NIK = ?";
	$query_flag = odbc_prepare($conn, $query);
	odbc_execute($query_flag, array($NIK));

	$FlagPersonalData = odbc_result($query_flag, 'FlagPersonalData');
	$FlagAddress = odbc_result($query_flag, 'FlagAddress');
	$FlagContact = odbc_result($query_flag, "FlagContact");
	$FlagPersonalIdentity = odbc_result($query_flag, 'FlagPersonalIdentity');
	$FlagBankDetails = odbc_result($query_flag, 'FlagBankDetails');
	$FlagFamily = odbc_result($query_flag, "FlagFamily");
	$FlagFormalEdu = odbc_result($query_flag, 'FlagFormalEdu');
	$FlagTrainingCourse = odbc_result($query_flag, "FlagTrainingCourse");
	$FlagWorkExperience = odbc_result($query_flag, "FlagWorkExperience");
	$TotalFlag = odbc_result($query_flag, "TotalFlag");
	$TglUpdate = date("Y-m-d", strtotime(odbc_result($query_flag, "TglUpdate")));

	// buat testing
	$flag = 0;
	// $jml_skip = 1;

	if ($TotalFlag == 9) {
		$flag = 1;
	}
	else{
		if ($TglUpdate == date("Y-m-d") && $TotalFlag > 0) {
			$flag = 1;
		}
		else{
			// total flag = 5, karena family, education, training, dan work exp tidak wajib (5 flag lainnya wajib)
			// tetep muncul popup tapi ada tombol close, karena tidak dipaksa
			if (($FlagFormalEdu == 0 || $FlagFamily == 0 || $FlagTrainingCourse == 0 || $FlagWorkExperience == 0) && $TotalFlag == 5) {
				// klo flag 2 -> berarti popupnya bs diclose (tdk wajib)

				$flagNames = array(
					"Personal Data", 
					"Address", 
					"Contact", 
					"Personal Identity", 
					"Bank Details", 
					"Family", 
					"Formal Educations", 
					"Work Experience"
				)

				$allBtn = '';

				foreach $flagNames as $names {
					$isActive = false

					if($names == "Personal Data") $isActive = true;
					if($names == "Address") $isActive = true;
					if($names == "Contact") $isActive = true;
					if($names == "Personal Identity") = true;
					if($names == "Bank Details") $isActive = true;
					if($names == "Family") $isActive = true;
					if($names == "Formal Educations") $isActive = true;
					if($names == "Formal Educations") $isActive = true;

					$btn = '<div class="banner-btn"> 
							<label> 
								Personal Data 
								<input type="checkbox" />
								<span class="checkmark"></span>
							</label> 
						</div>'

					$allBtn += $btn;
				}

				
				

				$flag = 2;
				echo '<div data-remodal-id="modal_heregistrasi">
					<button data-remodal-action="close" class="remodal-close right"></button>
				   <p>
				    <a href="list_profile_anjab.php" target="_blank"><img src="img/banner_home/banner_heregistrasi2024.jpeg"></img></a>
				   </p>

				   <div id="banner-image">
						<div id="button-items">'
							.$allBtn.
						'</div>
				   </div>
				 </div>';
			}
			else{
				echo '<div data-remodal-id="modal_heregistrasi">
				   <p>
				    <a href="list_profile_anjab.php" target="_blank"><img src="img/banner_home/banner_heregistrasi2024.jpeg"></img></a>
				   </p>
				 </div>';
			}
		}
	}

	$return = array('flag' => $flag/*, 'skip_flag' => $jml_skip*/);
	return $return;
}

?>
