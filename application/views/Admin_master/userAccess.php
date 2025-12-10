<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/sidebar'); ?>

    <div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card card-outline-info">
                    <div class="card-header border-bottom">
                        <h4 class="m-b-0 "><i class="fa fa-plus" aria-hidden="true"> </i> Set User Permission</h4>                    
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-12">
                                <label>User*</label>
                                <select style="width:100%" name="user_id" id="user_id" class="form-control" required>
                                    <option value="" disabled selected>Select</option>
                                <?php foreach($userdata as $value) { ?>
                                    <option value="<?php echo $value->user_id; ?>"><?php echo $value->first_name.' '.$value->last_name; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12 mb-0 text-right">
                                <button type="button" id="BtnSubmit" class="btn btn-info"><i class="fa fa-check"></i> Fetch Permission</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php $this->load->view('include/footer'); ?>

<script type="text/javascript">

$(document).ready(function() 
{
    $('#link-Admin_master').addClass('show');
    $("#link-Admin_master").addClass('active');
    $("#li-userAccess").addClass('active');
});

</script>

<script>
    $("#BtnSubmit").on("click", function(event)
    {
        event.preventDefault();
        var user_id = $('#user_id').val();

        if (user_id!='')
        {
            var encodedStr = btoa(user_id);
            window.location.href='<?php echo base_url()."Admin_master/fetch_userAccess/"; ?>'+encodedStr; 
        }
        else 
        {
            alert("Please Select A User !");
        }   
    });
</script>