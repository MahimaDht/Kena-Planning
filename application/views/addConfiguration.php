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

					<h1 class="h3 mb-3">Configuration</h1>

					<div class="row">
                    <div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">
                                    Configuration
                                    </h5>
								</div>
								<div class="card-body">
                                    
                                <form class="row" method="post" action="<?php echo base_url() ?>Configuration/saveConfiguration" enctype="multipart/form-data">
    <div class="p-3">
        <h5 class="text-info">ASP</h5>
<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
        <div class="mb-3 row">
            <label for="einv_aspid" class="col-sm-3 col-form-label text-end">ASP ID</label>
            <div class="col-sm-9">
                <input type="hidden" id="comp_id" value="<?php echo $comp_id;?>" name="comp_id">
                <input type="password" hidden name="smtp_password" id="smtp_password" value="<?php echo $configuration->smtp_password;?>" class="form-control">
                <input type="text" id="einv_aspid" value="<?php echo $configuration->einv_aspid;?>" name="einv_aspid" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="einv_password" class="col-sm-3 col-form-label text-end">ASP Password</label>
            <div class="col-sm-9">
                <input type="password" id="einv_password" value="<?php echo $configuration->einv_password;?>" name="einv_password" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="env_user_name" class="col-sm-3 col-form-label text-end">Portal Client User Name</label>
            <div class="col-sm-9">
                <input type="text" id="env_user_name" value="<?php echo $configuration->env_user_name;?>" name="env_user_name" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="einv_eInvPwd" class="col-sm-3 col-form-label text-end">Portal Client Password</label>
            <div class="col-sm-9">
                <input type="password" id="einv_eInvPwd" value="<?php echo $configuration->einv_eInvPwd;?>" name="einv_eInvPwd" class="form-control">
            </div>
        </div>

        <hr>

        <h5 class="text-info">SMTP Configuration</h5>

        <div class="mb-3 row">
            <label for="smtp_host" class="col-sm-3 col-form-label text-end">Host</label>
            <div class="col-sm-9">
                <input type="text" name="smtp_host" id="smtp_host" value="<?php echo $configuration->smtp_host;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="smtp_port" class="col-sm-3 col-form-label text-end">Port</label>
            <div class="col-sm-9">
                <input type="text" name="smtp_port" id="smtp_port" value="<?php echo $configuration->smtp_port;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="smtp_sender_id" class="col-sm-3 col-form-label text-end">Sender Mail Id</label>
            <div class="col-sm-9">
                <input type="text" name="smtp_sender_id" id="smtp_sender_id" value="<?php echo $configuration->smtp_sender_id;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="smtp_password" class="col-sm-3 col-form-label text-end">Password</label>
            <div class="col-sm-9">
            <input type="password" name="smtp_password" value="<?php echo $configuration->smtp_password;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="smtp_secure" class="col-sm-3 col-form-label text-end">Secure</label>
            <div class="col-sm-9">
                <input type="text" name="smtp_secure" id="smtp_secure" value="<?php echo $configuration->smtp_secure;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <label for="smtp_name" class="col-sm-3 col-form-label text-end">Name</label>
            <div class="col-sm-9">
                <input type="text" name="smtp_name" id="smtp_name" value="<?php echo $configuration->smtp_name;?>" class="form-control">
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-12 text-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
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