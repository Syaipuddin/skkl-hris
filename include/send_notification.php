<?php
    // require_once "/var/www/include/class.phpmailer.php";
    // include_once "/var/www/language/credentials_constant.php";


    function send_email($host, $port='', $secure=false, $username, $password, $from,$from2, $to, $subject, $body){

        //Kirim email ke Karyawan
        $mailbawahan = new PHPMailer();
        $mailbawahan->IsSMTP();
        // $mailbawahan->Host = "smtp.office365.com";
        $mailbawahan->Host = $host;
        // $mailbawahan->Port = 587;
        if ($port!='') {
            $mailbawahan->Port = $port;
        }

        $mailbawahan->SMTPDebug = 0;
        $mailbawahan->do_debug = 0;
        
        if ($secure==true) {
            if ($host == 'mail.mykg.id') {
                $mailbawahan->SMTPSecure = 'ssl';
            }
            else{
                $mailbawahan->SMTPSecure = 'tls';
            }
        }
        // $mailbawahan->SMTPSecure = 'tls';
        $mailbawahan->SMTPAuth = true;
        // $mailbawahan->Username = 'hrportal@hr.kompasgramedia.com';
        $mailbawahan->Username = $username;
        // $mailbawahan->Password = 'S1rh0K3@@)@!';
        $mailbawahan->Password = $password;
        // $mailbawahan->SetFrom("hrportal@hr.kompasgramedia.com", "[HRPortal] DO NOT REPLY THIS EMAIL!");
        $mailbawahan->SetFrom($from, $from2);
        $mailbawahan->IsHTML(true);
                      
        // $mailbawahan->AddAddress("ivanyudi.cr9@gmail.com");
        if (is_array($to)) {
            foreach ($to as $key) {
                $mailbawahan->AddAddress(strtoupper(trim($key)));
            }
        }
        else{
            $mailbawahan->AddAddress(strtoupper(trim($to)));
        }
        // $mailbawahan->Subject = "Pemberitahuan Status Pengajuan Reimburse Medis";
        $mailbawahan->Subject = $subject;

        $mailbawahan->Body = $body;

        //sending email
        if(!$mailbawahan->Send())
        {
            $return = array('status' => false, 'msg'=>$mailbawahan->ErrorInfo);
        }
        else{
            $return = array('status' => true);
        }
        return $return;
    }
?>