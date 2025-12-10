<!doctype html>
<html> 
<head> 
    <meta charset="utf-8" />
    <title> DHT Solutions  </title>
    <link href="<?php echo base_url(); ?>assets/login/css/login.css" rel="stylesheet"/> 
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/ui/img/icons/favicon.ico" />
   <!--  <script type="text/javascript">
    if (screen.width <=699){window.location="index_2.html";}
    </script> -->
  
<style type="text/css">
    
form h5 {
    box-sizing: border-box;
    padding: 20px;
}
h5 {
    height: 100px;
    width: 100%;
    background: #18aa8d;
    letter-spacing: 1px;
    font-size: 28px;
    color: white;
    line-height: 150%;
    border-radius: 3px 3px 0 0;
    box-shadow: 0 2px 5px 1px rgba(0, 0, 0, 0.2);
}
</style>

</head> 
<body>
    <div id="level">
        <div id="content">
            <div id="gears">
                <div id="gears-static"></div>
                <div id="gear-system-1">
                    <div class="shadow" id="shadow15"></div>
                    <div id="gear15"></div>
                    <div class="shadow" id="shadow14"></div>
                    <div id="gear14"></div>
                    <div class="shadow" id="shadow13"></div>
                    <div id="gear13"></div>
                </div>
                <div id="gear-system-2">
                    <div class="shadow" id="shadow10"></div>
                    <div id="gear10"></div>
                    <div class="shadow" id="shadow3"></div>
                    <div id="gear3"></div>
                </div>
                <div id="gear-system-3">
                    <div class="shadow" id="shadow9"></div>
                    <div id="gear9"></div>
                    <div class="shadow" id="shadow7"></div>
                    <div id="gear7"></div>
                </div>
                <div id="gear-system-4">
                    <div class="shadow" id="shadow6"></div>
                    <div id="gear6"></div>
                    <div id="gear4"></div>
                </div>
                <div id="gear-system-5">
                    <div class="shadow" id="shadow12"></div>
                    <div id="gear12"></div>
                    <div class="shadow" id="shadow11"></div>
                    <div id="gear11"></div>
                    <div class="shadow" id="shadow8"></div>
                    <div id="gear8"></div>
                </div>
                <div class="shadow" id="shadow1"></div>
                <div id="gear1"></div>
                <div id="gear-system-6">
                    <div class="shadow" id="shadow5"></div>
                    <div id="gear5"></div>
                    <div id="gear2"></div>
                </div>
                <div class="shadow" id="shadowweight"></div>
                <div id="chain-circle"></div>
                <div id="chain"></div>
                <div id="weight"></div>
   
            </div>
            <div id="title">
  <p  style="    font-family: ice;
    color: white;
    padding-top: 10px;
    font-size: 25px;">Your Reliable Partner in developing and Manufacturing Sustainable Mobility Solutions</p>
 <form action = "login/Login_Auth" method = "post" autocomplete="off">
  <h5 style="font-family: ice;">Kena</h5>
   <?php if(!empty($this->session->flashdata('feedback'))){ ?>
              <div style="color: red" class="message">
              <strong>OOPS! </strong><?php echo $this->session->flashdata('feedback')?>
              </div>
              <?php
              }
              ?>
              <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

  <input placeholder="Username" type="text" name="username" value="<?php if(isset($_COOKIE['email'])) { echo $_COOKIE['email']; } ?>" type="text" required="">
  <input placeholder="Password" name="password"  value="<?php if(isset($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>"  type="password" required="">
  <button type="submit">Login</button>
  <div style="text-align: center; color: red; padding-top: 20px;">
                </div>
<br>

</form> 
 <div style="text-align:  right; font-family: 'Trebuchet MS', Helvetica, sans-serif  ;  color: #fff; font-size: 14px;  line-height: 20px;  padding-top: 20px; letter-spacing: 1px;">
                   </div>

            </div>
        </div>
    </div>
</body>
</html>