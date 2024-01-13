<?php
    include_once('config/common.php');

    // EMAIL INTEGRATION CODE
        require 'includes/PHPMailer.php';
        require 'includes/SMTP.php';
        require 'includes/Exception.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = "true";
        $mail->Username = "er.krutikpatel31@gmail.com";
        $mail->Password = "utzvxtvpgskxriwe";
        $mail->SMTPSecure = "ssl";
        $mail->Port = "465";
        $mail->setFrom("er.krutikpatel31@gmail.com");
    // EMAIL INTEGRATION CODE ENDS

    $status = '';
    $msg = '';
    $data = '';

    if (!isset($_POST['email'])){
        $status = 'false';
        $msg = 'Something Went Wrong! Please try again.';
    }else{
        if (empty($_POST['email'])){
            $status = 'false';
            $msg = 'Kindly enter email address!';
        }else{
            $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $status = 'false';
                $msg = 'Invalid email format!';
            }else{

                $validateemailsql = "SELECT pid FROM permissions WHERE email = '".$email."' AND disflag = '0' ";
                $validateemail = $obj->select($validateemailsql);

                if (count($validateemail) > 0){

                    function generateOTP() {
                        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $OTP = '';
                        for ($i = 0; $i < 5; $i++) {
                            $index = rand(0, strlen($characters) - 1);
                            $OTP .= $characters[$index];
                        }
                        return $OTP;
                    }

                    $otp = generateOTP();

                    $deletepastotp = "DELETE FROM login_otp WHERE email = '".$email."' ";
                    $obj->delete($deletepastotp);

                    // while(1){
                    //     $validateotpsql = "SELECT oid FROM login_otp WHERE otp = '".$otp."' ";
                    //     $validateotp = $obj->select($validateotpsql);

                    //     if(count($validateotp) == 0){
                    //         break;
                    //     }else{
                    //         $otp = generateOTP();
                    //     }
                    // }

                    $insertotpsql = "INSERT INTO login_otp(email, otp) VALUES ('".$email."', '".$otp."')";
                    $obj->insert($insertotpsql); 

                    $getdatasql = "SELECT name, phone FROM permissions WHERE email = '".$email."' ";
                    $getdata = $obj->select($getdatasql); 

                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "Email Verification";
                    $mail->Body = '
                        Hello '.$getdata[0]["name"].',<br><br>
                        The OTP towards validating your mail address is <strong>'.$otp.'.</strong><br>
                        Please Note this OTP is valid only for a duration of 15:00 Minutes.<br><br>
                        Regards,<br>
                        CES Portal<br><br>
                        <strong>Note:</strong> <span style="color: red;">Please do not reply to this mail. Email sent to this address will not be responded to.</span>
                    ';
                    if($mail->send()){
                            $data .= "
                            <form id='otpForm'>
                                <input type='hidden' id='email' value='".$email."'>
                                <div class='form-icon'>
                                    <span><img class='img-fluid img-profile rounded-circle mx-auto mb-5' src='assets/img/logo.png' alt='logo' /></span>
                                </div>
                                <div class='form-group text-center'>
                                    <h3 class='cusfont'>CES Portal</h3>
                                    <p class='cusfont'>Your OTP has been sent to <br><strong>".$email."</strong></p>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control item' name='otp' id='otp' placeholder='Enter OTP'>
                                    <small id='otpHelp' class='form-text text-muted'>This OTP will only be allowed for next <strong>15:00</strong> Minutes.<br><strong>Do not reload this page!</strong></small>
                                </div>
                                <a href='' id='resendOTP'>Resend OTP</a>
                                <div class='form-group'>
                                    <button type='button' class='btn btn-block create-account' id='signin'>Sign In <i class='fa fa-sign-in' aria-hidden='true'></i></button>
                                </div>
                            </form>
                        ";
                    }else{
                        $deletepastotp = "DELETE FROM login_otp WHERE email = '".$email."' ";
                        $obj->delete($deletepastotp);
                        $status = 'false';
                        $msg = 'Something Went Wrong, Kindly try to resend OTP or recheck your email address!';
                    }
                    $mail->smtpClose();
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