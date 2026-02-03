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
                    <h1 class="h3 mb-3"> Unplanned SalesOrders</h1>
                    <div class="row">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="d-flex">
                                    <input type="number" class="form-control me-2" id="input_qty" placeholder="Enter Quantity">
                                    <button class="btn btn-primary" type="button" id="apply_qty" onclick="unPlannedSO()">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="machine_id">Select Machine</label>
                                            <select class="form-select" id="machine_id" name="machine_id">
                                                <option value="">Select Machine</option>
                                                <?php if(isset($printing_machines)): ?>
                                                    <?php foreach($printing_machines as $machine): ?>
                                                        <option value="<?= $machine->code ?>" data-machine="<?= $machine->machine_name ?>"><?= $machine->machine_name ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                
                             <form class="form-group" action="<?php echo base_url()?>Transaction/saveUnPlannedSO" method="post">
                                  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="doc_entries" id="doc_entries">
                                   <table class="table table-striped" id="example123" Style="width:100%">
                                       <thead>
                                        <tr>
                                              <th>Plan Seq</th>
                                            <th>Doc Entry</th>
                                            <th>DocNum</th>
                                            <th>Doc Date</th>
                                              <th>Card Name</th>
                                            <th>product Code</th>
                                            <th>product Name</th>
                                            <th>Raw Material</th>
                                            <th>Qty</th>
                                            <th>Territory</th>
                                            <th>Inside Printing</th>
                                            <th>Printing Pending Days</th>
                                            <th>No of days from tracing days</th>
                                            <th>Machine</th>
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
<!---------modal --------------------->

<?php 
    $this->load->view('include/footer');
?>




<script>
var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

let table;



/* store checked rows */
let checkedDocEntries = [];

$(document).ready(function () {
unPlannedSO();

});

function unPlannedSO(){
  var qty = $("#input_qty").val();

  $('#example123').DataTable().clear().destroy();

    table = $('#example123').DataTable({
        processing: true,
        serverSide: true,
        destroy: true,
        stateSave: false,
        columnDefs: [
            { orderable: false, targets: 0 }
        ],
        ajax: {
            url: "<?= base_url('Transaction/getUnPlanningList'); ?>",
            type: "POST",
            data: function (d) {
                d[csrfName] = csrfHash;
                d.qty = qty;
            }
            // success: function (data) {
            //     console.log(data);
            // }
        },

        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
             { data: 3 },
            { data: 4 },
            {data:5},
            { data: 6 },
            {data:7},
            {data:8},
            {data:9},
            {data:10},
            {data:11},
            {data:12},
            {data:13},
            // { data: 6 },
           
        ],
        drawCallback: function () {
            restoreCheckedRows();
        }
    });
 }


$(document).on('change', '.row-check', function () {

    let docEntry = $(this).val();

    if ($(this).is(':checked')) {

        if (!checkedDocEntries.includes(docEntry)) {
            checkedDocEntries.push(docEntry);
        }

     

    } else {

        
        checkedDocEntries = checkedDocEntries.filter(v => v !== docEntry);

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



function checkValidation(selectElement) {
    let selectedValue = selectElement.value;
    let id = selectElement.id;
    var row_id = id.split('_')[1];
    var machine = $('#machine_id').val();
   var machine_name = $('#machine_id option:selected').data('machine');
   
    let size = selectElement.getAttribute('data-size');
    let gauge = selectElement.getAttribute('data-gauge');
    let type = selectElement.getAttribute('data-type');
    let qty = selectElement.getAttribute('data-qty');
    let ups = selectElement.getAttribute('data-ups');
    let rawMaterialNo = selectElement.getAttribute('data-rawMaterialNo');
    let isValid = true; // flag

    $('.planseq-select').each(function () {
        if (this !== selectElement && this.value === selectedValue && selectedValue !== "") {

            let compareSize = this.getAttribute('data-size');
            let compareGauge = this.getAttribute('data-gauge');
            let compareType = this.getAttribute('data-type');
            let compareQty = this.getAttribute('data-qty');
            let compareUps = this.getAttribute('data-ups');
            let compareRawMaterialNo = this.getAttribute('data-rawMaterialNo');

            if (
                size !== compareSize ||
                gauge !== compareGauge ||
                type !== compareType ||
                qty !== compareQty ||
                ups !== compareUps ||
                rawMaterialNo !== compareRawMaterialNo
            ) {
                alert("Validation Error: Size, Gauge, Type, Qty, Raw Material , and UPS must match for the same Plan Sequence.");
                selectElement.value = "";
                isValid = false;
                return false; // break each loop
            }

            let currentCount = $('.planseq-select').filter(function() {
                return this.value === selectedValue;
            }).length;

            if (currentCount > parseInt(ups)) {
                alert("Validation Error: Plan Sequence selection exceeds UPS limit of " + ups);
                selectElement.value = "";
                isValid = false;
                return false;
            }


        }
    });

    // ✅ If validation passed, add machine to selected element
    if (isValid && selectedValue !== "") {
        $('#rowMachine_'+row_id).val(machine);
        $('#rowMachineName_'+row_id).val(machine_name);
    }
}


</script>
