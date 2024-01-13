<?php
    include_once('../config/common.php');
    $template = "forum";

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $personsql = "SELECT case_id, role, email FROM permissions WHERE email = '".$_SESSION['user']['email']."' and disflag = '0' ";
    $persondata = $obj->select($personsql);

    if (count($persondata) == 0){
        header("Location:../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cs']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $forumdatasql = "SELECT * FROM forum WHERE cnr_number = '".$gettedcnr."' ORDER BY id DESC ";
    $forumdata = $obj->select($forumdatasql);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Case Forum</title>
        <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
        <!-- CSS Files -->
        <?php include_once('../inc/css.php'); ?>
    </head>
    <body id="page-top">
        <!-- Navigation -->
        <?php include_once('../inc/nav.php'); ?>
        <!-- Page Content-->
        <div class="container-fluid p-0">
            <!-- About-->
            <section class="resume-section" id="about">
                <div class="resume-section-content">
            <?php
                if(count($forumdata) > 0){
            ?>
                    <div class="subheading mb-5">
                    </div>
                    <div class="row">
                        <div class="col-10">
                            <h4><i class="fa fa-file" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp; Case Forum (<?php echo $gettedcnr; ?>):</h4>
                        </div>
                        <div class="col-2">
                            <a class='btn btn-sm btn-flat btn-custom font-weight-bold flex-left' href = '../cases/casedetails.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
                        </div>
                    </div>
                    <a href = 'writecomment.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-comment'></i> &nbsp; Write your comment</a>

                    <hr>

                <?php
                    $n = count($forumdata);
                    for($i=0; count($forumdata) > $i; $i++){
                        $usersql = "SELECT name FROM permissions WHERE email = '".$forumdata[$i]['email']."' ";
                        $user = $obj->select($usersql);
                ?>
                        <div class="row">
                            <div class="col-11">
                                <h5>#<?php echo $n; ?></h5>
                            </div>
                            <div class="col-1">
                <?php
                    if($forumdata[$i]['email'] == $_SESSION['user']['email']){
                ?>
                                <a href="deletecomment.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>&com=<?php echo $forumdata[$i]['id']; ?>"><i class='nav-icon fas fa-trash'></i></a>
                <?php } ?>
                            </div>
                        </div>

                        <p><strong>From:</strong>  <?php echo $user[$i]['name']; ?></p>
                        <p><strong>Email:</strong>  <?php echo $forumdata[$i]['email']; ?></p>
                        <p><strong>Posted on:</strong>  <?php echo $forumdata[$i]['createdon']; ?></p>

                        <?php if($forumdata[$i]['comment'] != ""){ ?>
                            <p><strong>Comment:</strong>  <?php echo $forumdata[$i]['comment']; ?></p>
                        <?php } ?>
                    <?php 
                        if($forumdata[$i]['isdoc'] == "1"){
                            $forumdocsql = "SELECT docpath FROM forumdocs WHERE fid = '".$forumdata[$i]['id']."' "; 
                            $forumdoc = $obj->select($forumdocsql);

                            for($j=0; $j < count($forumdoc); $j++){
                    ?>
                                <a href="../forum_documents/<?php echo $forumdoc[$j]['docpath']; ?>" target = "blank"><?php echo $forumdoc[$j]['docpath']; ?></a>
                                <br>
                    <?php
                            }
                    ?>
                    <?php } ?>
                    
                    <hr>
                <?php
                        $n = $n - 1;
                    }
                ?>
                    
            <?php }else{ ?>
                <div class="row">
                    <div class="col-10">
                        <h4>No comments on this case (<?php echo $gettedcnr; ?>)</h4>
                    </div>
                    <div class="col-2">
                        <a class='btn btn-sm btn-flat btn-custom font-weight-bold flex-left' href = '../cases/casedetails.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
                    </div>
                </div>
                <a href="writecomment.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>"><i class='nav-icon fas fa-comment'></i> Write your comment</a>
            <?php } ?>
                </div>
            </section>
        </div>
        <!-- JS Files -->
        <?php include_once('../inc/js.php'); ?>
    </body>
</html>
