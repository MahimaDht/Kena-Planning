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
                    <h1 class="h3 mb-3"><?php //echo $title ?></h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">
                                   <table class="table table-striped" id="example123">
                                       <thead>
                                        <tr>
                                          <!--  <th></th> -->
                                            <th>Doc Entry</th>
                                            <th>Sales Order</th>
                                             <th>Item Code</th>
                                             <th>Item Name</th>
                                            <th>product Code</th>
                                            <th>product Name</th>
                                            <th>Qty</th>
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
 <div class="modal fade" id="filteredModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <form class="form-group" action="<?php echo base_url() ?>Transaction/Process" method="post">
           <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
      <div class="modal-body">
        <div class="col-md-12 table-responsive" >
            <table class="table table-bordered" id="filteredTable" style="width: 100%;">
                <thead>
                    <tr>
                        <th></th>
                        <th>Doc Entry</th>
                        <th>Sales Order</th>
                         <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Qty</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" >Process</button>
        </div>
     </form>
   </div>
  </div>
</div>

</div>


<!---------modal --------------------->

<?php 
    $this->load->view('include/footer');
?>


<script>

     var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
$(document).ready(function () {
    
    $('#example123').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        destroy: true,
          columnDefs: [
            { className: "text-nowrap", targets: [0, 1] },
          
        ],

        ajax: {
            url: "<?php echo base_url('Transaction/getProductOrderList'); ?>",
            type: "POST",
             data: function (d) {
                d[csrfName] = csrfHash;
              
            },
        },
        columns: [
            // { data: 0 }, // id
            { data: 1 }, // DocEntry
            { data: 2 }, // DocNum
            { data: 3 }, // Itemcode
            { data: 4 } ,
            { data: 5 } ,
            {data : 6 },
            {data : 7 },
            {data : 8 }  // Action
        ]
    });
});


function filteredRecords(id)
{
    $('#filteredModal').modal('show');

    $('#filteredTable').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,

        ajax: {
            url: "<?= base_url('Transaction/filteredProductionList') ?>",
            type: "POST",
            data: function (d) {
                d.id = id;
                d[csrfName] = csrfHash;
            }
        },

        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
             {data : 6 },
              {data : 7 }
        ]
    });
}


</script>