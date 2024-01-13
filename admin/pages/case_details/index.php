<?php
    $template = 'case_details';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Case Details</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-info-circle"></i> &nbsp;&nbsp;Case Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Case Details</li>
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
                    <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                    <th><i class="fas fa-search"></i>&nbsp;&nbsp;Case Status</th>
                    <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                    <th><i class="fas fa-search"></i>&nbsp;&nbsp;Case Status</th>
                    <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                  </tr>
                  </tfoot>
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
    "sAjaxSource":"ajaxcaseData.php",
    "dom": 'lBfrtip',
    "responsive": true,
    "lengthChange": false, 
    "autoWidth": false,
    "order": [[ 0, "desc" ]],
    "buttons": ["copy", "csv", "excel", "pdf", "print"],
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>
</body>
</html>
