<?php
    $template = 'dashboard';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    function numberToWord($number){
      if ($number < 1000) {
        $word = number_format($number);
      } else if ($number < 1000000) {
          $word = number_format($number / 1000, 2) . ' K';
      } else if ($number < 1000000000) {
          $word = number_format($number / 1000000, 2) . ' M';
      } else {
          $word = number_format($number / 1000000000, 2) . ' B';
      }
      return $word;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Dashboard</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-tachometer-alt"></i> &nbsp;&nbsp;Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Dashboard</li>
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

                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                      <div class="inner">
                <?php
                  $casedatasql = "SELECT count(cid) AS totalcases FROM case_details WHERE disflag = '0' ";
                  $casedata = $obj->select($casedatasql);
                ?>
                        <h3><?php echo numberToWord($casedata[0]['totalcases']); ?></h3>

                        <h5>Total Cases</h5>
                      </div>
                      <div class="icon">
                        <i class="nav-icon fas fa-gavel"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                      <div class="inner">
                <?php
                  $casedatasql = "SELECT count(cid) AS pendingcases FROM case_details WHERE disflag = '0' AND status = 'In Progress' ";
                  $casedata = $obj->select($casedatasql);
                ?>
                        <h3><?php echo numberToWord($casedata[0]['pendingcases']); ?></h3>

                        <h5>Pending Cases</h5>
                      </div>
                      <div class="icon">
                        <i class="fa fa-clock" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                      <div class="inner">
                <?php
                  $casedatasql = "SELECT count(cid) AS closedcases FROM case_details WHERE disflag = '0' AND status = 'Closed' ";
                  $casedata = $obj->select($casedatasql);
                ?>
                        <h3><?php echo numberToWord($casedata[0]['closedcases']); ?></h3>

                        <h5>Closed Cases</h5>
                      </div>
                      <div class="icon">
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-primary">
                      <div class="inner">
                <?php
                  $todaydate = date("d/m/Y");
                  $hearingdatasql = "SELECT count(case_id) AS todayshearing FROM hearings WHERE next_hearing = '".$todaydate."' ";
                  $hearingdata = $obj->select($hearingdatasql);
                ?>
                        <h3><?php echo $hearingdata[0]['todayshearing']; ?></h3>

                        <h5>Today's Hearings</h5>
                      </div>
                      <div class="icon">
                        <i class="fa fa-clock" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>

                </div>
                <hr>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="dept">Select Government Department:</label>
                      <select class="form-control select2" style="width: 100%;" name="dept" id="dept">
                        <option selected="selected" value="0">Select Department</option>
  <?php
    $deptsql = "SELECT did, dept_name FROM govdept";
    $dept = $obj->select($deptsql);
    for ($i=0; $i < count($dept); $i++){
  ?>
                        <option value="<?php echo $dept[$i]['did']; ?>"><?php echo $dept[$i]['dept_name']; ?></option>
  <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div id="homeData">

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
  $(document).ready(function () {
    $('.select2').select2();
    var dept = $("#dept").val();
    getDashData(dept);
  });

  function getDashData(dept){
    $.ajax({
        type: 'post',
        url: 'ajaxgetDepartmentCases.php',
        data: {
          dept : dept
        },
        success: function(data){
            const obj = JSON.parse(data);
            if (obj.status == 'false'){
                alert(obj.msg);
            }else{
                $("#homeData").html(obj.data); 
            }
        }
    });
  }

  $(document).on('change','#dept',function(){
    var dept = $("#dept").val();
    getDashData(dept);
  });
</script>

</body>
</html>
