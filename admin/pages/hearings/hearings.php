<?php
    $template = 'hearings';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cnr']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $validateCNR = "SELECT cid FROM case_details WHERE cnr_number = '".$gettedcnr."' ";
    $cid = $obj->select($validateCNR);
    if (count($cid) == 0){
        header("Location:../../"); die;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Hearings</title>
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
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><i class="nav-icon fas fa-gavel"></i> &nbsp;&nbsp;Hearings</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Hearings</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <input type="hidden" value="<?php echo $gettedcnr; ?>" id="cnr">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">CNR Number: <?php echo $gettedcnr; ?></h3>
                <a class='btn btn-sm btn-flat btn-danger font-weight-bold float-right' href = '../case_details/edit_case.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
              </div>

              <!-- /.card-header -->
              <div class="card-body mainContent">
                
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
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

$(document).ready(function(){
    getPage();
});

$(document).on('click','#hrdate, #nxthrdate',function(){
    $('#hrdate, #nxthrdate').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
});

function getPage(){
    var cnr = $("#cnr").val();
    $.ajax({
        type: 'post',
        url: 'ajaxgetPageData.php',
        data: {
            cnr : cnr
        },
        success: function(data){
            const obj = JSON.parse(data);
            if (obj.status == 'false'){
                toastr.error(obj.msg)
            }else{
                $(".mainContent").html(obj.data);
            }
        }
    });
}

$(document).on('click','#addHear',function(){
    var cnr = $("#cnr").val();
    var jname = $("#jname").val();
    var purpose = $("#purpose").val();
    var remarks = $("#remarks").val();
    var case_stage = $("#case_stage").val();
    var court = $("#court").val();
    var hrdate = $("#hrdate").val();
    var nxthrdate = $("#nxthrdate").val();
    $.ajax({
        type: 'post',
        url: 'ajaxaddHearing.php',
        data: {
            cnr : cnr,
            jname : jname,
            purpose : purpose,
            remarks : remarks,
            case_stage : case_stage,
            court : court,
            hrdate : hrdate,
            nxthrdate : nxthrdate
        },
        success: function(data){
            const obj = JSON.parse(data);
            if (obj.status == 'false'){
                toastr.error(obj.msg);
            }else{
                toastr.success(obj.msg);
                getPage();
            }
        }
    });
});

function removeHearing(){
    if (confirm("Are you sure to remove last hearing?") == true) {
        var cnr = $("#cnr").val();
        $.ajax({
            type: 'post',
            url: 'ajaxremoveHearing.php',
            data: {
                cnr : cnr
            },
            success: function(data){
                const obj = JSON.parse(data);
                if (obj.status == 'false'){
                    toastr.error(obj.msg);
                }else{
                    toastr.success(obj.msg);
                    getPage();
                }
            }
        });
    }
}


</script>
</body>
</html>
