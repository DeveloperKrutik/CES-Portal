<?php
    $template = 'totalhearings';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Total Hearings</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-tachometer-alt"></i> &nbsp;&nbsp;Total Hearings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Total Hearings</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="dept">Filter Durations:</label>
                      <select class="form-control select2" style="width: 100%;" name="dept" id="dept">
                        <option selected="selected" value="0">Select Duration</option>
                        <option value="1">Daily</option>
                        <option value="2">Weekly</option>
                        <option value="3">Monthly</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div id="hearingtable">
                    <table id="datatable_data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                                <th><i class="fas fa-circle" aria-hidden="true"></i>&nbsp;&nbsp;Hearing Date</th>
                                <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th><i class="fas fa-gavel"></i>&nbsp;&nbsp;CNR Number</th>
                                <th><i class="fas fa-circle" aria-hidden="true"></i>&nbsp;&nbsp;Hearing Date</th>
                                <th><i class="fas fa-tasks"></i>&nbsp;&nbsp;Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


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
        "sAjaxSource":"ajaxhearingData.php",
        "dom": 'lBfrtip',
        "responsive": true,
        "lengthChange": false, 
        "autoWidth": false,
        "order": [[ 0, "desc" ]],
        "search": {
        "search": $("#deptname").val()
        }
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});

function getDashData(dept){
    if(dept == 0){
        location.reload();
    }else{
        $.ajax({
            type: 'post',
            url: 'ajaxgetHearingCases.php',
            data: {
            dept : dept
            },
            success: function(data){
                $("#hearingtable").html(data);
            }
        });
    }
}

$(document).on('change','#dept',function(){
  var dept = $("#dept").val();
  getDashData(dept);
});
</script>

</body>
</html>
