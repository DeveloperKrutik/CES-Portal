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

            $cidsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $cid = $obj->select($cidsql);

            $sql = "SELECT pid, role, name, email, phone 
                    FROM permissions 
                    WHERE case_id = '".$cid[0]['cid']."' AND disflag = '0' ";
            $querydata = $obj->select($sql);            

            if (count($querydata) > 0){
                $data .= '
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-hover table-bordered table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email ID</th>
                                    <th>Mobile No.</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                        </thead>
                ';
                for ($i = 0; $i < count($querydata); $i++) {
                    $data .= '
                        <tbody>
                            <tr>
                                <td>'.$i.'</td>
                                <td>'.$querydata[$i]['name'].'</td>
                                <td>'.$querydata[$i]['email'].'</td>
                                <td>'.$querydata[$i]['phone'].'</td>
                                <td>'.$querydata[$i]['role'].'</td> ';
                $asspldrsql = "SELECT plid FROM assigned_pleaders WHERE case_id = '".$cid[0]['cid']."' AND email = '".$querydata[$i]['email']."' ";
                $asspldr = $obj->select($asspldrsql);
                if (count($asspldr) > 0){
                    $data .= '
                                <td><span class="badge bg-success" title="You can not remove Assigned Pleader from accessing the case!">Assigned Pleader</span></td> ';
                }else{
                    $data .= '
                                <td><button class="btn btn-xs btn-outline-danger font-weight-bold" title="Remove User from accessing case details" onclick="removeUser('.$b.$querydata[$i]['pid'].$b.');"><i class="nav-icon fas fa-trash"></i> | Remove</button></td> ';
                }
                    $data .= '
                            </tr>
                        </tbody>
                    ';
                }
                $data .= '</table></div><hr><br>';
            }

            $data .= '
                <div class="form-group">
                    <label for="role">Select Role:</label>
                    <select class="custom-select" id="role">
                    <option value="0">Select Role</option>
                    <option value="1">Government Pleader</option>
                    <option value="2">Government Officer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>&nbsp;&nbsp;&nbsp;
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email">
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>&nbsp;&nbsp;&nbsp;
                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name">
                </div>
                <div class="form-group">
                    <label for="phone">Mobile Number:</label>&nbsp;&nbsp;&nbsp;
                    <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter Mobile Number">
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-flat btn-sm" id="sendCreds">Invite User</button>
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