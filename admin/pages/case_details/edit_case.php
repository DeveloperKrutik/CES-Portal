<?php
    $template = 'edit_case';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cnr']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $validateCNR = "SELECT cid FROM case_details WHERE cnr_number = '".$gettedcnr."' ";
    if (count($obj->select($validateCNR)) == 0){
        header("Location:../../"); die;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Edit Case Details</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp;Edit Case Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Edit Case Details</li>
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
                <h3 class="card-title">CNR Number: <?php echo $gettedcnr; ?></h3>
                <a class='btn btn-sm btn-flat btn-danger font-weight-bold float-right' href = '../case_details'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
              </div>

              <div class="card-body">
                <div class="list-group" style="font-size: 20px;">
                    <a href='update_case_details.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>' class="list-group-item">Update Case Details</a>
                    <a href='../hearings/hearings.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>' class="list-group-item">Update Hearing Details</a>
                    <a href='update_assigned_pleader.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>' class="list-group-item">Change Assigned Pleader</a>
                    <a href='../permissions/accesspermissions.php?cnr=<?php echo $gettedcnr; ?>' class="list-group-item">Update Case Access Permissions</a>
                    <a href='update_case_status.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>' class="list-group-item">Update Case Status</a>
                </div>
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

</body>
</html>
