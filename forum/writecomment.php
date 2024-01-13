<?php
    include_once('../config/common.php');
    $template = "forum";

    if (!isset($_SESSION['user'])){
        header("Location:../"); die;
    }

    $personsql = "SELECT case_id, role, email FROM permissions WHERE email = '".$_SESSION['user']['email']."' and disflag = '0' ";
    $persondata = $obj->select($personsql);

    if (count($persondata) == 0){
        header("Location:../"); die;
    }

    // EMAIL INTEGRATION CODE
    require '../includes/PHPMailer.php';
    require '../includes/SMTP.php';
    require '../includes/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = "true";
    $mail->Username = "er.krutikpatel31@gmail.com";
    $mail->Password = "utzvxtvpgskxriwe";
    $mail->SMTPSecure = "ssl";
    $mail->Port = "465";
    $mail->setFrom("er.krutikpatel31@gmail.com");

    $cnr_encode = mysqli_real_escape_string($obj->CONN, trim($_GET['cs']));
    $gettedcnr = base64_decode(urldecode($cnr_encode));

    if (isset($_POST['postcomment'])){
        if ((!empty($_POST['comment'])) or (!empty($_FILES['forumdocs']['name'][0]))){

            if(!empty($_FILES['forumdocs']['name'][0])){
                $insertcomment = "INSERT INTO forum(cnr_number, email, comment, isdoc) VALUES ('".$gettedcnr."', '".$_SESSION['user']['email']."', '".$_POST['comment']."', '1')";
            }else{
                $insertcomment = "INSERT INTO forum(cnr_number, email, comment, isdoc) VALUES ('".$gettedcnr."', '".$_SESSION['user']['email']."', '".$_POST['comment']."', '0')";
            }
            $comment = $obj->insert($insertcomment);

            if(!empty($_FILES['forumdocs']['name'][0])){
                $countfiles = count($_FILES['forumdocs']['name']);
                for($i=0;$i<$countfiles;$i++){
                    $path = '../forum_documents/';
                    $img = $_FILES['forumdocs']['name'][$i];
                    $tmp = $_FILES['forumdocs']['tmp_name'][$i];
                    // get uploaded file's extension
                    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                    // can upload same image using rand function
                    $final_image = rand(1000,1000000).$img;
                    while(true){
                    if(file_exists('../forum_documents/'.$final_image)){
                        $final_image = rand(1000,1000000).$final_image;
                    }else{
                        break;
                    }
                    }
                    $path = $path.strtolower($final_image); 
                    if(move_uploaded_file($tmp,$path)){
                        $insertdocsql = "INSERT INTO forumdocs(fid, docpath) VALUES ('".$comment."', '".$final_image."')";
                        $obj->insert($insertdocsql);
                    }else{
                        echo "<script>alert('Something Went Wrong! please try again.');</script>";
                    }
                }
            }
            $checkpersql = "SELECT email, name FROM permissions WHERE case_id = (SELECT cid FROM case_details WHERE cnr_number = '".$gettedcnr."') AND email != '".$_SESSION['user']['email']."' ";
            $checkper = $obj->select($checkpersql);
            
            for ($i=0; $i < count($checkper); $i++){
                $mail->addAddress($checkper[$i]['email']);
                $mail->isHTML(true);
                $mail->Subject = "Comment on ".$gettedcnr." ";
                $mail->Body = '
                    Hello '.$checkper[$i]["name"].',<br><br>
                    '.$_SESSION['user']['name'].' has commented on <strong>CNR: '.$gettedcnr.'</strong> case.<br><br>
                    To check the case details through your email, use the below given link.<br>
                    <a href="#">link to CES Portal</a>.<br><br>
                    Regards,<br>
                    CES Portal<br><br>
                    <strong>Note:</strong> <span style="color: red;">Please do not reply to this mail. Email sent to this address will not be responded to.</span>
                ';
                $mail->send();
            }
            header("Location: ../forum/index.php?cs=".urlencode(base64_encode($gettedcnr))."");
        }else{
            header("Location: ../forum/writecomment.php?cs=".urlencode(base64_encode($gettedcnr))."");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Case Forum</title>
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
                <div class="resume-section-content">
                    <div class="subheading mb-5">
                    </div>
                    <div class="row">
                        <div class="col-10">
                            <h4><i class="fa fa-comment" aria-hidden="true"></i> &nbsp;&nbsp;&nbsp; Write your comment (<?php echo $gettedcnr; ?>):</h4>
                        </div>
                        <div class="col-2">
                            <a class='btn btn-sm btn-flat btn-custom font-weight-bold flex-left' href = 'index.php?cs=<?php echo urlencode(base64_encode($gettedcnr)); ?>'><i class='nav-icon fas fa-arrow-left'></i> &nbsp; Back</a>
                        </div>
                    </div>

                    <br>

                    <form method="post" action="" id="commentForm" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="comment">Comment:</label>
                            <textarea class="form-control" rows="5" id="comment" name="comment"></textarea>
                        </div>

                        <br>

                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="forumdocs" name="forumdocs[]" multiple>
                            <label class="custom-file-label font-weight-bold" for="customFile">Upload Document(s)</label>
                        </div>

                        <br>

                        <div class="form-group">
                          <input type="submit" class="btn btn-custom font-weight-bold btn-flat" name="postcomment" id="postcomment" value="Post Comment">
                        </div>

                    </form>

                </div>
            </section>
        </div>
        <!-- JS Files -->
        <?php include_once('../inc/js.php'); ?>

        <script>
           
        </script>
    </body>
</html>
