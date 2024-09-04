<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * SAP RFC Email Class
 *
 * Memungkinan berkomunikasi dengan data SAP via BAPI.
 *
 * @package     Awsomeness
 * @subpackage  Libraries
 * @category    Libraries
 * @author      Freon L
 */
class send_notification{

    private $CI;

    function __construct()
    {
        // Assign by reference with "&" so we don't create a copy
        $this->CI = &get_instance();
    }
    

    function send_email_CI($host, $port='', $secure=false, $username, $password, $from,$from2, $to, $subject, $body){
        $this->CI->load->library('email');
        $config['protocol']='smtp';
        $config['mailtype']='html';
        $config['charset']= "utf-8";
        $config['crlf']= "\r\n";
        $config['newline']= "\r\n";
        $config['priority']=1;
        $config['smtp_host']=$host;
        $config['smtp_port'] = "$port";
        if ($secure==true) {
            if ($host == 'mail.mykg.id') {
                $mailbawahan->SMTPSecure = 'ssl';
            }
            else{
                $mailbawahan->SMTPSecure = 'tls';
            }
        }
        $config['starttls'] = true;
        $config['smtp_user']=$username;
        $config['smtp_pass']=$password;
        $config['smtp_timeout']= 5;
        
        $this->CI->email->initialize($config);
        $this->CI->email->from($from, $from2);
        $this->CI->email->to($to);
        $this->CI->email->subject($subject);
        $this->CI->email->message($body);
        
        if($this->CI->email->send()){
            $_SESSION['notif'] = "Email has sent";
            $_SESSION['notif_type'] = "alert-success";
        }else{
            $_SESSION['notif'] = "Email has not sent";
            $_SESSION['notif_type'] = "alert-danger";
        }
    }

    function send_email($host, $port='', $secure=false, $username, $password, $from,$from2, $to, $subject, $body){
        $this->CI->load->library('phpmailer_lib');
        //Kirim email ke Karyawan
        $mailbawahan = $this->CI->phpmailer_lib->load();
        $mailbawahan->IsSMTP();
        // $mailbawahan->Host = "smtp.office365.com";
        $mailbawahan->Host = $host;
        // $mailbawahan->Port = 587;
        if ($port!='') {
            $mailbawahan->Port = $port;
        }
        
        if ($secure==true) {
            // $mailbawahan->SMTPSecure = 'tls';
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
            $this->CI->session->set_userdata('notif',"Email has not sent");
            $this->CI->session->set_userdata('notif_type',"alert-danger");
        }
        else{
            $return = array('status' => true);
            $this->CI->session->set_userdata('notif',"Email has sent");
            $this->CI->session->set_userdata('notif_type',"alert-success");
        }

        return $return;
    }
}
?>