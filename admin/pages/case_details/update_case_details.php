<?php
    $template = 'update_case_details';
    include_once('../../../config/common.php');

    if (!isset($_SESSION['admin'])){
        header("Location:../../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cnr']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $validateCNR = "SELECT cnr_number, case_type, department, opposition_party_name, description, filing_number, filing_date, reg_number, reg_date FROM case_details WHERE cnr_number = '".$gettedcnr."' ";
    $data = $obj->select($validateCNR);

    $actssql = "SELECT act, articles FROM acts WHERE cnr_number = '".$data[0]['cnr_number']."' ";
    $acts = $obj->select($actssql);

    if (count($data) == 0){
        header("Location:../../"); die;
    }

    if (isset($_POST['update_case'])){
      if(isset($_POST['cnr']) AND isset($_POST['ctype']) AND isset($_POST['pname']) AND isset($_POST['dept']) AND isset($_POST['desc']) AND isset($_POST['flno']) AND isset($_POST['regno']) AND isset($_POST['fldate']) AND isset($_POST['regdate'])){

        if(!empty($_POST['cnr']) AND !empty($_POST['ctype']) AND !empty($_POST['pname']) AND ($_POST['dept'] != 0) AND !empty($_POST['desc']) AND !empty($_POST['flno']) AND !empty($_POST['regno']) AND !empty($_POST['fldate']) AND !empty($_POST['regdate'])){

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

              if (count($validationcheck) > 1){
                echo "<script>alert('entered CNR(".$cnr.") Already exists. please try again with new cnr number.');</script>";
              }else{
                $casesql = "UPDATE case_details SET cnr_number = '".$cnr."', description = '".$desc."', department = '".$dept."', case_type = '".$ctype."', opposition_party_name = '".$pname."', filing_number = '".$flno."', filing_date = '".$fldate."', reg_number = '".$regno."', reg_date = '".$regdate."' WHERE cnr_number = '".$gettedcnr."' ";
                $obj->edit($casesql);
  
                $deleteacts = "DELETE FROM acts WHERE cnr_number = '".$gettedcnr."' ";
                $obj->delete($deleteacts);

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
              }
              
              echo "<script>alert('Case Updated Successfully. (CNR:".$cnr.")');</script>";
              header("Location:../case_details/edit_case.php?cnr=".urlencode(base64_encode($cnr)).""); die;
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
  <title>Admin | Update Case Details</title>
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
            <h1 class="m-0"><i class="nav-icon fas fa-edit"></i> &nbsp;&nbsp;Update Case Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="../dashboard">Home</a></li>
              <li class="breadcrumb-item active font-weight-bold">Update Case Details</li>
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
                      <form method="post" action="">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                                <label for="cnr">CNR Number: (16 Digit)&nbsp;<span style="font-size: 15px; color:red;">*</span></label><span id="cnrmsg" class=""></span>
                                <input type="text" name="cnr" class="form-control" id="cnr" placeholder="Enter CNR Number" value="<?php echo $data[0]['cnr_number']; ?>" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="ctype">Case Type:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>
                              <input type="text" name="ctype" class="form-control" id="ctype" placeholder="Enter Case Type" value="<?php echo $data[0]['case_type']; ?>" required>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="pname">Opposition Party Name:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>
                              <input type="text" name="pname" class="form-control" id="pname" placeholder="Enter Name" value="<?php echo $data[0]['opposition_party_name']; ?>" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="dept">Select Government Department:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>
                              <select class="form-control select2" style="width: 100%;" name="dept" id="dept">
<?php
  if($data[0]['department'] == 0){
?>
                                <option selected="selected" value="0">Select Department</option>
<?php }else{ ?>
                                <option value="0">Select Department</option>
<?php } ?>    

<?php
  $deptsql = "SELECT did, dept_name FROM govdept";
  $dept = $obj->select($deptsql);
  for ($i=0; $i < count($dept); $i++){
    if($data[0]['department'] == $dept[$i]['did']){
?>
                                <option selected="selected" value="<?php echo $dept[$i]['did']; ?>"><?php echo $dept[$i]['dept_name']; ?></option>
<?php }else{ ?>
                                <option value="<?php echo $dept[$i]['did']; ?>"><?php echo $dept[$i]['dept_name']; ?></option>
<?php } } ?>
                              </select>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>

                        <div class="form-group">
                            <label for="desc">Case Description:</label>&nbsp;&nbsp;&nbsp;<span id="descmsg" class=""></span>
                            <textarea class="form-control" rows="3" spellcheck="false" name="desc" id="desc" placeholder="Enter Case Description"><?php echo $data[0]['description']; ?></textarea>
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="flno">Filing Number:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>
                              <input type="text" name="flno" class="form-control" id="flno" placeholder="Enter Filing Number" value="<?php echo $data[0]['filing_number']; ?>" required>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="regno">Registration Number:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>&nbsp;&nbsp;&nbsp;<span id="regnomsg" class=""></span>
                              <input type="text" name="regno" class="form-control" id="regno" placeholder="Enter Registration Number" value="<?php echo $data[0]['reg_number']; ?>" required>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="fldate">Filing Date:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>&nbsp;&nbsp;&nbsp;<span id="fldatemsg" class=""></span>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" required name="fldate" class="form-control" id="fldate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask value="<?php echo $data[0]['filing_date']; ?>">
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="regdate">Registration Date:&nbsp;<span style="font-size: 15px; color:red;">*</span></label>&nbsp;&nbsp;&nbsp;<span id="regdatemsg" class=""></span>
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" required name="regdate" class="form-control" id="regdate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask value="<?php echo $data[0]['reg_date']; ?>">
                              </div>
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
                        
                        <hr>
<?php
if(count($acts) != 0){
  for ($i = 0; $i < count($acts); $i++){
?>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="act<?php echo $i+1; ?>">Act:</label>&nbsp;&nbsp;<span style="color:red;">(optional)</span>
                              <input type="text" name="act<?php echo $i+1; ?>" class="form-control" id="act<?php echo $i+1; ?>" placeholder="Enter Act" value="<?php echo $acts[$i]['act'] ?>">
                            </div>
                          </div>
                          <!-- /.col -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label for="article<?php echo $i+1; ?>">Article(s) with Section(s):</label>&nbsp;&nbsp;<span style="color:red;">(optional)</span>&nbsp;&nbsp;<i class="nav-icon fas fa-info-circle" title="Saperate multiple values with comma ( , )"></i>
                              <input type="text" name="article<?php echo $i+1; ?>" class="form-control" id="article<?php echo $i+1; ?>" placeholder="Enter Articles" value="<?php echo $acts[$i]['articles'] ?>">
                            </div>
                          </div>
                          <!-- /.col -->
                        </div>
<?php
  }
}else{
?>
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
<?php
}
?>

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
                        <div class="form-group">
                          <input type="submit" class="btn btn-info font-weight-bold btn-flat" name="update_case" value="Update Case Details">
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

  $('#nexthear, #firsthear, #fldate, #regdate, #hear').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
  
</script>
</body>
</html>
