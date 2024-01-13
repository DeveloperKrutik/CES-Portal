<?php
    $template = 'new_case';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    if (isset($_POST['add_case'])){
      if(isset($_POST['cnr']) AND isset($_POST['ctype']) AND isset($_POST['pname']) AND isset($_POST['dept']) AND isset($_POST['desc']) AND isset($_POST['flno']) AND isset($_POST['regno']) AND isset($_POST['fldate']) AND isset($_POST['regdate']) AND isset($_POST['email'])){

        if(!empty($_POST['cnr']) AND !empty($_POST['ctype']) AND !empty($_POST['pname']) AND ($_POST['dept'] != 0) AND !empty($_POST['desc']) AND !empty($_POST['flno']) AND !empty($_POST['regno']) AND !empty($_POST['fldate']) AND !empty($_POST['regdate']) AND !empty($_POST['email'])){

          $email = mysqli_real_escape_string($obj->CONN, trim($_POST['email']));
          $emaildatasql = "SELECT name, phone FROM permissions WHERE email = '".$email."' ";
          $emaildata = $obj->select($emaildatasql);
          
          if (count($emaildata) == 0){
            if(isset($_POST['name']) AND isset($_POST['phone']) AND !empty($_POST['phone']) AND !empty($_POST['name'])){
              $name = mysqli_real_escape_string($obj->CONN, trim($_POST['name']));
              $phone = mysqli_real_escape_string($obj->CONN, trim($_POST['phone']));
            }else{
              echo "<script>alert('All Fields are required!');</script>";
              header("Location:../new_case"); die;
            }
          }else{
            $name = $emaildata[0]['name'];
            $phone = $emaildata[0]['phone'];
          }

          $cnr = mysqli_real_escape_string($obj->CONN, trim($_POST['cnr']));
          $desc = mysqli_real_escape_string($obj->CONN, trim($_POST['desc']));
          $flno = mysqli_real_escape_string($obj->CONN, trim($_POST['flno']));
          $regno = mysqli_real_escape_string($obj->CONN, trim($_POST['regno']));
          $fldate = mysqli_real_escape_string($obj->CONN, trim($_POST['fldate']));
          $regdate = mysqli_real_escape_string($obj->CONN, trim($_POST['regdate']));
          $ctype = mysqli_real_escape_string($obj->CONN, trim($_POST['ctype']));
          $pname = mysqli_real_escape_string($obj->CONN, trim($_POST['pname']));
          $dept = mysqli_real_escape_string($obj->CONN, trim($_POST['dept']));
          
          if (strlen($_POST['cnr']) != 16){
            echo "<script>alert('CNR Number should contain 16 digits. please try again.');</script>";
          }else{
            $validationsql = "SELECT cid FROM case_details WHERE cnr_number = '".$cnr."' ";
            $validationcheck = $obj->select($validationsql);

            if (count($validationcheck) > 0){
              echo "<script>alert('entered CNR(".$cnr.") Already exists. please try again with new cnr number.');</script>";
            }else{

              $casesql = "INSERT INTO case_details(cnr_number, description, case_type, department, opposition_party_name, filing_number, reg_number, filing_date, reg_date, status) VALUES ('".$cnr."', '".$desc."', '".$ctype."', '".$dept."', '".$pname."', '".$flno."', '".$regno."', '".$fldate."', '".$regdate."', 'In Progress');";

              $addcase = $obj->insert($casesql);

              $n = 1;
              while(1){
                $actvar = 'act'.$n;
                $articlevar = 'article'.$n;
                if(isset($_POST[$actvar])){
                  if(!empty($_POST[$actvar])){
                    $actsql = "INSERT INTO acts(cnr_number, act, articles) VALUES ('".$cnr."', '".$_POST[$actvar]."', '".$_POST[$articlevar]."')";
                    $obj->insert($actsql);
                  }
                }else{
                  break;
                }
                $n=$n+1;
              }

              if ($addcase > 0) {
                $pldrsql = "INSERT INTO 
                            permissions(case_id, role, name, email, phone) 
                            VALUES (
                                (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."'), 'Government Pleader', '".$name."', '".$email."', '".$phone."'
                            )";
                $pleaderdetails = $obj->insert($pldrsql);
                
                $assignedpldrsql = "INSERT INTO 
                                    assigned_pleaders(case_id, name, email, phone) 
                                    VALUES (
                                        (SELECT cid FROM case_details WHERE cnr_number = '".$cnr."'), '".$name."', '".$email."', '".$phone."'
                                    )";
                $assignedpldr = $obj->insert($assignedpldrsql);
                
                echo "<script>alert('Case Added Successfully. (CNR:".$cnr.")');</script>";
                header("Location:../permissions/accesspermissions.php?cnr=".$cnr.""); die;
              }
            }
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
  <title>Admin | Add New Case</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-plus"></i> &nbsp;&nbsp;Add New Case</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Add New Case</li>
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
              <!-- <div class="card-header">
              </div> -->

                <div class="card-body p-0">
                    <div class="card-body">
                      <form method="post" action="" id="caseForm">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="cnr">CNR Number: (16 Digit)</label>
                              <input type="text" name="cnr" class="form-control" id="cnr" placeholder="Enter CNR Number" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="ctype">Case Type:</label>
                              <input type="text" name="ctype" class="form-control" id="ctype" placeholder="Enter Case Type" required>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="pname">Opposition Party Name:</label>
                              <input type="text" name="pname" class="form-control" id="pname" placeholder="Enter Name" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
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
                          <!-- /.col -->
                        </div>

                        <div class="form-group">
                            <label for="desc">Case Description:</label>
                            <textarea class="form-control" rows="3" spellcheck="false" name="desc" id="desc" placeholder="Enter Case Description" required></textarea>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="flno">Filing Number:</label>
                              <input type="text" name="flno" class="form-control" id="flno" placeholder="Enter Filing Number" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="regno">Registration Number:</label>&nbsp;&nbsp;&nbsp;<span id="regnomsg" class=""></span>
                              <input type="text" name="regno" class="form-control" id="regno" placeholder="Enter Registration Number" required>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="fldate">Filing Date:</label>&nbsp;&nbsp;&nbsp;<span id="fldatemsg" class=""></span>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" required name="fldate" class="form-control" id="fldate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="regdate">Registration Date:</label>&nbsp;&nbsp;&nbsp;<span id="regdatemsg" class=""></span>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" required name="regdate" class="form-control" id="regdate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <hr>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="act1">Act:</label>&nbsp;&nbsp;<span style="color:red;">(optional)</span>
                              <input type="text" name="act1" class="form-control" id="act1" placeholder="Enter Act">
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="article1">Article(s) with Section(s):</label>&nbsp;&nbsp;<span style="color:red;">(optional)</span>&nbsp;&nbsp;<i class="nav-icon fas fa-info-circle" title="Saperate multiple values with comma ( , )"></i>
                              <input type="text" name="article1" class="form-control" id="article1" placeholder="Enter Articles">
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>

                        <div id="moreactfields">
            
                        </div>

                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <a href="#" class="btn btn-info btn-flat" id="moreacts">Add more acts &nbsp;&nbsp; <i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        <hr>

                        <br>
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                          <table class="table table-hover table-bordered table-head-fixed text-nowrap">
                              <tr>
                                <th colspan = "2" style="text-align:center">Enter Contact Information about Assigned Pleader to this Case.</th>
                              </tr>
                              <tr>
                                  <th style="text-align:center">Email ID:</th>
                                  <td>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" required>
                                    </div>
                                  </td>
                              </tr>
                              <tr>
                                  <th style="text-align:center">Name:</th>
                                  <td>
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                                    </div>
                                  </td>
                              </tr>
                              <tr>
                                  <th style="text-align:center">Mobile No.:</th>
                                  <td>
                                    <div class="form-group">
                                        <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter Mobile Number" required>
                                    </div>
                                  </td>
                              </tr>
                          </table>
                        </div>
                        <br>
                        <div class="form-group">
                          <input type="submit" class="btn btn-success btn-flat font-weight-bold" name="add_case" value="Create New Case">
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

  function checkMailData(email){
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
  }

  $(document).ready(function () {
    $('.select2').select2();
  });

  $('#nexthear, #firsthear, #fldate, #regdate, #hear').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
  
  $(document).on('keyup','#email',function(){
      var email = $("#email").val();
      checkMailData(email);
  });

  $(document).on('click','#moreacts',function(e){
      event.preventDefault();
      var n = 1;
      while (true) {
        if($("#act" + n).length == 0) {
          var content = "<div class='row'><div class='col-md-6'><div class='form-group'><label for='act"+n+"'>Act:</label>&nbsp;&nbsp;<span style='color:red;'>(optional)</span><input type='text' name='act"+n+"' class='form-control' id='act"+n+"' placeholder='Enter Act'></div></div><div class='col-md-6'><div class='form-group'><label for='article"+n+"'>Article(s) with Section(s):</label>&nbsp;&nbsp;<span style='color:red;'>(optional)</span>&nbsp;&nbsp;<i class='nav-icon fas fa-info-circle' title='Saperate multiple values with comma ( , )'></i><input type='text' name='article"+n+"' class='form-control' id='article"+n+"' placeholder='Enter Articles'></div></div></div>";
            
          $("#moreactfields").append(content);
          break;
        }
        n = n + 1;
      }
  });
</script>
</body>
</html>
