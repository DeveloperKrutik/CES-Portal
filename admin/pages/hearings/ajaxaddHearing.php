<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';

    if ((!isset($_POST['cnr'])) OR (!isset($_POST['jname'])) OR (!isset($_POST['purpose'])) OR (!isset($_POST['hrdate'])) OR (!isset($_POST['nxthrdate'])) OR (!isset($_POST['case_stage'])) OR (!isset($_POST['court'])) OR (!isset($_POST['remarks']))){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{

        if (($_POST['cnr'] != "") AND ($_POST['jname'] != "") AND ($_POST['purpose'] != "") AND ($_POST['hrdate'] != "") AND ($_POST['case_stage'] != "") AND ($_POST['court'] != "") AND ($_POST['remarks'] != "")){
        
            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));
            $jname = mysqli_real_escape_string($obj->CONN, trim($_POST['jname']));
            $purpose = mysqli_real_escape_string($obj->CONN, trim($_POST['purpose']));
            $remarks = mysqli_real_escape_string($obj->CONN, trim($_POST['remarks']));
            $case_stage = mysqli_real_escape_string($obj->CONN, trim($_POST['case_stage']));
            $court = mysqli_real_escape_string($obj->CONN, trim($_POST['court']));
            $hrdate = mysqli_real_escape_string($obj->CONN, trim($_POST['hrdate']));
            if ($_POST['nxthrdate'] != ""){
                $nxthrdate = mysqli_real_escape_string($obj->CONN, trim($_POST['nxthrdate']));
            }else{
                $nxthrdate = NULL;
            }

            $validationsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $validationcheck = $obj->select($validationsql);

            if (count($validationcheck) == 0){
                $status = 'false';
                $msg = "CNR Number doesn't exists!";
            }else{
                $cid = $validationcheck[0]['cid'];

                $casessql = "SELECT hid FROM hearings WHERE case_id = '".$cid."' ";
                $cases = $obj->select($casessql);

                $hearing_no = count($cases)+1;

                $inserthearingsql = "INSERT INTO hearings(case_id, hearing_no, judge_name, case_stage, court, hearing_date, next_hearing, purpose, remarks) VALUES ('".$cid."', '".$hearing_no."', '".$jname."', '".$case_stage."', '".$court."', '".$hrdate."', '".$nxthrdate."', '".$purpose."', '".$remarks."') ";
                $inserthearing = $obj->insert($inserthearingsql);

                if ($inserthearing > 0){
                    $status = 'true';
                    $msg = 'Hearing Added Successfully';
                }else{
                    $status = 'false';
                    $msg = 'Something Went Wrong!';
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