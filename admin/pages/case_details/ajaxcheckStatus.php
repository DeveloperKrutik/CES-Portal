<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';
    $data = '';

    if ((!isset($_POST['status'])) or (!isset($_POST['cnr']))){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{
        if ($_POST['status'] == 2){
            $status = mysqli_real_escape_string($obj->CONN, trim($_POST['status']));
            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));

            $casedatasql = "SELECT cid, status, department, favour, decision FROM case_details WHERE cnr_number = '".$cnr."' ";
            $casedata = $obj->select($casedatasql);

            $deptsql = "SELECT dept_name FROM govdept WHERE did = '".$casedata[0]['department']."' ";
            $dept = $obj->select($deptsql);

            $status = 'true';
            $data .= '
                <div class="form-group">
                    <label for="favour">In whose favour the decision went:</label>
                    <select class="form-control select2" style="width: 100%;" name="favour" id="favour" required>';
            if($casedata[0]['favour'] == 'gov'){
            $data .= '
                        <option value="0">Select Party</option>
                        <option value="1" selected="selected">Government ('.$dept[0]['dept_name'].')</option>
                        <option value="2">Opposition</option>';
            }else if($casedata[0]['favour'] == 'opp'){
                $data .= '
                            <option value="0">Select Party</option>
                            <option value="1">Government ('.$dept[0]['dept_name'].')</option>
                            <option value="2" selected="selected">Opposition</option>';
            }else{
                $data .= '
                            <option value="0" selected="selected">Select Party</option>
                            <option value="1">Government ('.$dept[0]['dept_name'].')</option>
                            <option value="2">Opposition</option>';
            }
            $data .= '
                    </select>
                </div>

                <div class="form-group">
                    <label for="decision">Final Decision:</label>
                    <textarea class="form-control" rows="3" spellcheck="false" name="decision" id="decision" required placeholder="Enter Final Decision" >'.$casedata[0]['decision'].'</textarea>
                </div><hr>

                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="judgement" name="judgement[]" multiple>
                    <label class="custom-file-label font-weight-bold" for="customFile">Upload Judgement Documents</label>
                </div><hr>
            ';
            $getdocsql = "SELECT cid, docid, docpath FROM casedocs WHERE cid = '".$casedata[0]['cid']."' ";
            $getdoc = $obj->select($getdocsql);

            if(count($getdoc) > 0){
                for($i=0; $i < count($getdoc); $i++){
                    $j = $i + 1;
                    $data .= '
                        <span class="font-weight-bold" style="font-size:20px">Document '.$j.'</span>
                        &nbsp;&nbsp;&nbsp;
                        <span><a href="../../../'.$getdoc[$i]['docpath'].'" target="_blank" class="btn btn-success btn-flat btn-sm"><i class="fa fa-download" aria-hidden="true"></i> | Download</a></span>
                        &nbsp;&nbsp;&nbsp;
                        <span><a href="deleteJudgementDocument.php?doc='.urlencode(base64_encode($getdoc[$i]['docid'])).'" class="btn btn-danger btn-flat btn-sm" id="removeDoc"><i class="fa fa-trash" aria-hidden="true"></i> | Delete</a></span><hr>
                    ';
                }
            }
        }else{
            $status = 'true';
            $data = '';
        }
    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    );
		
    echo json_encode($json_data); 

?>