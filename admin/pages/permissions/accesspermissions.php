<?php
    $template = 'access_permissions';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Access Permissions</title>
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
            <h1><i class="nav-icon fas fa-universal-access"></i> &nbsp;&nbsp;Case Access Permissions</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Access Permissions</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <input type="hidden" value="<?php echo $_GET['cnr']; ?>" id="cnr">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">CNR Number: <?php echo $_GET['cnr']; ?></h3>
                <a class='btn btn-sm btn-flat btn-info font-weight-bold float-right' href = '../case_details/edit_case.php?cnr=<?php echo urlencode(base64_encode($_GET['cnr'])); ?>'>Next &nbsp; <i class='nav-icon fas fa-arrow-right'></i></a>
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
    toastr.info("Add the Contact Information of a person, who will be able to access the details about this case.")
    getPage();
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

$(document).on('keyup','#email',function(){
    var email = $("#email").val();
    $.ajax({
        type: 'post',
        url: 'ajaxcheckEmail.php',
        data: {
            email : email
        },
        success: function(data){
            const obj = JSON.parse(data);
            if (obj.status == 'false'){
                toastr.error(obj.msg);
            }else if(obj.status == 'true'){
                $("#name").val(obj.name);
                $("#phone").val(obj.phone);
                $("#name").attr('disabled','disabled');
                $("#phone").attr('disabled','disabled');
                toastr.warning("This email already exists. You can not change the name & phone number of a person from here.");
            }else{
                $("#name").val("");
                $("#phone").val("");
                $("#name").removeAttr('disabled','disabled');
                $("#phone").removeAttr('disabled','disabled');
            }
        }
    });
});

$(document).on('click','#sendCreds',function(){
    var cnr = $("#cnr").val();
    var role = $("#role").val();
    var name = $("#name").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    $.ajax({
        type: 'post',
        url: 'ajaxsendCredentials.php',
        data: {
            cnr : cnr,
            role : role,
            name : name,
            email : email,
            phone : phone
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

function removeUser(per){
    if (confirm("Are you sure to remove person?") == true) {
        var cnr = $("#cnr").val();
        $.ajax({
            type: 'post',
            url: 'ajaxremoveUser.php',
            data: {
                cnr : cnr,
                per : per
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
