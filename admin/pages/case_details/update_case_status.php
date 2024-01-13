<?php
    $template = 'update_case_status';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cnr']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $validateCNR = "SELECT status, favour, decision FROM case_details WHERE cnr_number = '".$gettedcnr."' ";
    $data = $obj->select($validateCNR);
    if (count($data) == 0){
        header("Location:../../"); die;
    }

    if (isset($_POST['update_status'])){
      if(isset($_POST['cnr']) AND isset($_POST['status'])){

        if(!empty($_POST['cnr']) AND ($_POST['status'] != 0)){

            $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));
            $status = mysqli_real_escape_string($obj->CONN, trim($_POST['status']));

            $getcidsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $getcid = $obj->select($getcidsql);

            if (count($getcid) > 0){
            
            if($status == 1){
              $status = "In Progress";
              $updatestatussql = "UPDATE case_details SET status = '".$status."', favour = NULL, decision = NULL WHERE cnr_number = '".$gettedcnr."' ";
              $obj->edit($updatestatussql);
              echo "<script>alert('Case Status Updated Successfully. (CNR:".$cnr.")');</script>";
              header("Location:../case_details/update_case_status.php?cnr=".urlencode(base64_encode($cnr)).""); die;
            }else if($status == 2){
              $status = "Closed";

              if(isset($_POST['favour']) AND isset($_POST['decision'])){
                if(!empty($_POST['decision']) AND ($_POST['favour'] != 0) AND (count($_FILES['judgement']['name']) > 0)){
                  $favour = mysqli_real_escape_string($obj->CONN, trim($_POST['favour']));
                  $decision = mysqli_real_escape_string($obj->CONN, trim($_POST['decision']));

                  if($favour == 1){
                    $favour = 'gov';
                    $countfiles = count($_FILES['judgement']['name']);
                    for($i=0;$i<$countfiles;$i++){
                      $path = '../../../final_judgements/';
                      $img = $_FILES['judgement']['name'][$i];
                      $tmp = $_FILES['judgement']['tmp_name'][$i];
                      // get uploaded file's extension
                      $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                      // can upload same image using rand function
                      $final_image = rand(1000,1000000).$img;
                      while(true){
                        if(file_exists('../../../final_judgements/'.$final_image)){
                          $final_image = rand(1000,1000000).$final_image;
                        }else{
                          break;
                        }
                      }
                      $path = $path.strtolower($final_image); 
                      if(move_uploaded_file($tmp,$path)){
                        $insertdocsql = "INSERT INTO casedocs(cid, docpath) VALUES ('".$getcid[0]['cid']."', 'final_judgements/".$final_image."')";
                        $obj->insert($insertdocsql);
                      }else{
                        echo "<script>alert('Something Went Wrong! please try again.');</script>";
                      }
                    }
                    $updatestatussql = "UPDATE case_details SET status = '".$status."', favour = '".$favour."', decision = '".$decision."' WHERE cnr_number = '".$gettedcnr."' ";
                    $obj->edit($updatestatussql);
                    echo "<script>alert('Case Status Updated Successfully. (CNR:".$cnr.")');</script>";
                    header("Location:../case_details/update_case_status.php?cnr=".urlencode(base64_encode($cnr)).""); die;
                  }else if($favour == 2){
                    $favour = 'opp';
                    $countfiles = count($_FILES['judgement']['name']);
                    for($i=0;$i<$countfiles;$i++){
                      $path = '../../../final_judgements/';
                      $img = $_FILES['judgement']['name'][$i];
                      $tmp = $_FILES['judgement']['tmp_name'][$i];
                      // get uploaded file's extension
                      $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                      // can upload same image using rand function
                      $final_image = rand(1000,1000000).$img;
                      while(true){
                        if(file_exists('../../../final_judgements/'.$final_image)){
                          $final_image = rand(1000,1000000).$final_image;
                        }else{
                          break;
                        }
                      }
                      $path = $path.strtolower($final_image); 
                      if(move_uploaded_file($tmp,$path)){
                        $insertdocsql = "INSERT INTO casedocs(cid, docpath) VALUES ('".$getcid[0]['cid']."', 'final_judgements/".$final_image."')";
                        $obj->insert($insertdocsql);
                      }else{
                        echo "<script>alert('Something Went Wrong! please try again.');</script>";
                      }
                    }
                    $updatestatussql = "UPDATE case_details SET status = '".$status."', favour = '".$favour."', decision = '".$decision."' WHERE cnr_number = '".$gettedcnr."' ";
                    $obj->edit($updatestatussql);
                    echo "<script>alert('Case Status Updated Successfully. (CNR:".$cnr.")');</script>";
                    header("Location:../case_details/update_case_status.php?cnr=".urlencode(base64_encode($cnr)).""); die;
                  }else{
                    echo "<script>alert('Something Went Wrong! please try again.');</script>";
                  }
                }else{
                  echo "<script>alert('All Fields are required to fill. please try again.');</script>";
                }
              }else{
                echo "<script>alert('Something Went Wrong! please try again.');</script>";
              }
            }else{
              echo "<script>alert('Something Went Wrong! please try again.');</script>";
            }
          }else{
            echo "<script>alert('Something Went Wrong! please try again.');</script>";
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
  <title>Admin | Update Case Status</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp;Update Case Status</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Update Case Status</li>
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
                      <form method="post" action="" id="statusForm" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $gettedcnr; ?>" id="cnr" name="cnr">
                        <div class="form-group">
                          <label for="status">Select Case Status:</label>
                          <select class="form-control select2" style="width: 100%;" name="status" id="status" required>
<?php if($data[0]['status'] == 'In Progress'){ ?>
                            <option value="0">Select Status</option>
                            <option value="1" selected="selected">In Progress</option>
                            <option value="2">Closed</option>
<?php }else if($data[0]['status'] == 'Closed'){ ?>
                            <option value="0">Select Status</option>
                            <option value="1">In Progress</option>
                            <option value="2" selected="selected">Closed</option>
<?php }else{ ?>
                            <option value="0" selected="selected">Select Status</option>
                            <option value="1">In Progress</option>
                            <option value="2">Closed</option>
<?php } ?>    
                          </select>
                        </div>

                        <div id="closedForm">

                        </div>

                        <br>
                        <div class="form-group">
                          <input type="submit" class="btn btn-info font-weight-bold btn-flat" name="update_status" id="update_status" value="Update Case Status">
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
    $(document).ready(function () {
        $('.select2').select2();
        checkStatus($("#status").find("option:selected").attr('value')); 
    });

    $(document).on("change","#judgement",function(e) {
      // var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html("Document Selected");
    });

    $(document).on("submit","#statusForm",function(e) {
        if (confirm("Are You Sure to change the status of case?") == true) {
            return true;
        } else {
            return false;
        }
    });

    $(document).on("click","#removeDoc",function(e) {
        if (confirm("Are You Sure to delete the judgement document?") == true) {
            return true;
        } else {
            return false;
        }
    });

    function checkStatus(val){
        var cnr = $('#cnr').val();
        $.ajax({
          type: 'post',
          url: 'ajaxcheckStatus.php',
          data: {
              status : val,
              cnr : cnr
          },
          success: function(data){
              const obj = JSON.parse(data);
              if (obj.status == 'false'){
                    alert(obj.msg);
              }else{            
                  $("#closedForm").html(obj.data);
                  $('.select2').select2();
              }
          }
      });
    }

    $(document).on('change','#status',function(){
        checkStatus($(this).find("option:selected").attr('value')); 
    });
  
</script>
</body>
</html>
