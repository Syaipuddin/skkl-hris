<?php
function UpdateFlag($data=array()){
    include "connection.php";

    switch ($data['field']) {
        case 1:
            $field = 'FlagPersonalData';
            break;
        case 2:
            $field = 'FlagAddress';
            break;
        case 3:
            $field = 'FlagContact';
            break;
        case 4:
            $field = 'FlagPersonalIdentity';
            break;
        case 5:
            $field = 'FlagBankDetails';
            break;
        case 6:
            $field = 'FlagFamily';
            break;
        case 7:
            $field = 'FlagFormalEdu';
            break;
        case 8:
            $field = 'FlagTrainingCourse';
            break;
        case 9:
            $field = 'FlagWorkExperience';
            break;

        default:
            $field = "";
            break;
    }

    $NIK = $data['NIK'];

    $cekHereg = odbc_prepare($conn, "SELECT TOP 1 * FROM ms_FlagHeregistrasi WHERE NIK = ?");
    odbc_execute($cekHereg, array($data['NIK']));
    $jmlHereg = odbc_num_rows($cekHereg);
    $flagHeregistrasi = odbc_result($cekHereg, $field);

    // var_dump('field: ',$field);
    // var_dump('jmlHereg: ',$jmlHereg);
    // var_dump('flagHeregistrasi: ',$flagHeregistrasi);

    if ($jmlHereg > 0) {
        if ($flagHeregistrasi) {
            // kalau flagnya udh 1, diskip aja
            $updateFlag = 1;
        }
        else{
            // kalau flagnya msh 0, akan melakukan update
            $updateFlag = odbc_prepare($conn, "UPDATE ms_FlagHeregistrasi SET $field = 1, TotalFlag = TotalFlag+1, TglUpdate = GETDATE() WHERE NIK = ?");
            $exec = odbc_execute($updateFlag, array($NIK));
            $error_msg = odbc_errormsg($conn);
            // var_dump('error_msg: ',$error_msg);
        }
    }
    else{
        // klo blm ada di table, diinsert aja
        $updateFlag = odbc_prepare($conn, "INSERT INTO ms_FlagHeregistrasi (NIK,$field,TotalFlag,TglUpdate) VALUES(?,1,1,GETDATE())");
        $exec = odbc_execute($updateFlag, array($NIK));
        // var_dump('error_msg: ',$error_msg);
    }

    $exec = (isset($exec)) ? $exec : $updateFlag;
    
    return $exec;
}
?>