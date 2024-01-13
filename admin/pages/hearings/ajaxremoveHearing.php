<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';

    if ((!isset($_POST['cnr']))){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{

            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));

            $validationsql = "SELECT hid FROM hearings WHERE case_id = (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."') ";
            $validationcheck = $obj->select($validationsql);

            if (count($validationcheck) == 0){
                $status = 'false';
                $msg = "No Entry Exists for ".$cnr."";
            }else{
                $deletesql = "DELETE FROM hearings WHERE case_id = (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."') AND hearing_no = '".count($validationcheck)."' ";
                $obj->delete($deletesql);
                $status = 'true';
                $msg = "Hearing Removed Successfully.";
            }

    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg
    );
		
    echo json_encode($json_data); 

?>