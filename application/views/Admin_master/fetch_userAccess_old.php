<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/sidebar'); ?>

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
                            <h5 class="mb-0"><i class="fa fa-list" aria-hidden="true"> </i> Role List</h5>
                        </div>

                        <div class="card-body">

                            <form class="row" role="form" action="<?php echo base_url('Admin_master/fetch_userAccess') ?>" method="get" enctype="multipart/form-data">
                     
                            <div class="form-group col-md-4">
                                <label>User Role*</label>

                                <select style="width:100%" name="role_id" id="user_id" class="form-control" required>
                                    <option value="" >Select</option>
                                <?php foreach($roleList as $value) { ?>
                                    <option value="<?php echo base64_encode($value->id); ?>" <?php if($roledata->id==$value->id){echo 'selected';} ?> ><?php echo $value->rolename; ?></option>
                                <?php } ?>
                                </select>

                            </div>

                            <div class="form-group col-md-4">
                                <label>Parent Name</label>
                                <select class="form-control select2" name="parent_id"  >
                                    <option value="">All</option>
                                    <?php foreach ($parentMenuList as $key => $value): ?>
                                    <option value="<?= base64_encode($value->id) ?>" <?php if ($value->id==$parent_id): ?>
                                        selected
                                    <?php endif ?> ><?= $value->menu_name ?></option>    
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2 ">
                                <button type="submit" class="btn btn-info btn-sm mt-4"> Fetch</button>
                            </div>

                            </form>



<!-- MAIN USER ACCESS -->
                            <form class="row" role="form" action="<?php echo base_url('Admin_master/set_userAccess') ?>" method="post" enctype="multipart/form-data">

                            <input type="hidden" name="role_id" value="<?php echo $roledata->id; ?>">
                            <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
                            <div class="col-md-12 mb-2">
                                <hr>
                                <h4><u>Permissions</u></h4>
                                <table  class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Parent Name</th>
                                            <th>Menu</th>
                                            <th>Read <input type='checkbox' id='read_selectall' /></th>
                                            <th>Write <input type='checkbox' id='write_selectall' /></th>
                                            <th>Update <input type='checkbox' id='update_selectall' /></th>
                                            <th>Delete <input type='checkbox' id='delete_selectall' /></th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                    $x=0;
                                        foreach($menudata as $value){

                                            $permissiondata=$this->db->query("SELECT * FROM uac_access_controls WHERE role_id='$roledata->id' AND menu_id='$value->id'")->row();
                                    ?>
                                        <tr>
                                            <td><?php echo $value->parent_name; ?></td>
                                            <td><?php echo $value->menu_name; ?></td>
                                            <td>
                                                <input type="hidden" name="menu_id[]" value="<?php echo $value->id; ?>">

                                                <input type="checkbox" class='readClass' name="readp_<?php echo $value->id; ?>" id="readp_<?php echo $x; ?>" value="readPermission" <?php if(!empty($permissiondata) && $permissiondata->readp==1) { echo "checked"; } ?>>
                                            </td>
                                            <td>
                                                <input type="checkbox" class='writeClass' name="writep_<?php echo $value->id; ?>" id="writep_<?php echo $x; ?>" value="writePermission" <?php if(!empty($permissiondata) && $permissiondata->writep==1) { echo "checked"; } ?>>
                                            </td>
                                            <td>
                                                <input type="checkbox" class='updateClass' name="updatep_<?php echo $value->id; ?>" id="updatep_<?php echo $x; ?>" value="updatePermission" <?php if(!empty($permissiondata) && $permissiondata->updatep==1) { echo "checked"; } ?>>
                                            </td>
                                            <td>
                                                <input type="checkbox" class='deleteClass' name="deletep_<?php echo $value->id; ?>" id="deletep_<?php echo $x; ?>" value="deletePermission" <?php if(!empty($permissiondata) && $permissiondata->deletep==1) { echo "checked"; } ?>>
                                            </td>
                                            <td>
                                                <input type="time" name="start_time_<?php echo $value->id; ?>" class="form-control" value="<?php if(!empty($permissiondata)){ echo $permissiondata->start_time; } ?>" >
                                            </td>

                                            <td>
                                                <input type="time" name="end_time_<?php echo $value->id; ?>" class="form-control" value="<?php if(!empty($permissiondata)){ echo $permissiondata->end_time; } ?>" >
                                            </td>

                                        </tr>
                                    <?php $x++; } ?>
                                    </tbody>
                                </table>

                            </div>

                            <div class="col-md-12 mb-2">
                                <hr>
                            </div>

                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" id="btnSubmit" class="btn btn-info"><i class="fa fa-check"></i> Set Permission</button>
                                <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                            </div>
                        </form>


<!-- CUSTOM USER ACCESS -->
<hr>

<?php $permissions = explode(',',$roledata->custom_permissions); ?>
<form role="form" action="<?php echo base_url('Admin_master/update_customUserPermission') ?>" method="post">
<input type="hidden" name="role_id" value="<?php echo $roledata->id; ?>">
<div class="row">
    
    <div class="form-group col-md-12">
        <h5><u>Custom Permissions</u></h5>
    </div>

    <div class="form-group col-md-12"><b>Dashboards</b></div>
    <div class="form-group col-md-2">
         <label class="ckbox">
            <input type="checkbox" name="permissions[]" id="permissions" value="salesDashboard" <?php if($permissions && in_array('salesDashboard', $permissions)) { echo "checked"; } ?> >
            <span>Sales Dashboard</span>
          </label>
    </div>

    <div class="form-group col-md-2">
         <label class="ckbox">
            <input type="checkbox" name="permissions[]" id="permissions" value="purchaseDashboard" <?php if($permissions && in_array('purchaseDashboard', $permissions)) { echo "checked"; } ?> >
            <span>Purchase Dashboard</span>
          </label>
    </div>

    <div class="form-group col-md-2">
         <label class="ckbox">
            <input type="checkbox" name="permissions[]" id="permissions" value="storeDashboard" <?php if($permissions && in_array('storeDashboard', $permissions)) { echo "checked"; } ?> >
            <span>Store Dashboard</span>
          </label>
    </div>
    <div class="form-group col-md-2" hidden>
         <label class="ckbox">
            <input type="checkbox" name="permissions[]" id="permissions" value="prodCordinatorDashboard" <?php if($permissions && in_array('prodCordinatorDashboard', $permissions)) { echo "checked"; } ?> >
            <span>Prod Cord. Dashboard</span>
          </label>
    </div>
    <hr>
    <div class="form-group col-md-2">
         <label class="ckbox">
            <input type="checkbox" name="permissions[]" id="permissions" value="changeFinYear" <?php if($permissions && in_array('changeFinYear', $permissions)) { echo "checked"; } ?> >
            <span>Change Fin year</span>
          </label>
    </div>
    <div class="col-md-2 mt-2">
        <button type="submit" onclick="return confirm('Are you sure to update data')" class="btn btn-info btn-sm" >Update Data</button>
    </div>

</div>

</form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
</div>
<?php $this->load->view('include/footer'); ?>

<script type="text/javascript">
$("#read_selectall").click(function() {
$('.readClass').attr('checked', this.checked);
});

$("#write_selectall").click(function() {
$('.writeClass').attr('checked', this.checked);
});

$("#update_selectall").click(function() {
$('.updateClass').attr('checked', this.checked);
});

$("#delete_selectall").click(function() {
$('.deleteClass').attr('checked', this.checked);
});


</script>