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

                            <form class="needs-validation" method="post" action="<?php echo base_url('employee/Save') ?>" enctype="multipart/form-data" >
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                <div class="row">

                                 <!--    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Solution One User Code*</label>
                                            <select id="sol_one_user_code" name="sol_one_user_code" class="form-select" required>
                                                <option value="">-- Select User Code --</option>

                                            </select>

                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">First name*</label>
                                            <input type="text" name="first_name" class="form-control" placeholder="Your first name" minlength="2" autocomplete="off" required > 

                                        </div>
                                    </div><!-- end col -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Last name*</label>
                                            <input type="text" id="last_name" name="last_name" class="form-control form-control-line" value="" placeholder="Your last name" autocomplete="off"  required> 

                                        </div>
                                    </div><!-- end col -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Username*</label>
                                            <input type="text" name="em_username" placeholder="Enter Username" id="em_username" onblur="checkusername()" class="form-control form-control-line" autocomplete="off" minlength="3" required="">
                                            <span id="em_usernamespan"></span>
                                            <input type="hidden" id="em_usernameflag" name="">

                                        </div>
                                    </div><!-- end col -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Number</label>
                                            <input type="tel" name="contact" class="form-control" value="" placeholder="Enter Contact Number">

                                        </div>
                                    </div><!-- end col -->


                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Email Address*</label>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Ex- name@gmail.com" required>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Company*</label>
                                            <select class="form-control" name="company_id" id="company_id" required>
                                              
                                                <option value="comp001">Kena</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Unit*</label>
                                            <select class="form-control choices-multiple" name="plant_id[]"  required multiple>
                                                <optgroup label="Select Unit">
                                                    <?php foreach ($plant_list as $key => $value) : ?>
                                                        <option value="<?php echo $value->code ?>"><?php echo $value->name ?></option>
                                                    <?php endforeach ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div> -->

                                    <div class="col-md-3" >
                                        <div class="mb-3">
                                            <label class="form-label">Role*</label>
                                            <select class="form-control" name="role_id" id="role_id" required>
                                                <option selected disabled value="">Select Role</option>
                                                <?php foreach ($roledata as $key => $value) : ?>
                                                    <option value="<?php echo $value->id ?>"><?php echo $value->rolename ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3" >
                                        <div class="mb-3">
                                            <label class="form-label">User type</label>
                                            <select class="form-control select2" name="user_type[]" id="user_type" multiple>
                                                <option selected disabled value="">Select</option>
                                                <option value="Operator">Operator</option>
                                                <option value="Helper">Helper</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3" hidden>
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

                                <div class="col-md-12 form-group"><hr></div>


                                <div class="col-md-12" hidden>
                                    <div class="mb-3">
                                        <input type="checkbox" class="form-check-input"  name="is_report_auth" value="Y" id="id_reporting_auth">
                                        <label class="form-check-label" for="id_reporting_auth">Is Reporting Auth</label>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Password*</label>
                                        <input id="txtPassword" type="password" name="password" class="form-control passwordInput" value="" placeholder="**********" autocomplete="off" required>
                                        <span id="StrengthDisp" class="badge displayBadge text-light">Weak</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password*</label>
                                        <input id="txtConfirmPassword" type="password" name="confirm" class="form-control" value="" placeholder="**********" autocomplete="off" required>
                                    </div>
                                </div>




                            </div><!-- end row -->

                            <button class="btn btn-primary" type="submit">Save</button>
                        </form><!-- end form -->

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
        const username = selectedOption.getAttribute('data-username');
        document.getElementById('email').value = email ? email : '';
        document.getElementById('em_username').value = username ? username : '';
    });
</script>
