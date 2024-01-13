<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';
    $name = '';
    // $email = '';
    $phone = '';

    if (!isset($_POST['email'])){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{
        $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
        $emailsql = "SELECT name, phone, email FROM permissions WHERE email = '".$email."' ";
        $emailcheck = $obj->select($emailsql);

        if (count($emailcheck) > 0){
            $status = 'true';
            $name = $emailcheck[0]['name'];
            // $email = $emailcheck[0]['email'];
            $phone = $emailcheck[0]['phone'];
        }
    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg,
        "name" => $name,
        "phone" => $phone
    );
		
    echo json_encode($json_data); 
?>