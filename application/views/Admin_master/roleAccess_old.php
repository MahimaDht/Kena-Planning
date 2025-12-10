<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/profile_header'); ?>


    <div class="page-body">
        <div class="container-fluid">        
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>User Roles</h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo base_url(); ?>">                                       
                                    <svg class="stroke-icon">
                                        <use href="<?php echo base_url(); ?>assets/svg/icon-sprite.svg#stroke-home"> Dashboard</use>
                                    </svg>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">User Roles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                       Set User Role Permission
                        </div>

                        <div class="card-body">
                        <form class="row" role="form" action="<?php echo base_url('Admin_master/fetch_userAccess') ?>" method="get" enctype="multipart/form-data">
                        <div class="form-group col-md-12">
                                <label>User Role*</label>
                                <select style="width:100%" name="role_id" id="user_id" class="form-control" required>
                                    <option value="" >Select</option>
                                <?php foreach($roledata as $value) { ?>
                                    <option value="<?php echo base64_encode($value->id); ?>"><?php echo $value->rolename; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12 mt-3 text-right">
                                <button type="submit" id="BtnSubmit2" class="btn btn-info"><i class="fa fa-check"></i> Fetch Permission</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>
</div>

<?php $this->load->view('partials/footer'); ?>
<script>
    $("#BtnSubmit").on("click", function(event)
    {
        event.preventDefault();
        var user_id = $('#user_id').val();

        if (user_id!='')
        {
            var encodedStr = btoa(user_id);
            window.location.href='<?php echo base_url()."Admin_master/fetch_userAccess?role_id="; ?>'+encodedStr; 
        }
        else 
        {
            alert("Please Select A User !");
        }   
    });
</script>