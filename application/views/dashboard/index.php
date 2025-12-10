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

					<h1 class="h3 mb-3"><?=$title;?></h1>

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0"><?=$title;?></h5>
								</div>
								<div class="card-body">

<?php
    if (!$this->session->flashdata('feedback')) { ?>
										<!-- <div class="col-12 col-lg-12">
							<div class="card">
								<div class="card-body">
									<div class="mb-3">
										<div class="alert  alert-dismissible" role="alert">
											<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
											<div class="alert-message">
												 <h1>Welcome to the INTEGRA Engineering India Limited Supply Chain Portal!</h1>
        <p>You have successfully logged into the <strong>INTEGRA Engineering India Limited Supply Chain Portal</strong>. We are excited to have you on board and look forward to helping you streamline your supply chain operations with our advanced tools and features.</p> <p>We are excited to have you as a valued Business Partner. This portal is designed to streamline communication, enhance collaboration, and provide you with easy access to important information, tools, and resources needed to manage your relationship with us efficiently.</p><p>Here, you can track orders, manage invoices, view supply chain transactions, and much more—all in one convenient place.</p>


        <h2>Key Benefits of the Integra Supply Chain Module:</h2>
        <ul>
            <li><strong>Enhanced Visibility:</strong> Gain real-time insights into your supply chain, from procurement to delivery, ensuring transparency at every stage.</li>
            <li><strong>Improved Efficiency:</strong> Automate routine tasks, reduce manual errors, and optimize workflows to save time and resources.</li>
            <li><strong>Data-Driven Decisions:</strong> Access powerful analytics and reporting tools to make informed, strategic decisions.</li>
            <li><strong>Seamless Collaboration:</strong> Connect with suppliers, partners, and team members effortlessly through an integrated communication platform.</li>
            <li><strong>Scalability:</strong> Adapt the module to your growing business needs, whether you're a small enterprise or a large corporation.</li>
        </ul>

        <h2>Getting Started:</h2>
        <ul>
            <li>Explore the <strong>dashboard</strong> for an overview of your supply chain performance.</li>
            <li>Leverage the <strong>analytics section</strong> to generate custom reports and insights.</li>
            <li>Check out the <strong>help center</strong> or contact our support team for any assistance.</li>
        </ul>

        <p>We are committed to providing you with a seamless and productive experience. Thank you for choosing the INTEGRA Engineering India Limited Supply Chain Portal. as your trusted partner in supply chain management.</p>

											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
 -->

<?php } ?>
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>
<?php 
            $this->load->view('include/footer');
?>