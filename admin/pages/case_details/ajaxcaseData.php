<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

$data = array();
$dataAll = array();
$sql = "SELECT cnr_number, status FROM case_details WHERE disflag = 0";
$appDetail = $obj->select($sql);
$totalRecords = count($appDetail);

for ($i = 0; $i < count($appDetail); $i++) {
	$data['0'] = $i+1;
	$data['1'] = $appDetail[$i]['cnr_number'];
	
	if ($appDetail[$i]['status'] == 'In Progress'){
		$data['2'] = "<span class='badge bg-warning'>".$appDetail[$i]['status']."</span>";
	}else{
		$data['2'] = "<span class='badge bg-success'>".$appDetail[$i]['status']."</span>";
	}

    $data['3'] = "<a class='btn btn-xs btn-outline-info font-weight-bold' title='Edit Case' href='edit_case.php?cnr=".urlencode(base64_encode($appDetail[$i]['cnr_number']))."'><i class='nav-icon fas fa-edit'></i> | Edit</a> &nbsp;&nbsp; <a class='btn btn-xs btn-outline-dark font-weight-bold' href='case.php?cnr=".urlencode(base64_encode($appDetail[$i]['cnr_number']))."'><i class='nav-icon fas fa-eye'></i> | View</a>";
    
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
