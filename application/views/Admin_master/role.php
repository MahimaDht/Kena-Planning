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

					<h1 class="h3 mb-3">Role</h1>

					<div class="row">
                    <div class="col-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0"> <?php if(isset($editdata)) { ?>
                            <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Edit Role</h5>
                        <?php } else { ?>
                            <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Add Role</h5>
                        <?php } ?></h5>
								</div>
								<div class="card-body">
                                <form class="row" role="form" action="<?php echo base_url('Admin_master/save_role') ?>" method="post"><input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                            <input type="hidden" name="id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->id; ?>" <?php } ?>>
                            <div class="form-group col-md-12">
                                <label>Role name*</label>
                                <input type="text" name="rolename" class="form-control" minlength="3" placeholder="Enter Role Name" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->rolename; ?>" <?php } ?> autocomplete="off" onblur="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="form-group col-md-12 mb-0 mt-2">
                                <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save <?php if(isset($editdata)) { echo "Changes"; } ?></button>
                                <?php if(isset($editdata)) { ?>
                                    <a href="<?php echo base_url(); ?>Admin_master/role"><input type="button" class="btn btn-secondary ml-2" value="Back"></a>
                                <?php } else { ?>
                                <?php } ?>
                            </div>
                        </form>

								</div>
							</div>
						</div>

                        <div class="col-6">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Role</h5>
								</div>
								<div class="card-body">
                                <table class="table table-bordered" id="datatable_index">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Role Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Role Name</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                            <?php
                                                $x=1; 
                                                foreach($roledata as $value)
                                                { 
                                            ?>
                                                    <tr>
                                                        <td><?php echo $x; ?></td>
                                                        <td><?php echo $value->rolename; ?></td>
                                                        <td class="text-center">
<?php if ($value->id != 3 && $value->id != 1) { ?>
                                                            <a href="<?php echo base_url().'Admin_master/edit_role/'.base64_encode($value->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

                                                            <!-- <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Admin_master/remove_role/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> -->
                                                        <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $x=$x+1;
                                                }
                                            ?>
                                </tbody>
                            </table>

								</div>
							</div>
						</div>
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>