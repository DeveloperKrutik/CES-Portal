<?php

    include_once('config/common.php');

    $status = '';
    $msg = '';
    $data = '';

    if ((!isset($_POST['email'])) or (!isset($_POST['otp']))){
        $status = 'false';
        $msg = 'Something Went Wrong! Please try again.';
    }else{
        if ((empty($_POST['email'])) or (empty($_POST['otp']))){
            $status = 'false';
            $msg = 'All fields are required to fill!';
        }else{
            $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
            $otp = mysqli_real_escape_string($obj->CONN, trim($_POST['otp']));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $status = 'false';
                $msg = 'Invalid email format!';
            }else{

                $validateemailsql = "SELECT name, email, phone FROM permissions WHERE email = '".$email."' AND disflag = '0' ";
                $validateemail = $obj->select($validateemailsql);

                if (count($validateemail) > 0){
                    
                    $signinsql = "SELECT createdon FROM login_otp WHERE email = '".$email."' AND otp = '".$otp."' ";
                    $signin = $obj->select($signinsql);

                    if (count($signin) > 0){

                        $deletepastotp = "DELETE FROM login_otp WHERE email = '".$email."' ";
                        $obj->delete($deletepastotp);

                        $d1 = $signin[0]['createdon'];
                        date_default_timezone_set('Asia/Kolkata');
                        $d2 = date('y-m-d H:i:s'); 
                        $from_time = strtotime($d1); 
                        $to_time = strtotime($d2); 
                        $mindiff = round(abs($from_time - $to_time) / 60,2);

                        if ($mindiff > 15){
                            $status = 'false';
                            $msg = "This OTP has been expired!";    
                        }else{
                            session_regenerate_id(true);
                            $_SESSION['user']['name'] = $validateemail[0]['name'];
                            $_SESSION['user']['email'] = $validateemail[0]['email'];
                            $_SESSION['user']['phone'] = $validateemail[0]['phone'];

                            $status = 'true';
                            $msg = 'Login Successful';
                        }

                    }else{
                        $status = 'false';
                        $msg = 'Invalid OTP!';
                    }
                }else{
                    $status = 'false';
                    $msg = 'Email address not found!';
                }
            }
        }
    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    );
		
    echo json_encode($json_data); 
?>