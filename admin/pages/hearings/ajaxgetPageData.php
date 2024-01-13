<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';
    $data = '';
    $b = "'";

    if (!isset($_POST['cnr'])){
        $status = 'false';
        $msg = 'Something Went Wrong!';
    }else{
        $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));

        $validationsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
        $validationcheck = $obj->select($validationsql);

        if (count($validationcheck) == 0){
            $status = 'false';
            $msg = "CNR Number doesn't exists!";
        }else{
            $status = 'true';

            $sql = "SELECT hid, hearing_no, judge_name, case_stage, court, remarks, hearing_date, next_hearing, purpose 
                    FROM hearings
                    WHERE case_id = (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."') AND disflag = '0' ";
            $querydata = $obj->select($sql);

            $data .= '
                <div>
                    <span>
                        <strong>Note:</strong> Enter Hearings in Sequence. You can not alternate the hearing sequence.
                    </span>
                    <button class="btn btn-sm btn-flat btn-info font-weight-bold float-right" onclick="removeHearing();"><i class="nav-icon fas fa-trash"></i> &nbsp; Delete Last Hearing</button>
                </div><hr>
            ';

            if (count($querydata) > 0){
                $data .= '
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-hover table-bordered table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>#hearing</th>
                                    <th>Judge Name</th>
                                    <th>Hearing Date</th>
                                    <th>Next Hearing</th>
                                    <th>Action</th>
                                </tr>
                        </thead>
                ';
                for ($i = 0; $i < count($querydata); $i++) {
                    $data .= '
                        <tbody>
                            <tr>
                                <td>'.$querydata[$i]['hearing_no'].'</td>
                                <td>'.$querydata[$i]['judge_name'].'</td>
                                <td>'.$querydata[$i]['hearing_date'].'</td>
                                <td>'.$querydata[$i]['next_hearing'].'</td>
                                <td><button class="btn btn-xs btn-dark font-weight-bold" data-toggle="modal" data-target="#hr'.$querydata[$i]['hid'].'"><i class="nav-icon fas fa-eye"></i> | view</button></td>
                            </tr>
                        </tbody>
                        <div class="modal fade" id="hr'.$querydata[$i]['hid'].'">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title">Purpose Of Hearing (#'.$querydata[$i]['hearing_no'].')</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                
                                <div class="modal-body">
                                    <strong>CNR Number: </strong>'.$cnr.'<br><br>
                                    <strong>Judge: </strong>'.$querydata[$i]['judge_name'].'<br><br>
                                    <strong>Case Stage: </strong>'.$querydata[$i]['case_stage'].'<br><br>
                                    <strong>Court: </strong>'.$querydata[$i]['court'].'<br><br>
                                    <strong>Purpose: </strong>'.$querydata[$i]['purpose'].'<br><br>
                                    <strong>Critical Remarks: </strong>'.$querydata[$i]['remarks'].'
                                </div>
                                
                                <div class="modal-footer">
                                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                </div>
                                
                            </div>
                            </div>
                        </div>
                    ';
                }
                $data .= '</table></div><hr><br>';
            }

            $data .= '
                <div class="form-group">
                    <label for="jname">Judge Name:</label>
                    <input type="text" name="jname" class="form-control" id="jname" placeholder="Enter Judge Name" required>
                </div>
                <div class="form-group">
                    <label for="purpose">Purpose Of Hearing:</label>
                    <input type="text" name="purpose" class="form-control" id="purpose" placeholder="Enter Purpose" required>
                </div>
                <div class="form-group">
                    <label for="remarks">Critical Remarks:</label>
                    <textarea class="form-control" rows="3" spellcheck="false" name="remarks" id="remarks" placeholder="Enter Critical Remarks" required></textarea>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="case_stage">Case Stage After this Hearing:</label>&nbsp;&nbsp;&nbsp;
                        <input type="text" name="case_stage" class="form-control" id="case_stage" placeholder="Enter Case Stage" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="court">Court of Hearing:</label>&nbsp;&nbsp;&nbsp;
                        <input type="text" name="court" class="form-control" id="court" placeholder="Enter Court" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                    <label for="hrdate">Hearing Date:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" name="hrdate" class="form-control" id="hrdate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask required>
                    </div>
                    </div>
                    <div class="form-group col-md-6">
                    <label for="nxthrdate">Next Hearing Date:&nbsp;&nbsp;<span style="color:red;">(optional)</span>&nbsp;&nbsp;<i class="nav-icon fas fa-info-circle" title="this field is optional, if court case is closed."></i></label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" name="nxthrdate" class="form-control" id="nxthrdate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                    </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-flat btn-sm" id="addHear">Add Hearing</button>
                </div>
            ';
        }
    }

    $json_data = array(
        "status" => $status,
        "msg" => $msg,
        "data" => $data
    );
		
    echo json_encode($json_data); 
?>