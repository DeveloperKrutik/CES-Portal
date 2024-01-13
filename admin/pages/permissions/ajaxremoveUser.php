<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';

    if ((!isset($_POST['cnr'])) OR (!isset($_POST['per']))){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{

            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));
            $per = mysqli_real_escape_string($obj->CONN, trim($_POST['per']));

            $cidsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $cid = $obj->select($cidsql);

            $validationsql = "SELECT pid, email FROM permissions WHERE case_id = '".$cid[0]['cid']."' AND pid = '".$per."' AND disflag = '0' ";
            $validationcheck = $obj->select($validationsql);

            if (count($validationcheck) == 0){
                $status = 'false';
                $msg = "Something Went Wrong!";
            }else{
                $asspldrsql = "SELECT plid FROM assigned_pleaders WHERE case_id = '".$cid[0]['cid']."' AND email = '".$validationcheck[0]['email']."' ";
                $asspldr = $obj->select($asspldrsql);
                if (count($asspldr) > 0){
                    $status = 'false';
                    $msg = "Assigned Pleader will always have permission to access the case!";
                }else{
                    $permissionsql =    "UPDATE permissions SET disflag = '1' WHERE case_id = (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."') AND pid = '".$per."' AND disflag = '0' ";
                    $permission = $obj->edit($permissionsql);
                    $status = 'true';
                    $msg = "User Remove Successfully.";
                }
            }

    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg
    );
		
    echo json_encode($json_data); 

?>