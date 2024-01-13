<?php

    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    if(isset($_GET['doc'])){

        $doc_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['doc']));
        $getteddoc = base64_decode(urldecode($doc_encode));

        $validatedoc = "SELECT cid, docid, docpath FROM casedocs WHERE docid = '".$getteddoc."' ";
        $data = $obj->select($validatedoc);
        if (count($data) == 0){
            header("Location:../../"); die;
        }

        unlink("../../../".$data[0]['docpath']);

        $deletedocpathsql = "DELETE FROM casedocs WHERE docid = '".$data[0]['docid']."' ";
        $deletedocpath = $obj->delete($deletedocpathsql);

        $getCNRsql = "SELECT cnr_number FROM case_details WHERE cid = '".$data[0]['cid']."' ";
        $getCNR = $obj->select($getCNRsql);
        
        header("Location:../case_details/update_case_status.php?cnr=".urlencode(base64_encode($getCNR[0]['cnr_number'])).""); die;

    }else{
        header("Location:../case_details"); 
        die;
    }

?>