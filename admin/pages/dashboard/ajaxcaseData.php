<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

$data = array();
$dataAll = array();
$sql = "SELECT cnr_number, department, status FROM case_details WHERE disflag = 0";
$appDetail = $obj->select($sql);
$totalRecords = count($appDetail);

for ($i = 0; $i < count($appDetail); $i++) {
	$data['0'] = $appDetail[$i]['cnr_number'];

	$deptsql = "SELECT dept_name FROM govdept WHERE did = '".$appDetail[$i]['department']."' ";
	$dept = $obj->select($deptsql);
	$data['1'] = $dept[0]['dept_name'];
	
	if ($appDetail[$i]['status'] == 'In Progress'){
		$data['2'] = "<span class='badge bg-danger'>".$appDetail[$i]['status']."</span>";
	}else{
		$data['2'] = "<span class='badge bg-success'>".$appDetail[$i]['status']."</span>";
	}

    $data['3'] = "<a class='btn btn-xs btn-outline-info font-weight-bold' title='Edit Case' href='../case_details/edit_case.php?cnr=".urlencode(base64_encode($appDetail[$i]['cnr_number']))."'><i class='nav-icon fas fa-edit'></i> | Edit</a> &nbsp;&nbsp; <a class='btn btn-xs btn-outline-dark font-weight-bold' href='../case_details/case.php?cnr=".urlencode(base64_encode($appDetail[$i]['cnr_number']))."'><i class='nav-icon fas fa-eye'></i> | View</a>";
    
	$dataAll[] = $data;
}
$json_data = array(
		"draw"            => 1,   
		"recordsTotal"    => intval( $totalRecords ),  
		"recordsFiltered" => intval($totalRecords),
		"data"            => $dataAll
		);
		
echo json_encode($json_data);  
?>
