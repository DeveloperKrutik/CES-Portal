<?php
    $template = 'casedetails';
    include_once('../config/common.php');

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cs']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    $caseDataSQL = "SELECT * FROM case_details WHERE cnr_number = '".$gettedcnr."' AND disflag = '0' ";
    $caseData = $obj->select($caseDataSQL);
    if (count($caseData) == 0){
        header("Location:../../"); die;
    }

    $hearingsql = "SELECT hid, hearing_no, judge_name, hearing_date, next_hearing, purpose 
            FROM hearings
            WHERE case_id = '".$caseData[0]['cid']."' AND disflag = '0' ";
    $hearings = $obj->select($hearingsql);

    $firsthearingsql = "SELECT hearing_date FROM hearings WHERE case_id = '".$caseData[0]['cid']."' AND disflag = '0' AND hearing_no = '1' ";
    $firsthearing = $obj->select($firsthearingsql);

    $nexthearingsql = "SELECT next_hearing, judge_name, case_stage, court FROM hearings WHERE case_id = '".$caseData[0]['cid']."' AND disflag = '0' AND hearing_no = '".count($hearings)."' ";
    $nexthearing = $obj->select($nexthearingsql);

    $deptnamesql = "SELECT dept_name FROM govdept WHERE did = '".$caseData[0]['department']."' ";
    $deptname = $obj->select($deptnamesql);

    $asspldrsql = "SELECT name, email, phone FROM assigned_pleaders WHERE case_id = '".$caseData[0]['cid']."' ";
    $asspldr = $obj->select($asspldrsql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Case Details</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/logo.png" />
    <!-- CSS Files -->
    <?php include_once('../inc/css.php'); ?>
</head>
<body id="page-top">
    <!-- Navigation -->
    <?php include_once('../inc/nav.php'); ?>
    <!-- Page Content-->
    <div class="container-fluid p-0">
        <!-- About-->
        <section class="resume-section" id="about">
            <div class="resume-section-content" id="content">
                <div class="card">
                
                <div class="card-header">
                    <a class='btn btn-sm btn-flat btn-custom font-weight-bold flex-left' href = '../cases'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
                    <a class='btn btn-sm btn-flat btn-custom font-weight-bold float-right' href = '../forum/index.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>'>Go to Forum &nbsp; <i class='nav-icon fas fa-arrow-right'></i></a>
                </div>
                
                <div class="card-body" id="content">
                <div style="text-align:center;" class="h4 font-weight-bold">-: Case Details :-</div>
                <table class="table table-bordered table-hover table-sm">enddeclare
                    <tr>
                      <th colspan="1">Case Type</th>
                      <td colspan="3"><?php echo $caseData[0]['case_type']; ?></td>
                    </tr>
                    <tr>
                      <th>Filing Number</th>
                      <td><?php echo $caseData[0]['filing_number']; ?></td>
                      <th>Filing Date</th>
                      <td><?php echo $caseData[0]['filing_date']; ?></td>
                    </tr>
                    <tr>
                      <th>Registration Number</th>
                      <td><?php echo $caseData[0]['reg_number']; ?></td>
                      <th>Registration Date</th>
                      <td><?php echo $caseData[0]['reg_date']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="1">CNR Number</th>
                      <td colspan="3"><?php echo $gettedcnr; ?></td>
                    </tr>
                </table>
                <br>
                
                <div style="text-align:center;" class="h4 font-weight-bold">-: Involved Parties :-</div>
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                      <th colspan="2">Government Department</th>
                      <td colspan="2"><?php echo $deptname[0]['dept_name']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Opposition Party Name</th>
                      <td colspan="2"><?php echo $caseData[0]['opposition_party_name']; ?></td>
                    </tr>
                </table>
                <br>
                
<?php
  $actsql = "SELECT act, articles FROM acts WHERE cnr_number = '".$gettedcnr."' ";
  $actdetails = $obj->select($actsql);

  if(count($actdetails) > 0){
?>
                <div style="text-align:center;" class="h4 font-weight-bold">-: Acts :-</div>
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                      <th colspan="2">Under Act(s)</th>
                      <th colspan="2">Under Section(s)</th>
                    </tr>
<?php
  for ($i=0; $i < count($actdetails); $i++){
?>                    
                    <tr>
                      <td colspan="2"><?php echo $actdetails[$i]['act']; ?></td>
                      <td colspan="2"><?php echo $actdetails[$i]['articles']; ?></td>
                    </tr>
<?php } ?>
                </table>
                <br>
<?php } ?>
                
                <div style="text-align:center;" class="h4 font-weight-bold">-: Assigned Pleader :-</div>
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                      <th colspan="2">Name</th>
                      <td colspan="2"><?php echo $asspldr[0]['name']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Email Address</th>
                      <td colspan="2"><?php echo $asspldr[0]['email']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Mobile No.</th>
                      <td colspan="2"><?php echo $asspldr[0]['phone']; ?></td>
                    </tr>
                </table>
                <br>
              
            <?php if (count($hearings) > 0){ ?>
                
                <div style="text-align:center;" class="h4 font-weight-bold">-: Case Status :-</div>
                <table class="table table-bordered table-hover table-sm">
                    <tr>
                      <th colspan="2">Current Case Status</th>
                      <td colspan="2"><?php echo $caseData[0]['status']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">First Hearing Date</th>
                      <td colspan="2"><?php echo $firsthearing[0]['hearing_date']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Next Hearing Date</th>
                      <td colspan="2"><?php echo $nexthearing[0]['next_hearing']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Case Stage</th>
                      <td colspan="2"><?php echo $nexthearing[0]['case_stage']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Court</th>
                      <td colspan="2"><?php echo $nexthearing[0]['court']; ?></td>
                    </tr>
                    <tr>
                      <th colspan="2">Judge</th>
                      <td colspan="2"><?php echo $nexthearing[0]['judge_name']; ?></td>
                    </tr>
                </table>
                <br>
                
                <div style="text-align:center;" class="h4 font-weight-bold">-: Case History :-</div>
                <table class="table table-hover table-bordered table-sm">
                  <thead>
                      <tr>
                          <th>#hearing</th>
                          <th>Judge</th>
                          <th>Hearing Date</th>
                          <th>Next Hearing</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
              <?php for ($i = 0; $i < count($hearings); $i++) { ?>
                    <tr>
                        <td><?php echo $hearings[$i]['hearing_no'] ?></td>
                        <td><?php echo $hearings[$i]['judge_name'] ?></td>
                        <td><?php echo $hearings[$i]['hearing_date'] ?></td>
                        <td><?php echo $hearings[$i]['next_hearing'] ?></td>
                        <td><button class="btn btn-xs btn-dark btn-sm font-weight-bold" onclick="viewHearing(<?php echo $hearings[$i]['hid']; ?>);"><i class="nav-icon fas fa-eye"></i> | View</button></td>
                    </tr>
              <?php } ?>
                  </tbody>
                </table>
            <?php } ?><br>
              
              <?php if ($caseData[0]['status'] == 'Closed'){ ?>
                  
                  <div style="text-align:center;" class="h4 font-weight-bold">-: Case Decision :-</div>
                  <table class="table table-bordered table-hover table-sm">
                      <tr>
                        <th colspan="2">In whose favour the decision went:</th>
              <?php if ($caseData[0]['favour'] == 'opp'){ ?>
                        <td colspan="2">Opposition (<?php echo $caseData[0]['opposition_party_name']; ?>)</td>
              <?php }else if ($caseData[0]['favour'] == 'gov'){ ?>
                        <td colspan="2">Government (<?php echo $deptname[0]['dept_name']; ?>)</td>
              <?php } ?>
                      </tr>
                      <tr>
                        <th colspan="2">Final Decision</th>
                        <td colspan="2"><?php echo $caseData[0]['decision']; ?></td>
                      </tr>
              <?php

                $getdocsql = "SELECT docpath FROM casedocs WHERE cid = '".$caseData[0]['cid']."' ";
                $getdoc = $obj->select($getdocsql);

                if(count($getdoc) > 0){
                  for ($i=0; $i < count($getdoc); $i++){
                    $j = $i + 1;
              ?>
                      <tr>
                        <th colspan="2">Document <?php echo $j; ?></th>
                        <td colspan="2"><a href="../<?php echo $getdoc[$i]['docpath']; ?>" target="_blank" class="btn btn-dark btn-flat btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> | View</a></td>
                      </tr>
              <?php } } ?>
                  </table>
                  <br>
              <?php } ?>

              </div>
            </div>
            </div>
        </section>
    </div>
    <!-- JS Files -->
    <?php include_once('../inc/js.php'); ?>

    <script>
        function viewHearing(hid){
            $.ajax({
                type: 'post',
                url: 'ajaxviewHearing.php',
                data: {
                    hid : hid
                },
                success: function(data){
                    const obj = JSON.parse(data);
                    if (obj.status == 'false'){
                        alert(obj.msg);
                    }else{
                        $("#content").html(obj.data);
                    }
                }
            });
        }

        $(document).on('click','#backbtn',function(){
            location.reload(true);
        });
    </script>
</body>
</html>
