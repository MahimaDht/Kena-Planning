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
									<h5 class="card-title mb-0">
                                        Set User Role Permission                    
                                    </h5>
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
			</main>
<?php 
            $this->load->view('include/footer');
?>