<?php
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    if(isset($_POST['dept'])){

        $sql = "SELECT case_id, next_hearing FROM hearings";
        $data = $obj->select($sql);


?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>CNR Number</th>
                <th>Hearing Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
        <?php 
            for($i = 0; $i < count($data); $i++){
                if ($_POST['dept'] == 1){
                            
                    $dailydate = date('d/m/Y');
                    if(($data[$i]['next_hearing'] == $dailydate)){
        ?>
        
            <tr>
                <td><?php echo $i+1 ?></td>
        <?php
            $cnrsql = "SELECT cnr_number FROM case_details WHERE cid = '".$data[$i]['case_id']."' ";  
            $cnr = $obj->select($cnrsql);  
        ?>
                <td><?php echo $cnr[0]['cnr_number']; ?></td>
                <td><?php echo $data[$i]['next_hearing']; ?></td>
                <td><a class='btn btn-xs btn-outline-dark font-weight-bold' href="../case_details/case.php?cnr=<?php echo urlencode(base64_encode($cnr[0]['cnr_number'])); ?>"><i class='nav-icon fas fa-eye'></i> | View Case</a></td>
            </tr>
        <?php } }else if (($_POST['dept'] == 2)){;
                    $weekstartDate = date('Y/m/d', strtotime("this week"));
                    $weekendDate = date('Y/m/d', strtotime("this week +5 days"));
                    if ((date("Y/m/d", strtotime($data[$i]['next_hearing'])) > $weekstartDate))
        ?>
        
            <tr>
                <td><?php echo $i+1 ?></td>
        <?php
            $cnrsql = "SELECT cnr_number FROM case_details WHERE cid = '".$data[$i]['case_id']."' ";  
            $cnr = $obj->select($cnrsql);  
        ?>
                <td><?php echo $cnr[0]['cnr_number']; ?></td>
                <td><?php echo $data[$i]['next_hearing']; ?></td>
                <td><a class='btn btn-xs btn-outline-dark font-weight-bold' href="../case_details/case.php?cnr=<?php echo urlencode(base64_encode($cnr[0]['cnr_number'])); ?>"><i class='nav-icon fas fa-eye'></i> | View Case</a></td>
            </tr>
        <?php } } ?>
            </tbody>
        </table>
<?php
    }
?>