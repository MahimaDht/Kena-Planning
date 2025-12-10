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
	<main class="d-flex w-100 h-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center">
							<h1 class="display-1 fw-bold">404</h1>
							<p class="h2"> <?php echo $heading; ?></p>
							<p class="lead fw-normal mt-3 mb-4"> <?php echo $description; ?></p>
							<a class='btn btn-primary btn-lg' href='<?=base_url();?>'>Return to homepage</a>
						</div>

					</div>
				</div>
			</div>
		</div>
	</main>
<?php 
            $this->load->view('include/footer');
?>