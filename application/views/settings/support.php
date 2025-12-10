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
                                        <iframe width="1200" height="850" src="https://system.dhtenterprise.com/forms/ticket" frameborder="0" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
<?php 
            $this->load->view('include/footer');
?>
