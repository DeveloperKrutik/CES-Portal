<?php
include_once('../config/common.php');

if (!isset($_SESSION['user'])){
    header("Location:../"); die;
}

$gettedcom = mysqli_real_escape_string($obj->CONN, trim($_GET['com']));
$cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cs']));
$gettedcnr = base64_decode(urldecode($cnr_encode));

$emailsql = "SELECT email FROM forum WHERE id = '$gettedcom' ";
$email = $obj->select($emailsql);

if($_SESSION['user']['email'] == $email[0]['email']){
    $deletecomsql = "DELETE FROM forum WHERE id = '".$gettedcom."' ";
    $obj->delete($deletecomsql);
}

header("Location: index.php?cs=".urlencode(base64_encode($gettedcnr))."");
?>