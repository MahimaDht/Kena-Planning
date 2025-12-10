<?php $this->load->view('inc/header'); ?>

    <div class="az-content-body">
        <div class="row">
            <div class="col-12 mb-2">        
                <a href="<?php echo base_url().'Admin_master/users'; ?>"><button type="button" class="btn btn-info"><i class="fa fa-list"> </i> User List</button></a>          
            </div>

        <?php if(isset($editdata)) { ?>

            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-header border-bottom">
                        <h4 class="m-b-0 "><i class="fa fa-plus" aria-hidden="true"> </i> Edit User</h4>                    
                    </div>
                    <div class="card-body">
                        <form class="row" role="form" action="<?php echo base_url('Admin_master/update_users') ?>" method="post">
                            <input type="hidden" name="user_id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->user_id; ?>" <?php } ?>>

                            <div class="form-group col-md-4">
                                <label>First name*</label>
                                <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->first_name; ?>" <?php } ?> autocomplete="off" onblur="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->last_name; ?>" <?php } ?> autocomplete="off" onblur="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->email; ?>" <?php } ?> autocomplete="off">
                            </div>
                            <div class="form-group col-md-2">
                                <label>Contact</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter Contact" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->phone; ?>" <?php } ?> autocomplete="off">
                            </div>

<?php

$roles=$this->db->query("SELECT a.role_id FROM user_role as a WHERE a.user_id='$editdata->user_id' ")->result();
$role_array=array();
foreach ($roles as $value) 
{
    array_push($role_array, $value->role_id);
}

?>

                            <div class="form-group col-md-3">
                                <label>Role*</label>
                                <select style="width:100%" name="role[]" multiple class="form-control select2" required>
                                <?php foreach($roledata as $value) { ?>
                                    <option value="<?php echo $value->id; ?>" <?php if(in_array($value->id, $role_array)) { echo "selected"; } ?>><?php echo $value->rolename; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Username*</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->username; ?>" <?php } ?> autocomplete="off" required>
                            </div>

                            <div class="form-group col-md-2">
                                <label>Image</label>
                                <input type="file" name="user_image" id="user_image" class="form-control">
                            </div>

                            <div>
                            <?php if(!empty($editdata->user_image)){ ?>
                                <label>Image</label><br>
                                <img style="width:200px" src="<?php echo base_url().'assets/images/users/'.$editdata->user_image; ?>">
                            <?php } ?>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save Changes</button>
                                <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </form>

                        <hr>

                        <form class="row" role="form" action="<?php echo base_url('Admin_master/update_password') ?>" method="post">
                            <input type="hidden" name="user_id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->user_id; ?>" <?php } ?>>

                            <div class="form-group col-md-6">
                                <label>Password*</label>
                                <input type="text" name="password" id="password" class="form-control" placeholder="Enter Password" autocomplete="off" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Confirm Password*</label>
                                <input type="text" name="confirm" id="confirm" class="form-control" placeholder="Enter Confirm Password" autocomplete="off" required>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" id="btnSubmit" class="btn btn-info"><i class="fa fa-check"></i> Update Password</button>
                                <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        <?php } else { ?>

            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-header border-bottom">
                        <h4 class="m-b-0 "><i class="fa fa-plus" aria-hidden="true"> </i> Add User</h4>                    
                    </div>
                    <div class="card-body">
                        <form class="row" role="form" action="<?php echo base_url('Admin_master/save_users') ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group col-md-4">
                                <label>First name*</label>
                                <input type="text" name="first_name" class="form-control" placeholder="Enter First Name" autocomplete="off" onblur="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Enter Last Name" autocomplete="off" onblur="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter Email" autocomplete="off">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact</label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter Contact" autocomplete="off">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Role*</label>
                                <select style="width:100%" name="role[]" multiple class="form-control select2" required>
                                <?php foreach($roledata as $value) { ?>
                                    <option value="<?php echo $value->id; ?>"><?php echo $value->rolename; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Image</label>
                                <input type="file" name="user_image" id="user_image" class="form-control">
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <hr>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Username*</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter Username" autocomplete="off" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Password*</label>
                                <input type="text" name="password" id="password" class="form-control" placeholder="Enter Password" autocomplete="off" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Confirm Password*</label>
                                <input type="text" name="confirm" id="confirm" class="form-control" placeholder="Enter Confirm Password" autocomplete="off" required>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" id="btnSubmit" class="btn btn-info"><i class="fa fa-check"></i> Save User</button>
                                <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php } ?>

        </div>
    </div>

<?php $this->load->view('inc/footer'); ?>

<script type="text/javascript">

$(document).ready(function() 
{
    $('#link-Admin_master').addClass('show');
    $("#link-Admin_master").addClass('active');
    $("#li-users").addClass('active');
});

</script>

<script>
    $(function () {
        $("#btnSubmit").click(function () {
            var password = $("#password").val();
            var confirmPassword = $("#confirm").val();
            if (password != confirmPassword) {
                alert("Passwords does not match,Please Enter a Valid Password.");
                return false;
            }
        });
    });
</script>