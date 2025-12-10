<?php  
$title_name = '';
if(isset($title)) {$title_name= $title; } else {$title_name =  'Integra || SCM'; } ?>
<!DOCTYPE html>
<html>

<head>
    <script type="text/javascript">
    if (screen.width >=699){window.location="<?php echo base_url('');?>";}
    </script>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title><?=$title_name;?></title>
    <!-- Favicon-->
    <link rel="icon" href="<?php echo base_url(); ?>assets/login/css/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="<?php echo base_url(); ?>assets/login/css/material_icon.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/login/css/font_rob.css" rel="stylesheet">

    <!-- Bootstrap Core Css -->
    <link href="<?php echo base_url(); ?>assets/login/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="<?php echo base_url(); ?>assets/login/plugins/node-waves/waves.css" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="<?php echo base_url(); ?>assets/login/plugins/animate-css/animate.css" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="<?php echo base_url(); ?>assets/login/css/style.css" rel="stylesheet">
</head>

<body class="login-page">
<div class="login-box">
    <div class="logo">
        <a href="javascript:void(0);"><b><?=$title_name;?></b></a>
    </div>
    <div class="card">
        <div class="body">
            <form action = "" method = "post" autocomplete="off">
                <div class="msg">Please Sign in to start your Session</div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">person</i>
                        </span>
                    <div class="form-line">
                        <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons">lock</i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-8 p-t-5">
                        <input type="checkbox" name="rememberme" id="rememberme" class="filled-in chk-col-pink">
                        <label for="rememberme">Remember Me</label>
                    </div>
                    <div class="col-xs-4">
                        <button class="btn btn-block bg-pink waves-effect" type="submit">SIGN IN</button>
                    </div>
                </div>
                <div class="row" style="text-align: center; color: red;">
                    <?php echo $_SESSION['msg']; ?>
                </div>
            </form>
             <!--  <br>  
              <div class="col-xs-12">
                        <button  onclick="window.location.href='faculty/'" style="letter-spacing: 0.5px;" class="btn btn-block bg-green waves-effect" type="submit">Staff Registration</button>
              </div> -->

              <br><br>
        </div>
    </div>
</div>

<!-- Jquery Core Js -->
<script src="<?php echo base_url(); ?>assets/login/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap Core Js -->
<script src="<?php echo base_url(); ?>assets/login/plugins/bootstrap/js/bootstrap.js"></script>

<!-- Waves Effect Plugin Js -->
<script src="<?php echo base_url(); ?>assets/login/plugins/node-waves/waves.js"></script>

<!-- Validation Plugin Js -->
<script src="<?php echo base_url(); ?>assets/login/plugins/jquery-validation/jquery.validate.js"></script>

<!-- Custom Js -->
<script src="<?php echo base_url(); ?>assets/login/js/admin.js"></script>
<script src="<?php echo base_url(); ?>assets/login/js/pages/examples/sign-in.js"></script>
</body>

</html>