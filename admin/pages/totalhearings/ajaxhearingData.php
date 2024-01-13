<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

$data = array();
$dataAll = array();
$sql = "SELECT case_id, next_hearing FROM hearings";
$appDetail = $obj->select($sql);
$totalRecords = count($appDetail);

for ($i = 0; $i < count($appDetail); $i++) {

        $data['0'] = $i + 1;

        $cnrsql = "SELECT cnr_number FROM case_details WHERE cid = '".$appDetail[$i]['case_id']."' ";
        $cnr = $obj->select($cnrsql);

        $data['1'] = $cnr[0]['cnr_number'];

        $data['2'] = $appDetail[$i]['next_hearing'];

        $data['3'] = "<a class='btn btn-xs btn-outline-dark font-weight-bold' href='../case_details/case.php?cnr=".urlencode(base64_encode($cnr[0]['cnr_number']))."'><i class='nav-icon fas fa-eye'></i> | View Case</a>";
        
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
