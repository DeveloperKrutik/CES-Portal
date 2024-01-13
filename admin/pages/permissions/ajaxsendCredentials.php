<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

        // EMAIL INTEGRATION CODE
        require '../../../includes/PHPMailer.php';
        require '../../../includes/SMTP.php';
        require '../../../includes/Exception.php';

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

    if ((!isset($_POST['cnr'])) OR (!isset($_POST['role'])) OR (!isset($_POST['name'])) OR (!isset($_POST['email'])) OR (!isset($_POST['phone']))){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{

        if (($_POST['cnr'] != "") AND ($_POST['role'] != 0) AND ($_POST['name'] != "") AND ($_POST['email'] != "") AND ($_POST['phone'] != "")){
        
            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));
            $role = mysqli_real_escape_string($obj->CONN, trim($_POST['role']));
            if ($role == 0){
                $status = 'false';
                $msg = 'All Fields are required to fill.';
            }else if ($role == 1){
                $role = 'Government Pleader';
            }else if ($role == 2){
                $role = 'Government Officer';
            }else{}
            $name = mysqli_real_escape_string($obj->CONN, trim($_POST['name']));
            $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
            $phone = mysqli_real_escape_string($obj->CONN, trim($_POST['phone']));

            $validationsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $validationcheck = $obj->select($validationsql);

            if (count($validationcheck) == 0){
                $status = 'false';
                $msg = "CNR Number doesn't exists!";
            }else{
                $cid = $validationcheck[0]['cid'];

                $validatePleaderSQL = "SELECT plid FROM assigned_pleaders WHERE case_id = '".$cid."' AND email = '".$email."' ";

                if (count($obj->select($validatePleaderSQL)) > 0){
                    $status = 'false';
                    $msg = "You can not change any information about <b>Assigned Pleader</b>.";
                }else{

                    $sql = "SELECT email, name, phone FROM permissions WHERE email = '".$email."' AND case_id = '".$cid."' ";
                    $query = $obj->select($sql);

                    if (count($query) > 0){
                        // $validateemailsql = "UPDATE permissions SET role = '".$role."', disflag = '0' WHERE email = (SELECT email FROM permissions WHERE case_id = '".$cid."' AND email = '".$email."') AND case_id = '".$cid."' ";
                        $validateemailsql = "UPDATE permissions SET role = '".$role."', disflag = '0' WHERE email = '".$email."' AND case_id = '".$cid."' ";
                        $validateemail = $obj->edit($validateemailsql);

                        $mail->addAddress($email);
                        $mail->isHTML(true);
                        $mail->Subject = "Case Access Permission";
                        $mail->Body = '
                            Hello '.$query[0]["name"].',<br><br>
                            This mail is only for government officials and government pleaders.<br><br>
                            you are given rights to access the details of the case (<strong>CNR: '.$cnr.'</strong>).<br><br>
                            To access this case details through your email, use the below given link.<br>
                            <a href="#">link to CES Portal</a>.<br><br>
                            Regards,<br>
                            CES Portal<br><br>
                            <strong>Note:</strong> <span style="color: red;">Please do not reply to this mail. Email sent to this address will not be responded to.</span>
                        ';
                        $mail->send();

                        $status = 'true';
                        $msg = "Invitation mail has been sent successfully.";
                    }else{
                    
                        $emaildatasql = "SELECT name, phone FROM permissions WHERE email = '".$email."' ";
                        $emaildata = $obj->select($emaildatasql);
                        
                        if (count($emaildata) > 0){
                            $permissionsql =    "INSERT INTO 
                                                permissions(case_id, role, name, email, phone) 
                                                VALUES (
                                                    '".$cid."', '".$role."', '".$emaildata[0]['name']."', '".$email."', '".$emaildata[0]['phone']."'
                                                )";
                            $permission = $obj->insert($permissionsql);

                            if ($permission > 0) {

                                $mail->addAddress($email);
                                $mail->isHTML(true);
                                $mail->Subject = "Case Access Permission";
                                $mail->Body = '
                                    Hello '.$emaildata[0]["name"].',<br><br>
                                    This mail is only for government officials and government pleaders.<br><br>
                                    you are given rights to access the details of the case (<strong>CNR: '.$cnr.'</strong>).<br><br>
                                    To access this case details through your email, use the below given link.<br>
                                    <a href="#">link to CES Portal</a>.<br><br>
                                    Regards,<br>
                                    CES Portal<br><br>
                                    <strong>Note:</strong> <span style="color: red;">Please do not reply to this mail. Email sent to this address will not be responded to.</span>
                                ';
                                $mail->send();

                                $status = 'true';
                                $msg = "Invitation mail has been sent successfully.";
                            }else{
                                $status = 'false';
                                $msg = "Something Went Wrong!";
                            }
                        }else{
                            $permissionsql =    "INSERT INTO 
                                                permissions(case_id, role, name, email, phone) 
                                                VALUES (
                                                    '".$cid."', '".$role."', '".$name."', '".$email."', '".$phone."'
                                                )";
                            $permission = $obj->insert($permissionsql);

                            if ($permission > 0) {

                                $mail->addAddress($email);
                                $mail->isHTML(true);
                                $mail->Subject = "Case Access Permission";
                                $mail->Body = '
                                    Hello '.$emaildata[0]["name"].',<br><br>
                                    This mail is only for government officials and government pleaders.<br><br>
                                    you are given rights to access the details of the case (<strong>CNR: '.$cnr.'</strong>).<br><br>
                                    To access this case details through your email, use the below given link.<br>
                                    <a href="#">link to CES Portal</a>.<br><br>
                                    Regards,<br>
                                    CES Portal<br><br>
                                    <strong>Note:</strong> <span style="color: red;">Please do not reply to this mail. Email sent to this address will not be responded to.</span>
                                ';
                                $mail->send();

                                $status = 'true';
                                $msg = "Invitation mail has been sent successfully.";
                            }else{
                                $status = 'false';
                                $msg = "Something Went Wrong!";
                            }
                        }
                    }
                }
            }
        }else{
            $status = 'false';
            $msg = 'All Fields are required to fill.';
        }

    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg
    );
		
    echo json_encode($json_data); 

?>