<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

$data = array();
$dataAll = array();
$sql = "SELECT COUNT(plid) AS cases, name, email, phone
        FROM assigned_pleaders
        GROUP BY email";
$appDetail = $obj->select($sql);
$totalRecords = count($appDetail);

for ($i = 0; $i < count($appDetail); $i++) {
	$data['0'] = $i+1;
	$data['1'] = $appDetail[$i]['name'];
	$data['2'] = $appDetail[$i]['email'];
	$data['3'] = $appDetail[$i]['phone'];
	$data['4'] = $appDetail[$i]['cases'];
    $data['5'] = "<a class='btn btn-xs btn-info font-weight-bold' href='pleaderProfile.php?pldr=".urlencode(base64_encode($appDetail[$i]['email']))."'><i class='nav-icon fas fa-eye'></i> | Profile</a>";
    
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
