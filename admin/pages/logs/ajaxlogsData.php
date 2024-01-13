<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

$data = array();
$dataAll = array();
$sql = "SELECT *
        FROM logs
        WHERE disflag = '0' ";
$appDetail = $obj->select($sql);
$totalRecords = count($appDetail);

for ($i = 0; $i < count($appDetail); $i++) {
	$data['0'] = $i+1;
	$data['1'] = $appDetail[$i]['ip_address'];
	$data['2'] = $appDetail[$i]['date'];
	$data['3'] = $appDetail[$i]['day'];
	$data['4'] = $appDetail[$i]['time'];
    
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
