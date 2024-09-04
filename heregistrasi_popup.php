<link rel="stylesheet" type="text/css" href="../assets/css/heregistrasi.css">
<?php
function CheckFlagHeregistrasi($NIK, $conn=NULL){
	$current_file = $_SERVER['PHP_SELF'];
	if ($NIK == '068101') {
		var_dump($_SERVER);
	}
	// kalau di halaman profile ga perlu munculin popup
	if ($current_file == "/list_profile_anjab.php" || !isset($NIK)) {
		$return = array('flag' => 1);
		return $return;
	}
	else{
		if (!$conn) {
			include "include/connection.php";
			session_start();
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

		$ms_niktelp = "SELECT * FROM ms_niktelp WHERE NIK = ?";
		$qry_msniktelp = odbc_prepare($conn, $ms_niktelp);
		odbc_execute($qry_msniktelp, array($NIK));
		$payArea = odbc_result($qry_msniktelp, "PayArea");

		if (isset($_SESSION['status_nikah'])) {
			// KHUSUS YANG STATUSNYA LAJANG, GA PERLU MUNCUL LAGI POPUPNYA
			if (strtolower($_SESSION['status_nikah']) == 'lajang') {
				$FlagFamily = 1;
				$TotalFlag++;
			}
		}
		else{
			// INI BUAT AMBIL STATUS PERNIKAHAN KARYAWAN, SPY GA PERLU MUNCUL POPUP JIKA LAJANG
			$todayDate_SAPFormat = date("Ymd");

			if (!isset($rfc)) {
				include "include/sapconnect.php";
			}

			//start of ZHRFM_CV_MINI
			$fce = saprfc_function_discover($rfc,"ZHRFM_CV_MINI");
			if (! $fce ) { echo "Discovering interface of function module failed"; exit; }
			saprfc_import ($fce,"FI_PERNR",$NIK);
			saprfc_import ($fce,"FI_PERNR_DIAKSES",$NIK);
			saprfc_import ($fce,"FI_TANGGAL",$todayDate_SAPFormat);
			saprfc_table_init ($fce,"FI_CV");
			$rfc_rc = saprfc_call_and_receive ($fce);
			if ($rfc_rc != SAPRFC_OK){
				if ($rfc == SAPRFC_EXCEPTION ) {
					echo "System Errorh1. Please Try Again Later.";
				}else {
					echo "System Errorh2. Please Try Again Later."; exit;
				}
			}

			$FI_NIKAH = strtolower(saprfc_export ($fce,"FI_NIKAH"));
			
			// KHUSUS YANG STATUSNYA LAJANG, GA PERLU MUNCUL LAGI POPUPNYA
			if ($FI_NIKAH == 'lajang') {
					$FlagFamily = 1;
					$TotalFlag++;
					// SET STATUS NIKAH KE SESSION, ini buat HEREGISTRASI SPY GA PERLU PANGGIL BAPI TRUS SIAP CEK FLAG
					$_SESSION['status_nikah'] = $FI_NIKAH;
			}
		}

		$flagNames = array(
			"Personal Data", 
			"Address", 
			"Contact", 
			"Personal Identity", 
			// "Bank Details", 
			"Family", 
			"Formal Educations", 
			"Training Courses",
			"Work Experience"
		);

		// buat testing
		$flag = 0;
		// $jml_skip = 1;

		// YANG BANK DETAILS DITAKEOUT DULU AJA, jadi totalnya hny 8
		// if ($TotalFlag == 9) {
		if ($TotalFlag >= 8) {
			$flag = 1;
		}
		else{
			if ($TglUpdate == date("Y-m-d") && $TotalFlag > 0) {
				$flag = 1;
			}
			else{
				// total flag = 5, karena family, education, training, dan work exp tidak wajib (5 flag lainnya wajib)
				// total flag dibikin jadi = 4 dulu, krn bank details ditakeout sementara
				// tetep muncul popup tapi ada tombol close, karena tidak dipaksa
				if (($FlagFormalEdu == 0 || $FlagFamily == 0 || $FlagTrainingCourse == 0 || $FlagWorkExperience == 0) && $TotalFlag >= 4) {
					// klo flag 2 -> berarti popupnya bs diclose (tdk wajib)

					$allBtn = '';
					foreach ($flagNames as $names) {
						$check = '';
						$imgElement = '<img width="15px" src="img/banner_home/square-check-solid.svg" />';

						if($names == "Personal Data" && $FlagPersonalData > 0) $check = $imgElement;
						if($names == "Address" && $FlagAddress > 0) $check = $imgElement;
						if($names == "Contact" && $FlagContact > 0) $check = $imgElement;
						if($names == "Personal Identity" && $FlagPersonalIdentity > 0) $check = $imgElement;
						// if($names == "Bank Details" && $FlagBankDetails > 0) $check = $imgElement;
						if($names == "Family" && $FlagFamily > 0) $check = $imgElement;
						if($names == "Formal Educations" && $FlagFormalEdu > 0) $check = $imgElement;
						if($names == "Training Courses" && $FlagTrainingCourse > 0) $check = $imgElement;
						if($names == "Work Experience" && $FlagWorkExperience > 0) $check = $imgElement;

						$btn = '<div id="banner-btn"> 
									<div class="items">'
										.$check.
										'<p>'.$names.'</p>
									</div> 
								</div>';

						$allBtn = $allBtn . $btn;
					}


					$flag = 2;

					if ($payArea != 'KM') {
						echo '<div id="banner_modal" data-remodal-id="modal_heregistrasi">
						<button data-remodal-action="close" class="remodal-close right"></button>
						<a href="/list_profile_anjab.php">
								<img id="banner-image" src="img/banner_home/banner_heregistrasi_newest.jpeg" />
								<div id="button-items">'
									.$allBtn.
								'</div>
						</a>
					 	</div>';
					}
					
				}
				else{

					$allBtn = '';
					foreach ($flagNames as $names) {
						$check = '';
						$imgElement = '<img width="15px" src="img/banner_home/square-check-solid.svg" />';

						if($names == "Personal Data" && $FlagPersonalData > 0) $check = $imgElement;
						if($names == "Address" && $FlagAddress > 0) $check = $imgElement;
						if($names == "Contact" && $FlagContact > 0) $check = $imgElement;
						if($names == "Personal Identity" && $FlagPersonalIdentity > 0) $check = $imgElement;
						// if($names == "Bank Details" && $FlagBankDetails > 0) $check = $imgElement;
						if($names == "Family" && $FlagFamily > 0) $check = $imgElement;
						if($names == "Formal Educations" && $FlagFormalEdu > 0) $check = $imgElement;
						if($names == "Training Courses" && $FlagTrainingCourse > 0) $check = $imgElement;
						if($names == "Work Experience" && $FlagWorkExperience > 0) $check = $imgElement;

						$btn = '<div id="banner-btn"> 
									<div class="items">'
										.$check.
										'<p>'.$names.'</p>
									</div> 
								</div>';

						$allBtn = $allBtn . $btn;
					}

					if ($payArea != 'KM') {
						echo '<div id="banner_modal" data-remodal-id="modal_heregistrasi">

							<a href="/list_profile_anjab.php">
									<img id="banner-image" src="img/banner_home/banner_heregistrasi_newest.jpeg" />
									<div id="button-items">'
										.$allBtn.
									'</div>
							</a>
						 </div>';
					}
				}
			}
		}

		$return = array('flag' => $flag/*, 'skip_flag' => $jml_skip*/);
		return $return;
	}
}

?>