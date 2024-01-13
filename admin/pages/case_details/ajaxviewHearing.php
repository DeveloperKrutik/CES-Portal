<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';
    $data = '';

    if (!isset($_POST['hid'])){
        $status = 'false';
        $msg = 'Something Went Wrong! Please try again.';
    }else{
        $hid = mysqli_real_escape_string($obj->CONN, trim($_POST['hid']));
        $status = 'true';

        $hearingsql = "SELECT case_id, hid, hearing_no, judge_name, court, case_stage, hearing_date, next_hearing, purpose, remarks
            FROM hearings
            WHERE hid = '".$hid."' ";
        $hearings = $obj->select($hearingsql);

        $cnrsql = "SELECT cnr_number, case_type, reg_number, opposition_party_name, department FROM case_details WHERE cid = '".$hearings[0]['case_id']."' ";
        $cnr = $obj->select($cnrsql);

        $deptnamesql = "SELECT dept_name FROM govdept WHERE did = '".$cnr[0]['department']."' ";
        $deptname = $obj->select($deptnamesql);

        $data .= '
            <div style="text-align:center;" class="h4 font-weight-bold">-: Hearing '.$hearings[0]['hearing_no'].' :-</div>
            <table class="table table-bordered table-hover table-sm">
                <tr>
                    <th colspan="2">CNR Number</th>
                    <td colspan="2">'.$cnr[0]['cnr_number'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Case Type</th>
                    <td colspan="2">'.$cnr[0]['case_type'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Case Number</th>
                    <td colspan="2">'.$cnr[0]['reg_number'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Hearing Date</th>
                    <td colspan="2">'.$hearings[0]['hearing_date'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Judge</th>
                    <td colspan="2">'.$hearings[0]['judge_name'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Court</th>
                    <td colspan="2">'.$hearings[0]['court'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Department</th>
                    <td colspan="2">'.$deptname[0]['dept_name'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Opposition Party</th>
                    <td colspan="2">'.$cnr[0]['opposition_party_name'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Case Stage</th>
                    <td colspan="2">'.$hearings[0]['case_stage'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Purpose Of Hearing</th>
                    <td colspan="2">'.$hearings[0]['purpose'].'</td>
                </tr>
                <tr>
                    <th colspan="2">Critical Remarks</th>
                    <td colspan="2">'.$hearings[0]['remarks'].'</td>
                </tr>   
                <tr>
                    <th colspan="2">Next Hearing</th>
                    <td colspan="2">'.$hearings[0]['next_hearing'].'</td>
                </tr>
            </table>
            <br>
            <button class="btn btn-danger btn-sm btn-flat" id="backbtn">Back</button>
        ';
    }


$json_data = array(
		"status"            => $status,   
		"data"    => $data,  
		"msg" => $msg
		);
		
echo json_encode($json_data);  
?>
