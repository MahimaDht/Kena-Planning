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

					<h1 class="h3 mb-3"><?=$title;?>
                     <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#ImportModal" style="margin-left: 10px;">Import</button>
                     
                          <a href="<?php echo base_url('uploads/documents/KenaUser.csv'); ?>"  class="btn btn-primary float-end "><i class="fa fa-download"></i>Template </a>               
                    </h1>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex justify-content-between align-items-center">
										<h5 class="card-title mb-0"><?=$title;?></h5>
										<?php if($urldata->writep==1) {  ?>
											<a href="<?php echo base_url('employee/Add_employee'); ?>" class="btn btn-light">
											<i class="uil uil-plus me-1"></i> Add New</a>
										<?php } ?>
									</div>
								</div>
								<div class="card-body">

                                <table class="table table-bordered" id="datatable_index">
                                            <thead class="bg-light">
                                                <tr>
                                                   
                                                 <th>Id</th>
                                                 <th>Username</th>
                                                <th>Name</th>
                                                <th>Email </th>
                                                <th>Contact No. </th>
                                                <th>User Role</th>
                                                    <th class="rounded-end">Action</th>
                                                </tr>
                                                <!-- end tr -->
                                            </thead>
                                            <!-- end thead -->
                                            <tbody>
                                                 <?php foreach($employee as $value): ?>
                                            <tr>
                                                <td><?php echo $value->em_id; ?></td>
                                                <td><?php echo $value->em_username; ?></td>
                                                <td title="<?php echo $value->first_name .' '.$value->last_name; ?>"><?php echo $value->first_name .' '.$value->last_name; ?></td>       
                                                <td><?php echo $value->em_email; ?></td>
                                                <td><?php echo $value->em_phone; ?></td>
                                                <td><?php echo $value->rolename; ?></td>
                                                <td class="jsgrid-align-center ">
                                                    <a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($value->em_id); ?>" title="Edit" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                               

                                            </tbody><!-- end tbody -->
                                        </table><!-- end table -->
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>

<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?php echo base_url('Employee/importUserExcel'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    
                    <input type="file" name="excel_file" accept=".csv" required class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>