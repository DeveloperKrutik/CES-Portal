<?php
    include_once('../config/common.php');
    $template = "update_profile";

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $personsql = "SELECT name, email, phone FROM permissions WHERE email = '".$_SESSION['user']['email']."' and disflag = '0' ";
    $persondata = $obj->select($personsql);

    if (count($persondata) == 0){
        header("Location:../"); die;
    }

    if (isset($_POST['update'])){
        if((isset($_POST['name'])) and (isset($_POST['phone']))){
            if((!empty($_POST['name'])) and (!empty($_POST['phone']))){
                $name = mysqli_real_escape_string($obj->CONN, trim($_POST['name']));
                $phone = mysqli_real_escape_string($obj->CONN, trim($_POST['phone']));

                $updatepldrsql = "UPDATE assigned_pleaders SET name = '".$name."', phone = '".$phone."' WHERE email = '".$_SESSION['user']['email']."' ";
                $obj->edit($updatepldrsql);
                
                $updatepersql = "UPDATE permissions SET name = '".$name."', phone = '".$phone."' WHERE email = '".$_SESSION['user']['email']."' ";
                $obj->edit($updatepersql);
                
                header("Location:../profile"); 
                die;
            }else{
                echo "<script>alert('All Fields are required to fill. please try again.');</script>";
            }
        }else{
            echo "<script>alert('Something Went Wrong! please try again.');</script>";
        }
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
                    <div class="subheading mb-5">
                        <span><i class="fa fa-edit" aria-hidden="true"></i>  Update Your Profile:</span>
                    </div>
                    <form method="post" action="" id="profileform">
                        <div class="form-group mb-3">
                            <label for="name"><strong>Name:</strong></label>&nbsp;&nbsp;&nbsp;
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $persondata[0]['name']; ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone"><strong>Mobile Number:</strong></label>&nbsp;&nbsp;&nbsp;
                            <input type="tel" name="phone" class="form-control" id="phone" value="<?php echo $persondata[0]['phone']; ?>">
                            <span class="text-custom"><strong>Note:</strong> This Changes will be reflected in all cases in which you have been involved.</span>
                        </div>
                        <div class="form-group mb-3">
                            <input type="submit" class="btn btn-custom btn-flat" name="update" value="Update Changes">
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!-- JS Files -->
        <?php include_once('../inc/js.php'); ?>

        <script>
            $(document).on("submit","#profileform",function(e) {
                if (confirm("Are You Sure to update your profile information?") == true) {
                    return true;
                } else {
                    return false;
                }
            });
        </script>

    </body>
</html>
