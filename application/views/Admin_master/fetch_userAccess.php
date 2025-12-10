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

					<h1 class="h3 mb-3">Role List</h1>

					<div class="row">
                    <div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">
                                        Set User Role Permission                    
                                    </h5>
								</div>
								<div class="card-body">
                                    
                            <form class="row" role="form" action="<?php echo base_url('Admin_master/fetch_userAccess') ?>" method="get" enctype="multipart/form-data">
                     
<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
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
                         <select class="form-control select2 " name="parent_id" multiple >
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
                                <form class="row" role="form" action="<?php echo base_url('Admin_master/set_userAccess') ?>" method="post" enctype="multipart/form-data">

<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
<input type="hidden" name="role_id" value="<?php echo $roledata->id; ?>">
<input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>">
<div class="col-md-12 mb-2">
    <hr>
    <h4><u>Permissions</u></h4>
    <table  class="table table-bordered ">
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
    <!-- <button type="reset" class="btn btn-secondary ml-2">Reset</button> -->
</div>
</form>

								</div>
							</div>
						</div>

                     
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>
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
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>