<?php 
            $this->load->view('include/header');
?>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">       
<!-- nav_sidebar -->
<?php 
    $this->load->view('include/nav_sidebar');
?>
<div class="main">      
<!-- nav_header -->
<?php 
            $this->load->view('include/nav_header');
?>
<main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"><?=$title;?></h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0"><?=$title;?></h5>
                                        
                                    </div>
                                </div>
                                <div class="card-body">
                                <?php
if($this->session->flashdata('error')) {
    echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';
}
?>

                                <form class="needs-validation" method="post" action="<?php echo base_url('employee/Save') ?>" enctype="multipart/form-data" ><input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <input type="text" hidden name="update_id" class="form-control" value="<?php echo $basic->em_id; ?>"  minlength="2" autocomplete="off" required > 
                                                        <label class="form-label">First name*</label>
                                                        <input type="text" name="first_name" class="form-control" value="<?php echo $basic->first_name; ?>" placeholder="Your first name" minlength="2" autocomplete="off" required > 
                                                      
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Last name*</label>
                                                        <input type="text" id="last_name" name="last_name" class="form-control form-control-line" value="<?php echo $basic->last_name; ?>" placeholder="Your last name" autocomplete="off"  required> 
                                                       
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Username*</label>
                                                        <input type="text" name="em_username" placeholder="Enter Username" id="em_username" value="<?php echo $basic->em_username; ?>" class="form-control form-control-line" autocomplete="off" minlength="3" required="" readonly>
                                                     
                                                    </div>
                                                </div><!-- end col -->
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Contact Number</label>
                                                    <input type="tel" name="contact" class="form-control" value="<?php echo $basic->em_phone; ?>" placeholder="Enter Contact Number">
                                                     
                                                    </div>
                                                </div><!-- end col -->



                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Email Address*</label>
                                                        <input type="email" id="email" name="email" value="<?php echo $basic->em_email; ?>" class="form-control" placeholder="Ex- name@gmail.com" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company*</label>
                                                        <select class="form-control" name="company_id" id="company_id" required>
                                                           <option value="comp001" selected>Kena</option>
                                                        </select>
                                                    </div>
                                                </div>
                        <!--  <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Unit*</label>
                                    <?php
                                    $selected_plant_ids = !empty($basic->plant_id) ? array_map('trim', explode(',', $basic->plant_id)) : [];

                                    ?>
                                    <select class="form-control choices-multiple" name="plant_id[]" id="plant_id" required multiple>
                                        <?php if (!empty($plant_list)) : ?>
                                            <?php foreach ($plant_list as $value) : ?>
                                                <option value="<?php echo htmlspecialchars($value->code); ?>"
                                                    <?php echo in_array("'" . trim($value->code) . "'", $selected_plant_ids) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($value->name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div> -->


                                                
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Role*</label>
                                                        <select class="form-control" name="role_id" id="role_id" required>
                                                            <?php foreach ($roledata as $key => $value) : ?>
                                                                <option  value="<?php echo $value->id ?>" <?php if($value->id==$basic->role_id) { echo 'selected'; }?>><?php echo $value->rolename ?></option>
                                                            <?php endforeach ?>
                                                        </select>
                                                    </div>
                                                </div>




                                                <div class="col-md-3" >
                                                    <div class="mb-3">
                                                        <label class="form-label">User type</label>
                                                      <?php $selectedTypes = explode(',', $basic->user_type); ?>
                                                        <select class="form-control select2" name="user_type[]" id="user_type" multiple>
                                                            <option disabled value="">Select</option>
                                                            <option value="Operator" <?php echo in_array('Operator', $selectedTypes) ? 'selected' : ''; ?>>Operator</option>
                                                            <option value="Helper" <?php echo in_array('Helper', $selectedTypes) ? 'selected' : ''; ?>>Helper</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div hidden class="col-md-3">
                                                    <label class="form-label">Reporting Person</label>
                                                    <select class="form-control" name="manager_id" id="manager_id">
                                                        <option selected disabled value="">-Select Reporting Manager-</option>
                                                        <?php if (!empty($gen_managers) && is_array($gen_managers)) : ?>
                                                            <?php foreach ($gen_managers as $value) : ?>
                                                                <option value="<?php echo htmlspecialchars($value->em_id, ENT_QUOTES, 'UTF-8'); ?>">
                                                                    <?php echo htmlspecialchars($value->first_name . ' ' . $value->last_name, ENT_QUOTES, 'UTF-8'); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Status*</label>
                                                        <select class="form-control" name="status" id="status" required>
                                                                <option value="<?php echo $basic->status; ?>"><?php echo $basic->status; ?></option>
                                                        <option value="ACTIVE">ACTIVE</option>
                                                        <option value="INACTIVE">INACTIVE</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group"></div>


                                                <div class="col-md-12" hidden>
                                                    <div class="mb-3">
                                                        <input type="checkbox" class="form-check-input" name="is_report_auth" value="Y" id="id_reporting_auth"
                                                        <?php echo (isset($basic->is_report_auth) && $basic->is_report_auth == 'Y') ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="id_reporting_auth">Is Reporting Auth</label>

                                                    </div>
                                                </div>

                                            </div><!-- end row -->
                                           
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </form><!-- end form -->
<hr>
                                         <?php 
                                         if($this->session->userdata('user_type')=='SUPER ADMIN'){ ?>
                                        
                                         <div class="card-header d-flex align-items-center">
                                            <h5 class="card-title">Update Password</h5>
                                    </div>
                            <form class="card-body row" action="<?= base_url('employee/Reset_Password_Hr') ?>" method="post" enctype="multipart/form-data"><input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="text" class="form-control" name="new1" value="" required minlength="6">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <input type="text" id="" name="new2" class="form-control " required minlength="6">
                                </div>
                                    <div class="form-actions col-md-12">
                                        <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
                                        <button type="submit" class="btn btn-primary pull-right mt-4"> Save</button>
                                    </div>
                            </form>

                                <?php }else{ ?>

                                <div class="card border shadow-none">
                                                <div class="card-header d-flex align-items-center">
                                                  
                                                    <<!-- div class="flex-grow-1">
                                                        <h5 class="card-title">Update Password</h5>
                                                    </div> -->
                                                </div>
                                                <div class="card-body">
                                                  
                                           <!--     <form class="needs-validation" method="post" action="<?php echo base_url('employee/Reset_Password') ?>" enctype="multipart/form-data" novalidate>   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="mb-lg-0">
                                                            <label for="current-password-input" class="form-label">Current password</label>                                                                     
                                                            <input type="password" class="form-control" name="old" value="" placeholder="Current password" required minlength="6">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-lg-0">
                                                            <label for="new-password-input" class="form-label">New password</label>

                                                            <input type="password" class="form-control" name="new1" value="" required minlength="6">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-4">
                                                        <div class="mb-lg-0">
                                                            <label for="confirm-password-input" class="form-label">Confirm password</label>

                                                            <input type="password" id="" name="new2" class="form-control " required minlength="6">
                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="text-end">
                                                <input type="text" hidden name="emid" value="<?php echo $basic->em_id; ?>">
                                                <button type="submit" class="btn btn-primary btn-sm mt-4">Update Password</button>
                                            </div>

                                        </form> -->
                        </div>
                                       
                                        </div>

                                <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
<?php 
            $this->load->view('include/footer');
?>
<script>
     $(document).ready(function(){

        $('.select2').select2();
    });
    $(function() {
        $("#btnSubmit").click(function() {
            var em_usernameflag = $("#em_usernameflag").val();
            if (em_usernameflag == 'FALSE') {
                alert("Username Already Exist");
                return false;
            }


            var password = $("#txtPassword").val();
            var confirmPassword = $("#txtConfirmPassword").val();
            if (password != confirmPassword) {
                alert("Passwords does not match,Please Enter a Valid Password.");
                return false;
            }

            return confirm('Are You Sure To Submit Data');
        });
    });
</script>
<script>
    function checkusername() {
        var base_url = "<?php echo base_url(); ?>";
        var em_username = $("#em_username").val();
        $.ajax({
            url: base_url + 'employee/checkusernameexist',
            type: 'post',
            data: {
                em_username: em_username
            },
            success: function(response) {
                var response = response.trim();
                if (response == "YES") {
                    $("#em_usernamespan").html('Username Already Exist');
                    $("#em_usernamespan").css('color', 'red');
                    $("#em_usernameflag").val('FALSE');
                } else {
                    $("#em_usernamespan").html('Username Available');
                    $("#em_usernamespan").css('color', 'green');
                    $("#em_usernameflag").val('TRUE');
                }
            }
        });
    }
</script>

<script>
document.getElementById('sol_one_user_code').addEventListener('change', function () {
    const selectedOption = this.options[this.selectedIndex];
    const email = selectedOption.getAttribute('data-email');
    document.getElementById('email').value = email ? email : '';
});
</script>
