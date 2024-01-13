<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CES | Login</title>
    <link rel="stylesheet" href="loginAsset/css/cloudflare.css">
    <link rel="stylesheet" href="loginAsset/css/bootstrap.css">
    <link rel="stylesheet" href="loginAsset/css/style.css">
</head>
<body>
    <div class="registration-form">
        <form id="mailForm">
            <div class="form-icon">
                <span><img class="img-fluid img-profile rounded-circle mx-auto mb-5" src="assets/img/logo.png" alt="logo" /></span>
            </div>
            <div class="form-group text-center">
                <h3 class="cusfont">CES Portal</h3>
            </div>
            <div class="form-group">
                <input type="email" class="form-control item" name="email" id="email" placeholder="Enter Email Address">
                <small id="emailHelp" class="form-text text-muted">An OTP will be sent to your email address for verification</small>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block create-account" id="getOTP">Get OTP <i class="fa fa-arrow-right" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>
    <script src="loginAsset/js/jquery.js"></script>
    <script src="loginAsset/js/cloudflare.js"></script>
    <script src="loginAsset/js/script.js"></script>
    <script src="loginAsset/js/fontawesome.js"></script>
    
    <script>

        function generateOTP(email){
            $.ajax({
                type: 'post',
                url: 'ajaxgetOTP.php',
                data: {
                    email : email
                },
                success: function(data){
                    const obj = JSON.parse(data);
                    if (obj.status == 'false'){
                        alert(obj.msg);
                    }else{
                        $(".registration-form").html(obj.data);
                    }
                }
            });
        }

        function signInOTP(email, otp){
            $.ajax({
                type: 'post',
                url: 'ajaxsignin.php',
                data: {
                    email : email,
                    otp : otp
                },
                success: function(data){
                    const obj = JSON.parse(data);
                    if (obj.status == 'false'){
                        alert(obj.msg);
                    }else{
                        window.location.replace(window.location.href+"home");
                    }
                }
            });
        }

        $(document).on('click','#getOTP',function(){
            var email = $("#email").val();
            generateOTP(email);
        });
        
        $(document).on('submit','#mailForm',function(e){
            e.preventDefault();
            var email = $("#email").val();
            generateOTP(email);
        });
        
        $(document).on('submit','#otpForm',function(e){
            e.preventDefault();
            var email = $("#email").val();
            var otp = $("#otp").val();
            signInOTP(email, otp);
        });
        
        $(document).on('click','#resendOTP',function(e){
            e.preventDefault();
            var email = $("#email").val();
            if (confirm("Do you want to get new OTP on '"+email+"'") == true) {
                $.ajax({
                    type: 'post',
                    url: 'ajaxresendOTP.php',
                    data: {
                        email : email
                    },
                    success: function(data){
                        const obj = JSON.parse(data);
                        if (obj.status == 'false'){
                            alert(obj.msg);
                        }else{
                            alert(obj.msg);
                            generateOTP(email);
                        }
                    }
                });
            } else {
                location.reload(true);
            }
        });

        $(document).on('click','#signin',function(){
            var email = $("#email").val();
            var otp = $("#otp").val();
            signInOTP(email, otp);
        });
        
    </script>
</body>
</html>
