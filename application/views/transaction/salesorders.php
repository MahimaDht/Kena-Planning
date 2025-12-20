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
                    <h1 class="h3 mb-3"><?php echo $title ?></h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">
                               
                                   <table class="table table-striped" id="example123">
                                       <thead>
                                        <tr>
                                           <th>ID</th>
                                           <th>Doc Date</th>
                                           <th>Doc Number</th>
                                           <th>Card Name</th>
                                           <th>Action</th>
                                       </tr>
                                   </thead>
                                   <tbody>

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


<script>

$(document).ready(function () {
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    $('#example123').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        destroy: true,
          columnDefs: [
            { className: "text-nowrap", targets: [0, 1] },
            { className: "text-center", targets: [4] } // column 4 is "Action"
        ],

        ajax: {
            url: "<?php echo base_url('Transaction/getSalesorderList'); ?>",
            type: "POST",
             data: function (d) {
                d[csrfName] = csrfHash; // add CSRF token to request
            },
        },
        columns: [
            { data: 0 }, // id
            { data: 1 }, // DocDate
            { data: 2 }, // DocNum
            { data: 3 }, // CardName
            { data: 4 }  // Action button
        ]
    });
});



</script>