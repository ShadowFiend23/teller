<?php 



?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Tellering</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!--<link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">-->

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="css/paper-dashboard.css" rel="stylesheet">
    <link href="css/themify-icons.css" rel="stylesheet">
    <style>
        #changePass{
            position: absolute;
            left: 50%;
            top: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width:500px;
            height: 430px;
            background-color: #17a2b8;
            z-index:1000;
            display:none;
        }
        h4,h6{
            color:white;
            text-align:center;
        }
        h6{
            font-size: 11px;
        }
        .form-container{
            margin:15px;
            border: 2px solid white;
            padding:30px;
        }
        .form-container label{
            color:white;
        }
        #newPassSpin{
            display:none;
        }
   </style>
</head>

<body class="bg-gradient-primary">

    <div class="container">


        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" id="loginForm">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                placeholder="Enter Employee ID" name="employeeID">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" placeholder="Password" name="password">
                                        </div>
                                        <!--<div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember
                                                    Me</label>
                                            </div>
                                        </div>-->
                                        <button type="submit" class="btn btn-primary btn-user btn-block" id="lgnBtn" data-loading-text="Loading..." >Login</button>
                                        <hr>
                                        <a href="register.php" class="btn btn-google btn-user btn-block" style='visibility: hidden;'>
                                            <i class="fab fa-google fa-fw"></i> Register
                                        </a>
                                        <a href="index.html" class="btn btn-facebook btn-user btn-block" style='visibility: hidden;'>
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    <div class="" id="changePass">
        <div class="container-fluid">
            <div class="form-container">
                <form class="form-group" id="newPassForm">
                    <h4 class="text-align-center">New Password</h4>
                    <h6 class="text-align-center">Need To Register New Password</h6>
                    <hr>
                    <div class="form-group">
                        <label for="newPass">New Password</label>
                        <input type="password" class="form-control" name="newPass" id="newPass">
                    </div>
                    <div class="form-group">
                        <label for="reType">Re-type Password</label>
                        <input type="password" class="form-control" name="reType" id="reType">
                        <span id='message'></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-secondary" type="button" id="reTypeCancel">Cancel</button>
                        <button class="btn btn-primary float-right" type="submit" id="submitNewPass" disabled>Submit &nbsp;<div class="spinner-border spinner-border-sm text-light" id="newPassSpin" role="status"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script src="js/sweetalert2.js"></script>

    <script type="text/javascript">
        
    $(function(){
        let userID;
        $("#loginForm").on("submit",function(e){
            e.preventDefault();
            let frmData = new FormData($(this)[0])

            $("#lgnBtn").html("\
                    <div class='text-center'>\
                      Logging In.. \
                      <div class='spinner-border ml-auto spinner-border-sm' role='status' aria-hidden='true'></div>\
                    </div>\
                ");
            
            $.ajax({
                url:"ajax/login.php",
                type:"POST",
                data:frmData,
                success:function(data){
                    let response = $.parseJSON(data)

                    if(response["result"]){
                        if(response["changePass"]){
                            userID = response["userID"];
                            let timerInterval
                            Swal.fire({
                                title: 'Success',
                                html: '<b>Default Password has been found. Need to Change Password.<b>',
                                timer: 2000,
                                type:"success",
                                timerProgressBar: true,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                    const b = Swal.getHtmlContainer().querySelector('b')
                                    timerInterval = setInterval(() => {
                                    b.textContent = Swal.getTimerLeft()
                                    }, 100)
                                },
                                willClose: () => {
                                    clearInterval(timerInterval)
                                }
                                }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    $("#changePass").show();
                                }
                            })
                        }else{
                            let type = response["type"];
                            if(type == "bAdmin"){
                                Swal.fire({
                                    title: 'Success',
                                    html: 'Welcome Administrator. Redirecting to Page.',
                                    timer: 3000,
                                    type:"success",
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                }).then(function() {
                                    window.location.href = "./index.php";
                                });
                                
                            }else{
                                Swal.fire({
                                    title: 'Success',
                                    html: 'Welcome. Redirecting to Page.',
                                    timer: 3000,
                                    type:"success",
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                }).then(function() {
                                    window.location.href = "./user.php";
                                });
                                
                            }
                        } 
                    }else{
                        Swal.fire({
                            title: 'Error',
                            html: response['msg'],
                            type:"error",
                            timerProgressBar: true,
                            showConfirmButton: true,
                            allowOutsideClick: false
                        })
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            })


        })

        $(document).on("submit","#newPassForm",function(e){

            e.preventDefault();
            $("#newPassSpin").css("display", "inline-block");

            let frmData = new FormData($(this)[0]);
            frmData.append("userID",userID);
            $.ajax({
                url:"ajax/saveNewPass.php",
                type:"POST",
                data: frmData,
                dataType: 'json',
                success:function(data){
                let response = data;

                if(response["result"]){
                    Swal.fire({
                        title: 'Success',
                        html: 'Welcome. Redirecting to Page.',
                        timer: 3000,
                        type:"success",
                        timerProgressBar: true,
                        showConfirmButton: false,
                        allowOutsideClick: false
                    }).then(function() {
                        if(response["type"] == "bAdmin"){
                            window.location.href = response["link"];
                        }else{
                            window.location.href = response["link"];
                        }
                    });
                        
                }else{
                    Swal.fire(
                        'Failed',
                        response["msg"],
                        'error'
                    )
                }
                    
                },
                cache: false,
                contentType: false,
                processData: false
            })
        })

        $(document).ready(function () {
            $("#newPass, #reType").keyup(checkPasswordMatch);
        });

        function checkPasswordMatch() {
            var password = $("#newPass").val();
            var confirmPassword = $("#reType").val();

            if (password !== confirmPassword){ 
                $('#message').html('Not Match').css('color', 'red');
                $("#submitNewPass").prop("disabled", "true");
            }else if(password === "" && confirmPassword === ""){
                $('#message').html('Please Input Passwords').css('color', 'red');
                $("#submitNewPass").prop("disabled", "true");
            }else{
                $('#message').html('Matched').css('color', 'green');
                $("#submitNewPass").removeAttr("disabled");
            }
        }

        $(document).on("click","#reTypeCancel",function(){
            $('#newPassForm').trigger("reset");
            $("#changePass").hide();
        })

    })

    </script>
</body>

</html>