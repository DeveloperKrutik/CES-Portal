<?php
    $template = 'pleader_profile';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $pldr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['pldr']));
    $gettedpldr = base64_decode(urldecode($pldr_encode));

    $validatePLDR = "SELECT * FROM assigned_pleaders WHERE email = '".$gettedpldr."' ";
    $data = $obj->select($validatePLDR);
    if (count($data) == 0){
        header("Location:../../"); 
        // echo $gettedpldr;
        die;
    }

    if (isset($_POST['update_profile'])){
        if((isset($_POST['email'])) and (isset($_POST['name'])) and (isset($_POST['phone']))){
  
          if((!empty($_POST['email'])) and (!empty($_POST['name'])) and (!empty($_POST['phone']))){
  
              $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
              $name = mysqli_real_escape_string($obj->CONN, trim($_POST['name']));
              $phone = mysqli_real_escape_string($obj->CONN, trim($_POST['phone']));

              $emaildatasql = "SELECT DISTINCT email FROM permissions WHERE email = '".$email."' ";
              $emaildata = $obj->select($emaildatasql);
              
              if ((count($emaildata) == 0) or ((count($emaildata) > 0) and ($emaildata[0]['email'] == $gettedpldr))){
                    $updatepldrsql = "UPDATE assigned_pleaders SET name = '".$name."', email = '".$email."', phone = '".$phone."' WHERE email = '".$gettedpldr."' ";
                    $obj->edit($updatepldrsql);
                    
                    $updatepersql = "UPDATE permissions SET name = '".$name."', email = '".$email."', phone = '".$phone."' WHERE email = '".$gettedpldr."' ";
                    $obj->edit($updatepersql);

                    echo "<script>alert('Profile Updated Successfully.');</script>";
                    header("Location:../assigned_pleaders/pleaderProfile.php?pldr=".urlencode(base64_encode($email)).""); die;
              }else{
                echo "<script>alert('Entered Email ID Already exists. please try again.');</script>";
              }
              
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
  <title>Admin | Pleader Profile</title>
<?php include_once('../../inc/css.php'); ?>
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
<div class="wrapper">

<?php include_once('../../inc/pre-loader.php'); ?>

  <!-- Navbar -->
  <?php include_once('../../inc/nav.php'); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php include_once('../../inc/vnav.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><i class="nav-icon fas fa-user"></i> &nbsp;&nbsp;Pleader Profile</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Pleader Profile</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">

                <div class="card-header">
                (<strong>Note:</strong> By Updating Pleader's Profile Details, changes will be reflected to the entire system.)
                <a class='btn btn-sm btn-flat btn-danger font-weight-bold float-right' href = '../assigned_pleaders'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
                </div>
              
                <div class="card-body">
                    <form method="post" action="" id="profileform">
                        <div class="form-group">
                            <label for="email">Email:</label>&nbsp;&nbsp;&nbsp;
                            <input type="email" name="email" class="form-control" id="email" value="<?php echo $data[0]['email']; ?>" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>&nbsp;&nbsp;&nbsp;
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $data[0]['name']; ?>" placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                            <label for="phone">Mobile Number:</label>&nbsp;&nbsp;&nbsp;
                            <input type="tel" name="phone" class="form-control" id="phone" value="<?php echo $data[0]['phone']; ?>" placeholder="Enter Mobile Number">
                        </div>
                        <br>
                        <div class="form-group">
                          <input type="submit" class="btn btn-info btn-sm btn-flat" name="update_profile" value="Update Profile">
                        </div>
                    </form>

                    <hr>

                    <div style="text-align:center;">
                        <h4>Assigned Cases to <?php echo $data[0]['name']; ?></h4>
                    </div>
                    <div class="card-body table-responsive p-0" style="height: 300px;">
                        <table class="table table-hover table-bordered table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>CNR Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $sql = "SELECT case_id FROM assigned_pleaders WHERE email = '".$gettedpldr."' ";
    $querydata = $obj->select($sql);
    for ($i = 0; $i < count($querydata); $i++) {
?>
                                <tr>
                                    <td><?php echo $i+1; ?></td>
    <?php
        $cnrsql = "SELECT cnr_number FROM case_details WHERE cid = '".$querydata[$i]['case_id']."' ";
        $cnrdata = $obj->select($cnrsql);
    ?>
                                    <td><?php echo $cnrdata[0]['cnr_number']; ?></td>
                                    <td><a class='btn btn-xs btn-outline-dark font-weight-bold' href='../case_details/case.php?cnr=<?php echo urlencode(base64_encode($cnrdata[0]["cnr_number"])) ?>'><i class="nav-icon fas fa-eye"></i> | View</a></td>
                                </tr>
<?php } ?>
                            </tbody>
                        </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


  </div>
  <!-- /.content-wrapper -->

<?php include_once('../../inc/footer.php'); ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php include_once('../../inc/js.php'); ?>

<script>
$(document).on("submit","#profileform",function(e) {
    if (confirm("Are You Sure to change assigned pleader?") == true) {
        return true;
    } else {
        return false;
    }
});
</script>
</body>
</html>
