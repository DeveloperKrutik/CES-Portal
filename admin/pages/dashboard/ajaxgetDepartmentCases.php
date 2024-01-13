<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $status = '';
    $msg = '';
    $data = '';

    if (!isset($_POST['dept'])){
        $status = 'false';
        $msg = 'Something Went Wrong! Please try again.';
    }else{

        function numberToWord($number){
          if ($number < 1000) {
            $word = number_format($number);
          } else if ($number < 1000000) {
              $word = number_format($number / 1000, 2) . 'K';
          } else if ($number < 1000000000) {
              $word = number_format($number / 1000000, 2) . 'M';
          } else {
              $word = number_format($number / 1000000000, 2) . 'B';
          }
          return $word;
        }

        $dept = mysqli_real_escape_string($obj->CONN, trim($_POST['dept']));
        $status = 'true';

        $getdeptsql = "SELECT did, dept_name FROM govdept WHERE did = '".$dept."' ";
        $getdept = $obj->select($getdeptsql);

        if(count($getdept) > 0){
            $casedatasql = "SELECT cnr_number, status FROM case_details WHERE department = '".$getdept[0]['did']."' ";
            $casedata = $obj->select($casedatasql);

            $casedatasql0 = "SELECT count(cid) AS totalcases FROM case_details WHERE disflag = '0' AND department = '".$getdept[0]['did']."' ";
            $casedata0 = $obj->select($casedatasql0);

            $casedatasql1 = "SELECT count(cid) AS pendingcases FROM case_details WHERE disflag = '0' AND status = 'In Progress' AND department = '".$getdept[0]['did']."' ";
            $casedata1 = $obj->select($casedatasql1);

            $casedatasql2 = "SELECT count(cid) AS closedcases FROM case_details WHERE disflag = '0' AND status = 'Closed' AND department = '".$getdept[0]['did']."' ";
            $casedata2 = $obj->select($casedatasql2);
            
            $data = '
                <div class="row">

                    <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-dark">
                        <div class="inner">
                        <h5><strong>'.numberToWord($casedata0[0]["totalcases"]).'</strong> Total Cases</h5>
                        </div>
                    </div>
                    </div>

                    <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-dark">
                        <div class="inner">
                        <h5><strong>'.numberToWord($casedata1[0]["pendingcases"]).'</strong> Pending Cases</h5>
                        </div>
                    </div>
                    </div>
                    
                    <div class="col-lg-4 col-6">
                    <!-- small box -->
                    <div class="small-box bg-dark">
                        <div class="inner">
                        <h5><strong>'.numberToWord($casedata2[0]["closedcases"]).'</strong> Closed Cases</h5>
                        </div>
                    </div>
                    </div>

                </div>
                <hr>
                
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                            <th><i class="fas fa-circle"></i>&nbsp;&nbsp;Department</th>
                            <th><i class="fas fa-search"></i>&nbsp;&nbsp;Case Status</th>
                            <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                        </tr>
                    </thead>
                    <tbody> ';
    if(count($casedata) > 4){
        for ($i=1; $i <= 4; $i++){
            $data .= '
                <tr>
                    <td>'.$casedata[$i]["cnr_number"].'</td>
                    <td>'.$getdept[0]["dept_name"].'</td>
                    <td><span class="badge bg-dark">'.$casedata[$i]["status"].'</span></td>
                    <td>
                        <a class="btn btn-xs btn-outline-info font-weight-bold" title="Edit Case" href="../case_details/edit_case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-edit"></i> | Edit</a> &nbsp;&nbsp; <a class="btn btn-xs btn-outline-dark font-weight-bold" href="../case_details/case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-eye"></i> | View</a>
                    </td>
                </tr>
            ';   
        } 
        $data .= '
                </tbody>
            </table>
            <a href="viewAllCases.php?dept='.urlencode(base64_encode($getdept[0]['did'])).'" class="btn btn-sm btn-info btn-flat float-right font-weight-bold">View More</a>
        ';
    }else if(count($casedata) > 0){
        for ($i=0; $i < count($casedata); $i++){
            $data .= '
                <tr>
                    <td>'.$casedata[$i]["cnr_number"].'</td>
                    <td>'.$getdept[0]["dept_name"].'</td>
                    <td><span class="badge bg-dark">'.$casedata[$i]["status"].'</span></td>
                    <td>
                        <a class="btn btn-xs btn-outline-info font-weight-bold" title="Edit Case" href="../case_details/edit_case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-edit"></i> | Edit</a> &nbsp;&nbsp; <a class="btn btn-xs btn-outline-dark font-weight-bold" href="../case_details/case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-eye"></i> | View</a>
                    </td>
                </tr>
            ';   
        }
        $data .= '
                </tbody>
            </table>
        ';
    }else{
        $data .= '
            <tr>
                <td colspan="4" style="text-align:center;">No Cases Available</td>
            </tr>
        ';   
        $data .= '
                </tbody>
            </table>
        ';
    }
        }else{
            $status = 'true';
            $casedatasql = "SELECT cnr_number, status, department FROM case_details ";
            $casedata = $obj->select($casedatasql);
            $data = '
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                            <th><i class="fas fa-circle"></i>&nbsp;&nbsp;Department</th>
                            <th><i class="fas fa-search"></i>&nbsp;&nbsp;Case Status</th>
                            <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                        </tr>
                    </thead>
                    <tbody> ';
    if(count($casedata) > 4){
        for ($i=1; $i <= 4; $i++){
            $deptsql = "SELECT dept_name FROM govdept WHERE did = '".$casedata[$i]["department"]."' ";
            $dept = $obj->select($deptsql);
            $data .= '
                <tr>
                    <td>'.$casedata[$i]["cnr_number"].'</td>
                    <td>'.$dept[0]["dept_name"].'</td>
                    <td><span class="badge bg-dark">'.$casedata[$i]["status"].'</span></td>
                    <td>
                        <a class="btn btn-xs btn-outline-info font-weight-bold" title="Edit Case" href="../case_details/edit_case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-edit"></i> | Edit</a> &nbsp;&nbsp; <a class="btn btn-xs btn-outline-dark font-weight-bold" href="../case_details/case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-eye"></i> | View</a>
                    </td>
                </tr>
            ';   
        }
        $data .= '
                </tbody>
            </table>
            <a href="viewAllCases.php?dept=0" class="btn btn-sm btn-info btn-flat float-right font-weight-bold">View More</a>
        ';
    }else if(count($casedata) > 0){
        for ($i=0; $i < count($casedata); $i++){
            $deptsql = "SELECT dept_name FROM govdept WHERE did = '".$casedata[$i]["department"]."' ";
            $dept = $obj->select($deptsql);
            $data .= '
                <tr>
                    <td>'.$casedata[$i]["cnr_number"].'</td>
                    <td>'.$dept[0]["dept_name"].'</td>
                    <td><span class="badge bg-dark">'.$casedata[$i]["status"].'</span></td>
                    <td>
                        <a class="btn btn-xs btn-outline-info font-weight-bold" title="Edit Case" href="../case_details/edit_case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-edit"></i> | Edit</a> &nbsp;&nbsp; <a class="btn btn-xs btn-outline-dark font-weight-bold" href="../case_details/case.php?cnr='.urlencode(base64_encode($casedata[$i]["cnr_number"])).'"><i class="nav-icon fas fa-eye"></i> | View</a>
                    </td>
                </tr>
            ';   
        }
        $data .= '
                </tbody>
            </table>
        ';
    }else{
        $data .= '
            <tr>
                <td colspan="4" style="text-align:center;">No Cases Available</td>
            </tr>
        '; 
        $data .= '
                </tbody>
            </table>
        ';  
    }
        }
    }

    $json_data = array(
		"status"            => $status,   
		"data"    => $data,  
		"msg" => $msg
		);
		
    echo json_encode($json_data); 
?>