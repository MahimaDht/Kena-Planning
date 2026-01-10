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

                                <form class="form-group" action="<?php echo base_url()?>Transaction/Process" method="post">
                                  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="doc_entries" id="doc_entries">
                                   <table class="table table-striped" id="example123">
                                       <thead>
                                        <tr>
                                           <th></th>
                                            <th>Doc Entry</th>
                                            <th>DocNum</th>
                                            <th>Sales Order</th>
                                           <!--    <th>Card Name</th> -->
                                            <th>product Code</th>
                                            <th>product Name</th>
                                            <th>Qty</th>
                                            <!-- <th>Action</th> -->
                                       </tr>
                                   </thead>
                                   <tbody>

                                   </tbody>
                               </table>
                            <div class="col-md-2">
                               <button type="submit" class="btn btn-primary">Submit</button>
                           </div>
                           </form>

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
var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

let table;

/* store active filter */
let activeFilter = {
    product_type: '',
    product_size: '',
    product_gauge: ''
};

/* store checked rows */
let checkedDocEntries = [];

$(document).ready(function () {

    table = $('#example123').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        stateSave: false,
        columnDefs: [
            { orderable: false, targets: 0 }
        ],
        ajax: {
            url: "<?= base_url('Transaction/getProductOrderList'); ?>",
            type: "POST",
            data: function (d) {
                d.product_type  = activeFilter.product_type;
                d.product_size  = activeFilter.product_size;
                d.product_gauge = activeFilter.product_gauge;
                d[csrfName]     = csrfHash;
            }
        },
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
             { data: 3 },
            // { data: 4 },
            { data: 5 },
            { data: 6 },
            { data: 7 }
        ],
        drawCallback: function () {
            restoreCheckedRows();
        }
    });
});




$(document).on('change', '.row-check', function () {

    let docEntry = $(this).val();

    if ($(this).is(':checked')) {

        if (!checkedDocEntries.includes(docEntry)) {
            checkedDocEntries.push(docEntry);
        }

        /* apply filter ONLY once (first checkbox decides filter) */
        if (checkedDocEntries.length === 1) {
             blockUIWithLogo();
            activeFilter.product_type  = $(this).data('product_type');
            activeFilter.product_size  = $(this).data('product_size');
            activeFilter.product_gauge = $(this).data('product_gauge');

            table.ajax.reload(function(){
                 $.unblockUI();
            });
               
        }

    } else {

        
        checkedDocEntries = checkedDocEntries.filter(v => v !== docEntry);

        /* reset filter if nothing checked */
        if (checkedDocEntries.length === 0) {
             blockUIWithLogo();
            activeFilter = {
                product_type: '',
                product_size: '',
                product_gauge: ''
            };
            
            //table.ajax.reload();
             table.ajax.reload(function(){
                 $.unblockUI();
            });
        }
    }
    $('#doc_entries').val(JSON.stringify(checkedDocEntries));
   
});

/* re-check checkbox after reload */
function restoreCheckedRows() {
    $('.row-check').each(function () {
        if (checkedDocEntries.includes($(this).val())) {
            $(this).prop('checked', true);
        }
    });
}
</script>
