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
<?php 
$id = $this->session->userdata('user_login_id');
$basicinfo = $this->employee_model->GetBasic($id);

$image_path = $basicinfo->em_image ? base_url($basicinfo->em_image) : base_url('assets/ui/img/photos/no_user.png'); 
$supplierArray = $this->db->query("
    SELECT 
        OVND.*, 
        VND3.gst, 
        VND3.pan 
    FROM OVND
    LEFT JOIN VND3 ON OVND.code = VND3.code
    WHERE OVND.code = '$basicinfo->ref_id'
")->row();


?>
<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3"><?=$title;?></h1>

			
					<div class="row">
						<div class="col-md-4 col-xl-3">
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Supplier Details</h5>
        </div>
        <div class="card-body text-center">
            <img src="<?= isset($image_path) ? $image_path : 'default.jpg' ?>" 
                 alt="<?= $supplierArray->name ?>" 
                 class="img-fluid rounded-circle mb-2" 
                 width="128" height="128" />

            <h5 class="card-title mb-0"><?= $supplierArray->name ?></h5>
            <div class="text-muted mb-2"><?= $supplierArray->Grp ?></div>

        </div>
        <hr class="my-0" />

        <div class="card-body">
            <h5 class="h6 card-title">Company Info</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-1"><strong>Code:</strong> <?= $supplierArray->code ?></li>
                <li class="mb-1"><strong>Industry:</strong> <?= !empty($supplierArray->Industry) ? $supplierArray->Industry : 'N/A' ?></li>
                <li class="mb-1"><strong>Segment:</strong> <?= !empty($supplierArray->Segment) ? $supplierArray->Segment : 'N/A' ?></li>
                <li class="mb-1"><strong>GST Status:</strong> <?= $supplierArray->GSTStatus ?></li>
                <li class="mb-1"><strong>GST No:</strong> <?= $supplierArray->gst ?></li>
                <li class="mb-1"><strong>Pan No:</strong> <?= $supplierArray->pan ?></li>
                <li class="mb-1"><strong>MSME No:</strong> <?= $supplierArray->MSMENo ?></li>
            </ul>
        </div>
        <hr class="my-0" />

        <div class="card-body">
            <h5 class="h6 card-title">Contact</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-1"><span class="fas fa-map-marker-alt"></span> <?= $supplierArray->Address ?>, <?= $supplierArray->City ?>, <?= $supplierArray->State ?>, <?= $supplierArray->Country ?> - <?= $supplierArray->PinCode ?></li>
                <li class="mb-1"><span class="fas fa-phone"></span> <?= $supplierArray->PhoneNo ?></li>
                <li class="mb-1"><span class="fas fa-envelope"></span> <?= !empty($supplierArray->BenfEmail) ? $supplierArray->BenfEmail : 'Not Available' ?></li>
                <li class="mb-1"><span class="fas fa-globe"></span> <?= !empty($supplierArray->WebSite) ? '<a href="'.$supplierArray->WebSite.'" target="_blank">'.$supplierArray->WebSite.'</a>' : 'Not Available' ?></li>
            </ul>
        </div>
        <hr class="my-0" />

        <div class="card-body">
            <h5 class="h6 card-title">Bank Details</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-1"><strong>Beneficiary:</strong> <?= $supplierArray->BenfName ?></li>
                <li class="mb-1"><strong>Beneficiary Mobile:</strong> <?= $supplierArray->BenfMobile ?></li>
            </ul>
        </div>
    </div>
</div>


						<div class="col-md-8 col-xl-9" >
							<div class="card">
								<div class="card-header">

									<h5 class="card-title mb-0">Request for Information Modification</h5>
								</div>
								<div class="card-body h-100">
										
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>