<?php
    include_once('../config/common.php');
    $template = "home";

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $personsql = "SELECT name, email, phone FROM permissions WHERE email = '".$_SESSION['user']['email']."' and disflag = '0' ";
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
        <title>Dashboard</title>
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
                    <h1 class="mb-0">
                        <span class="text-custom"><?php echo $persondata[0]['name']; ?></span>
                    </h1>
                    <div class="subheading mb-5">
                        <span><?php echo $persondata[0]['email']; ?></span><br>
                        <span><?php echo $persondata[0]['phone']; ?></span>
                    </div>
                    <p class="lead mb-5">Welcome to the CES Portal. You can track the events and status of court cases through this portal which you have access to. </p>
                    
                    <!-- Number of cases you can access: <strong><?php //echo count($persondata); ?> (<a href="../cases">view cases</a>)</strong> -->
                </div>
            </section>
        </div>
        <!-- JS Files -->
        <?php include_once('../inc/js.php'); ?>
    </body>
</html>
