<?php 
            $this->load->view('include/header');

$id = $this->session->userdata('user_login_id');
$basicinfo = $this->employee_model->GetBasic($id);
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
						<div class="col-md-3 col-xl-2">

							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Profile Settings</h5>
								</div>

								<div class="list-group list-group-flush" role="tablist">
									<a class="list-group-item list-group-item-action <?php echo $this->input->get('type') == 'account' ? 'active' : '' ?>" data-bs-toggle="list" href="#account" role="tab">
										Account
									</a>
									<a class="list-group-item list-group-item-action <?php echo $this->input->get('type') == 'password' ? 'active' : '' ?>"   data-bs-toggle="list" href="#password" role="tab">
										Password
									</a>
									<a class="list-group-item list-group-item-action <?php echo $this->input->get('type') == 'notification' ? 'active' : '' ?>" data-bs-toggle="list" href="#" role="tab">
										Notifications
									</a>
									<a class="list-group-item list-group-item-action <?php echo $this->input->get('type') == 'document' ? 'active' : '' ?>" data-bs-toggle="list" href="#document" role="tab">
										Document
									</a>
								</div>
							</div>
						</div>

						<div class="col-md-9 col-xl-10">
							    <?php if ($this->session->flashdata('error')): ?>

	<div class="alert alert-danger alert-outline alert-dismissible" role="alert">
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			<div class="alert-icon">
				<i class="far fa-fw fa-bell"></i>
			</div>
			<div class="alert-message">
				<strong> <?php echo $this->session->flashdata('error'); ?></strong> 
			</div>
		</div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>

<div class="alert alert-success alert-outline alert-dismissible" role="alert">
	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	<div class="alert-icon">
		<i class="far fa-fw fa-bell"></i>
	</div>
	<div class="alert-message">
		<strong><?php echo $this->session->flashdata('success'); ?></strong>
	</div>
</div>
    <?php endif; ?>
							<div class="tab-content">
								<div class="tab-pane fade  <?php echo $this->input->get('type') == 'account' ? 'active show' : '' ?>" id="account" role="tabpanel">

									<div class="card">
										<div class="card-header">

											<h5 class="card-title mb-0">Private info</h5>
										</div>
										<div class="card-body">
<?php
$image_path = $basicinfo->em_image ? base_url($basicinfo->em_image) : base_url('assets/ui/img/photos/no_user.png'); 
?>
<?php echo form_open_multipart('general/update_profile'); ?>
    <input type="hidden" name="emid" value="<?php echo $this->session->userdata('user_login_id'); ?>">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label class="form-label" for="inputUsername">Username</label>
                <?php echo form_input('username', $basicinfo->em_username, 'class="form-control" id="inputUsername" placeholder="Username"'); ?>
                <small>This username will be used for login. Please remember it.</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <img alt="<?php echo $basicinfo->first_name.' '.$basicinfo->last_name; ?>" src="<?php echo $image_path; ?>" class="rounded-circle img-responsive mt-2" width="128" height="128" />
                <div class="mt-2">
                    <label for="profile_picture" class="btn btn-primary"><i class="fas fa-upload"></i> Upload</label>
                    <input type="file" name="profile_picture" id="profile_picture" style="display: none;">
                </div>
                <small>For best results, use an image at least 128px by 128px in .jpg format</small>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save changes</button>
<?php echo form_close(); ?>
										</div>
									</div>

								
								</div>
								<div class="tab-pane fade <?php echo $this->input->get('type') == 'password' ? 'active show' : '' ?>" id="password" role="tabpanel">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Password</h5>


<?php echo form_open('general/Reset_Password'); ?>
    <input type="hidden" name="emid" value="<?php echo $this->session->userdata('user_login_id'); ?>">

    <div class="mb-3">
        <label class="form-label" for="inputPasswordCurrent">Current password <span class="text-danger">*</span></label>
        <?php echo form_password('old', '', 'class="form-control" id="inputPasswordCurrent" required'); ?>
    </div>

    <div class="mb-3">
        <label class="form-label" for="inputPasswordNew">New password <span class="text-danger">*</span></label>
        <?php echo form_password('new1', '', 'class="form-control" id="inputPasswordNew" required'); ?>
    </div>

    <div class="mb-3">
        <label class="form-label" for="inputPasswordNew2">Verify password <span class="text-danger">*</span></label>
        <?php echo form_password('new2', '', 'class="form-control" id="inputPasswordNew2" required'); ?>
    </div>

    <button type="submit" class="btn btn-primary">Save changes</button>
<?php echo form_close(); ?>

										</div>
									</div>
								</div>



<div class="tab-pane fade <?php echo $this->input->get('type') == 'document' ? 'active show' : '' ?>" id="document" role="tabpanel">
									<div class="card">
										<div class="card-body">
											<h5 class="card-title">Document</h5>
<?php echo form_open_multipart('clients/update_supplier_document'); ?>
    <input type="text" hidden name="supplier_code" value="<?php echo $basicinfo->ref_id; ?>">

    <table class="table table-bordered table-sm" id="datatable">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Name</th>
                <th>Ref. Doc</th>
                <th>Status</th>
                <th>Upload Document</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $cnt = 1;
            foreach ($document_master_array as $value) {


            $this->db->where('document_id', $value->id);
            $this->db->where('supplier_code', $basicinfo->ref_id);
            $this->db->order_by('version_number', 'DESC'); // Show latest version first
            $document_versions = $this->db->get('dht_supplier_document_attachments')->result();

             ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $value->document_name; ?></td>
                    <td>
                        <?php if (!empty($value->document_attachment)) { ?>
                            <a href="<?php echo base_url($value->document_attachment); ?>" target="_blank">Download</a>
                        <?php } else { ?>
                            No file uploaded
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (!empty($document_versions)) { ?>
                            <img src="<?php echo base_url('assets/images/icons/');?>success-icon.png" alt="Uploaded" width="16" height="16"> 
                        <?php } else { ?>
                            <img src="<?php echo base_url('assets/images/icons/');?>error-icon.png" alt="Pending" width="16" height="16"> 
                        <?php } ?>
                    </td>

                    <td class="text-center">
                        <input type="file" name="document_attachment_<?php echo $value->id; ?>[]" multiple class="form-control">
                    </td>
                </tr>
            <?php $cnt++; } ?>
        </tbody>
    </table>

    <button type="submit" class="btn btn-primary">Update Document</button>
<?php echo form_close(); ?>

<br>
<br>
<table id="datatable_index" class="table table-bordered table-sm">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Name</th>
            <th>Version</th>
            <th>Uploaded Date</th>
            <th>Download</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $cnt = 1;
        foreach ($document_master_array as $value) { 
            $this->db->where('document_id', $value->id);
            $this->db->where('supplier_code', $basicinfo->ref_id);
            $this->db->order_by('version_number', 'DESC'); // Show latest version first
            $document_versions = $this->db->get('dht_supplier_document_attachments')->result();
            
            foreach ($document_versions as $version) { ?>
                <tr>
                    <td><?php echo $cnt; ?></td>
                    <td><?php echo $value->document_name; ?></td>
                    <td>V<?php echo $version->version_number; ?></td>
                    <td><?php echo date('d M Y H:i:s', strtotime($version->uploaded_at)); ?></td>
                    <td>
                        <a href="<?php echo base_url($version->attachment_path); ?>" target="_blank" class="btn btn-sm btn-info">Download</a>
                    </td>
                </tr>
            <?php $cnt++; }
        } ?>
    </tbody>
</table>

										</div>
									</div>
								</div>










							</div>
						</div>
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>