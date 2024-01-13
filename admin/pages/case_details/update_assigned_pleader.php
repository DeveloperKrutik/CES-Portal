<?php
    $template = 'update_assigned_pleader';
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

    $pldrdetails = "SELECT name, email, phone FROM assigned_pleaders WHERE case_id = '".$cid[0]['cid']."' ";
    $data = $obj->select($pldrdetails);

    if (isset($_POST['update_pleader'])){
      if(isset($_POST['email'])){

        if(!empty($_POST['email'])){

            $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
            $emaildatasql = "SELECT name, phone FROM permissions WHERE email = '".$email."' ";
            $emaildata = $obj->select($emaildatasql);
            
            if (count($emaildata) == 0){
                if(isset($_POST['name']) AND isset($_POST['phone']) AND !empty($_POST['phone']) AND !empty($_POST['name'])){
                    $name = mysqli_real_escape_string($obj->CONN, trim($_POST['name']));
                    $phone = mysqli_real_escape_string($obj->CONN, trim($_POST['phone']));
                }else{
                    header("Location:../dashboard"); die;
                }
            }else{
                $name = $emaildata[0]['name'];
                $phone = $emaildata[0]['phone'];
            }
    
            $updatepldrsql = "UPDATE assigned_pleaders SET name = '".$name."', email = '".$email."', phone = '".$phone."' WHERE case_id = '".$cid[0]['cid']."' ";
            $obj->edit($updatepldrsql);

            $selectperpldrsql = "SELECT disflag FROM permissions WHERE case_id = '".$cid[0]['cid']."' and email = '".$email."' ";
            $selectperpldr = $obj->select($selectperpldrsql);
            if (count($selectperpldr) == 0){
                $permissionsql =    "INSERT INTO 
                                        permissions(case_id, role, name, email, phone) 
                                        VALUES (
                                            '".$cid[0]['cid']."', 'Government Pleader', '".$name."', '".$email."', '".$phone."'
                                        )";
                $permission = $obj->insert($permissionsql);
            }else{
                if ($selectperpldr[0]['disflag'] == 1){
                    $updatepermission = "UPDATE permissions SET disflag = '0' WHERE case_id = '".$cid[0]['cid']."' and email = '".$email."' ";
                    $obj->edit($updatepermission);
                }
            }
            
            echo "<script>alert('Pleader Updated Successfully.');</script>";
            header("Location:../case_details/edit_case.php?cnr=".urlencode(base64_encode($gettedcnr)).""); die;
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
  <title>Admin | Change Assigned Pleader</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-user-edit"></i> &nbsp;&nbsp;Change Assigned Pleader</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Change Assigned Pleader</li>
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
                <a class='btn btn-sm btn-flat btn-danger font-weight-bold float-right' href = '../case_details/edit_case.php?cnr=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
              </div>

                <div class="card-body p-0">
                    <div class="card-body">
                      <form method="post" action="" id="pldrform">
                        <div class="form-group">
                            <label for="email">Email:</label>&nbsp;&nbsp;&nbsp;
                            <input type="email" name="email" class="form-control" id="email" value="<?php echo $data[0]['email']; ?>" placeholder="Enter Email">
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>&nbsp;&nbsp;&nbsp;
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $data[0]['name']; ?>" placeholder="Enter Name" disabled="disabled">
                        </div>
                        <div class="form-group">
                            <label for="phone">Mobile Number:</label>&nbsp;&nbsp;&nbsp;
                            <input type="tel" name="phone" class="form-control" id="phone" value="<?php echo $data[0]['phone']; ?>" placeholder="Enter Mobile Number" disabled="disabled">
                        </div>
                        <br>
                        <div>(<strong>Note:</strong> By clicking on "Change Assigned Pleader" Button, mentioned pleader will automatically got the permission to access the case details.)</div>
                        <br>
                        <div class="form-group">
                          <input type="submit" class="btn btn-info btn-sm btn-flat" name="update_pleader" value="Change Assigned Pleader">
                        </div>
                      </form>
                    </div>
                </div>
            </div>
          </div>
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

$(document).on("submit","#pldrform",function(e) {
    if (confirm("Are You Sure to change assigned pleader?") == true) {
        return true;
    } else {
        return false;
    }
});

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
</script>
</body>
</html>
