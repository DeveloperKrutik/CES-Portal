<?php
  $template = 'login';
  include_once('../../../config/common.php');
  if (isset($_POST['login'])) {
	
    $auname = mysqli_real_escape_string($obj->CONN, $_POST['auname']);
    $aupassword = md5($_POST['aupassword']);
    
      $select = "SELECT * FROM admin_user WHERE ((BINARY auname = '" . $auname . "') OR (BINARY auemail = '" . $auname . "')) AND BINARY aupassword = '".$aupassword."' ";
      $res = $obj->select($select);

    if (count($res) > 0) {
      if($res[0]['disflag'] != '0'){
        $_SESSION['msg'] = "<script type='text/javascript'>alert('Oops! You are still not Activated!');</script>"; 
        
      }else{
        date_default_timezone_set('Asia/Kolkata');
        $ip = $_SERVER['REMOTE_ADDR'];
        $dt = date("d-m-y");
        $dy = date("l");
        $tm = date("h:i:s A");

        $logsql = "INSERT INTO 
                    logs(ip_address, date, day, time) 
                    VALUE ('".$ip."', '".$dt."', '".$dy."', '".$tm."') ";
        $obj->insert($logsql);

        session_regenerate_id(true);
        $_SESSION['admin']['auname'] = $res[0]['auname'];
        $_SESSION['admin']['auemail'] = $res[0]['auemail'];
        $_SESSION['admin']['auid'] = $res[0]['auid'];
        header("Location:../dashboard/");
      }
      
    } else {
          $_SESSION['msg'] = "<script type='text/javascript'>alert('Oops! Invalid Username & Password!');</script>";
      }
  } else {
      @session_start();
      @session_destroy();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>College Project | Log in</title>
  <?php include_once('../../inc/css.php'); ?>

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="./">
      <h2><b>CES</b> Admin</h2>
    </a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
    <?php 
      if (isset($_SESSION['msg'])) echo $_SESSION['msg']; 
      $_SESSION['msg'] = NULL;
    ?>
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="username" name ="auname" class="form-control" placeholder="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-at"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name ="aupassword" class="form-control" placeholder="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
            <p class="mb-1">
              <a href="#">Forgot Password ?</a>
            </p>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name = "login" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<?php include_once('../../inc/js.php'); ?>
</body>

<!-- <script>
  function deactive(){
    alert('ok');
  }
</script> -->
</html>
