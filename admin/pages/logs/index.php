<?php
    $template = 'logs';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Log History</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-history"></i> &nbsp;&nbsp;Log History</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Log History</li>
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
              
              <div class="card-body">
                <table id="datatable_data" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th><i class="fas fa-address-card"></i>&nbsp;&nbsp;IP Address</th>
                    <th><i class="fas fa-calendar"></i>&nbsp;&nbsp;Date</th>
                    <th><i class="fas fa-sun"></i>&nbsp;&nbsp;Day</th>
                    <th><i class="fas fa-clock"></i>&nbsp;&nbsp;Time</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  </tbody>
                  <!-- <tfoot>
                  <tr>
                    <th>#</th>
                    <th><i class="fas fa-user"></i>&nbsp;&nbsp;Name</th>
                    <th><i class="fas fa-envelope"></i>&nbsp;&nbsp;Email ID</th>
                    <th><i class="fas fa-phone"></i>&nbsp;&nbsp;Mobile No.</th>
                    <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;No. Of Cases Assigned</th>
                    <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                  </tr>
                  </tfoot> -->
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
$(function () {
  $("#datatable_data").DataTable({
    "sAjaxSource":"ajaxlogsData.php",
    "dom": 'lBfrtip',
    "responsive": true,
    "lengthChange": false, 
    "autoWidth": false,
    "order": [[ 0, "desc" ]],
    "buttons": ["copy", "csv", "excel", "pdf", "print"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
</body>
</html>
