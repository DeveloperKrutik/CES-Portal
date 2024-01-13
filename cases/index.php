<?php
    include_once('../config/common.php');
    $template = "cases";

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $personsql = "SELECT case_id, role, email FROM permissions WHERE email = '".$_SESSION['user']['email']."' and disflag = '0' ";
    $persondata = $obj->select($personsql);

    if (count($persondata) == 0){
        header("Location:../"); die;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>View Cases</title>
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
                    <div class="subheading mb-5">
                        <span><i class="fa fa-gavel" aria-hidden="true"></i>  Accessible Cases:</span>
                    </div>
                    <table class="table table-bordered table-responsive table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fa fa-gavel" aria-hidden="true"></i> CNR Number</th>
                                <th scope="col"><i class="fa fa-user-tag" aria-hidden="true"></i> Role</th>
                                <th scope="col"><i class="fa fa-tasks" aria-hidden="true"></i> Action</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php for ($i=0; count($persondata) > $i; $i++){ ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
        <?php
            $cnrsql = "SELECT cnr_number FROM case_details WHERE cid = '".$persondata[$i]['case_id']."' ";
            $cnr = $obj->select($cnrsql);
        ?>
                                <td><?php echo $cnr[0]['cnr_number']; ?></td>
        <?php
            $rolesql = "SELECT plid FROM assigned_pleaders WHERE case_id = '".$persondata[$i]['case_id']."' AND email = '".$persondata[$i]['email']."' ";
            $rolesql = $obj->select($rolesql);
            if (count($rolesql) > 0){
        ?>
                                <td><strong class="text-custom">Assigned Pleader</strong></td>
        <?php }else{ ?>
                                <td><?php echo $persondata[$i]['role']; ?></td>
        <?php } ?>
                                <td><a href="casedetails.php?cs=<?php echo urlencode(base64_encode($cnr[0]['cnr_number'])); ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> view</a></td>
                            </tr>
    <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <!-- JS Files -->
        <?php include_once('../inc/js.php'); ?>
    </body>
</html>
