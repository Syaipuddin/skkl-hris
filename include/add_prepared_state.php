<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	include "connection.php";

	function add_prepared_state($conn, $query){
		// odbc_prepare($conn, $query);
	}

	$test = $_GET['test'];
	$tanggalPeriode = '201601';
	$startDate = '2010-01-01';
	$endDate = '2016-01-01';
	$NIK = '005290';
	$password = "TestHehe123";
	// $prepare = odbc_exec($conn, "SELECT * FROM ms_niktelp WHERE NIK = '$test'");
	// $prepare = odbc_prepare($conn, "SELECT * FROM ms_niktelp WHERE NIK like ?");
	// $prepare_with_encrypt = odbc_prepare($conn, "SELECT *, encryptbypassphrase(?,?) FROM tr_login WHERE userLogin = ?");
	// $prepare_between = odbc_prepare($conn, "SELECT * from tb_Documentation where DocumentDate between ? and ?");
	/*$prepare_insert = odbc_prepare($conn, "INSERT INTO [PORTAL].[dbo].[tb_tunjSekolah_files] ([id_header],[path_file]) VALUES(?, ?)");
	var_dump(odbc_execute($prepare_insert, array(99999, 'test')));*/

	$batas = 100;
	$posisi = 0;
	$tahunSelected = '2016';

	/*$prepare_top = odbc_prepare($conn, "SELECT DISTINCT TOP (?) NIK, Nama, JenisPengunduran, TanggalPengunduran, HRNIK, LastEdited 
                FROM (SELECT DISTINCT TOP (?) NIK, Nama, JenisPengunduran, TanggalPengunduran, HRNIK, LastEdited 
                    FROM (  SELECT DISTINCT TOP (?) Nama, NIK, JenisPengunduran, TanggalPengunduran, HRNIK, LastEdited 
                            FROM tb_exitinterview_answer_employee
                            WHERE TanggalPengunduran LIKE ?
                            ORDER BY NIK ASC)T
                    ORDER BY NIK DESC)T
                ORDER BY NIK ASC");*/

	// $qry_jml_param = array("005290", "2020-01-01", "2022-12-31");
	$nama = "IVAN YUDI PRABOWO";
	$persadm = "004";
	$trainingCode = 1;
	$val = 1;
			// $info_2 = odbc_exec($conn, $query_info_2,1);
			// odbc_execute($sql_list = odbc_prepare($conn,"SELECT * FROM ms_niktelp WHERE Nama like ? AND PersAdmin in(?)"), array("%".$nama."%",$persadm));

			odbc_execute($sql_list = odbc_prepare($conn, "SELECT sch_date AS dt FROM trm_training_sch_date tsd
														WHERE 
															$trainingCode = (SELECT training_id FROM trm_training_sch WHERE training_sch_id = $val) AND
															tsd.training_sch_id = $val"),array($val,$val));
			var_dump(odbc_result($sql_list, 1));
			// $query_info_2 = "SELECT Message
			// 				FROM [PORTAL].[dbo].[PersoInfoParticipant]
			// 				WHERE Event_ID = 25 AND NIK = '$NIK'";
			// $info_2 = odbc_exec($conn, $query_info_2,1);

	/*$test_prepare = odbc_execute($qry_jml_nik = odbc_prepare($conn,"SELECT count(distinct A.id_header) as jml FROM tb_mr_trx_medrem_header A, tb_mr_trx_medrem_detail b WHERE A.id_header = b.id_header $nik_sql AND A.persadmin in(SELECT PersAdminRole FROM tb_mr_persadmin_role WHERE NIK = ?)"), $qry_jml_param);*/

    // $test = odbc_fetch_array(odbc_execute($prepare_top, array($batas, $batas, $posisi, "%".$tahunSelected."%")));
    var_dump($sql_list);
	// var_dump(odbc_execute($prepare_between, array($startDate, $endDate)));
	// var_dump(odbc_execute($prepare_with_encrypt, array($NIK, $password,$NIK)));
	// odbc_execute($prepare, array("%".$test."%"));
	// $prepare_sp = odbc_prepare($conn, "exec SPManPowerStat ?");
	// var_dump("%".$test."%");
	// odbc_execute($prepare_sp, array($tanggalPeriode));

	// var_dump(odbc_result($prepare_between, "DocumentTitle"));

	// var_dump(odbc_result($prepare, "NIK"));
	// var_dump(odbc_result($prepare_sp, "orgname"));
	// odbc_execute($prepare);
	// odbc_execute($prepare);

	/*$test = odbc_result($prepare, "NIK");
	var_dump($test);*/
?>