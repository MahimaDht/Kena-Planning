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
                                
                             <form class="form-group" action="<?php echo base_url()?>Transaction/saveUnPlannedSO" method="post" id="unPlannedSOForm">
                                  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="doc_entries" id="doc_entries">
                                   <table class="table table-striped" id="example123" Style="width:100%">
                                       <thead>
                                        <tr>
                                            <th width="100px">Plan Seq</th>
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
                               <button type="button" class="btn btn-primary" onclick="showPreviewModal()">Submit</button>
                           </div>
                           </form>

                           </div>
                       </div>
                   </div>


               </div>

           </div>
           
       </main>
<!---------modal --------------------->

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview - Records with Machine & Plan Sequence Assigned</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="previewForm" action="<?php echo base_url()?>Transaction/saveUnPlannedSO" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="previewTable">
                        <thead>
                            <tr>
                                <th>Plan Seq</th>
                                <th>Doc Entry</th>
                                <th>DocNum</th>
                                <th>Doc Date</th>
                                <th>Card Name</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Raw Material</th>
                                <th>UPS</th>
                                <th>Qty</th>
                                <th>Territory</th>
                                <th>Inside Printing</th>
                                <th>Printing Pending Days</th>
                                <th>No of days from tracing days</th>
                                <th>Machine</th>
                                <th>Duplicate</th>
                            </tr>
                        </thead>
                        <tbody id="previewTableBody">
                        </tbody>
                    </table>
                </div>
                <div id="noRecordsMessage" class="alert alert-warning" style="display:none;">
                    No records found with both Machine and Plan Sequence assigned.
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="finalSubmitBtn" onclick="submitForm()">Confirm & Submit</button>
            </div>
        </div>
    </div>
</div>

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

   if(machine == ""){
    alert("Please select machine");
    selectElement.value = "";
    return false;
   }
   
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

/**
 * Show preview modal with only records that have both machine and plan sequence assigned
 */
function showPreviewModal() {
    // Get all rows from the DataTable
    let previewData = [];
    
    // Iterate through all rows in the DataTable
    table.rows().every(function() {
        let rowData = this.data();
        let rowNode = this.node();
       
        
        // Check if this row has both plan sequence and machine assigned
        // Assuming column 0 is Plan Seq and column 13 is Machine
        let planSeq = $(rowNode).find('.planseq-select').val();
      
        let machine = $(rowNode).find('[id^="rowMachineName_"]').val();
     

        let ups = $(rowNode).find('.planseq-select').attr('data-ups');

        // Only include rows where both plan sequence and machine are assigned
        if (planSeq && planSeq !== "" && machine && machine !== "") {
            
            // Extract values for hidden inputs from the current row
            let docEntryVal = $(rowNode).find('input[name="doc_entry[]"]').val();
            let lineNumVal = $(rowNode).find('input[name="line_num[]"]').val();
            let tracingDaysVal = $(rowNode).find('input[name="tracing_days[]"]').val();
            let machineId = $(rowNode).find('input[name="machineid[]"]').val();

            previewData.push({
                planSeq: planSeq,
                docEntry: rowData[1], // HTML content for column 1
                docEntryVal: docEntryVal, // Value for hidden input
                lineNumVal: lineNumVal,   // Value for hidden input
                
                docNum: rowData[2],
                docDate: rowData[3],
                cardName: rowData[4],
                productCode: rowData[5],
                productName: rowData[6],
                rawMaterial: rowData[7],
                ups: ups,
                qty: rowData[8],
                territory: rowData[9],
                insidePrinting: rowData[10],
                printingPendingDays: rowData[11],
                tracingDays: rowData[12],
                tracingDaysVal: tracingDaysVal, // Value for hidden input
                machine: machine,
                machineId: machineId // Value for hidden input
            });
        }
    });
    

    // Sort the preview data by plan sequence number (ascending order)
    previewData.sort(function(a, b) {
        return parseInt(a.planSeq) - parseInt(b.planSeq);
    });
    
    // Clear previous preview data
    $('#previewTableBody').empty();
    
    if (previewData.length === 0) {
        // Show warning message if no records found
        $('#previewTable').hide();
        $('#noRecordsMessage').show();
        $('#finalSubmitBtn').prop('disabled', true);
    } else {
        // Populate the preview table
        $('#previewTable').show();
        $('#noRecordsMessage').hide();
        $('#finalSubmitBtn').prop('disabled', false);
        
        previewData.forEach(function(row) {
            let tr = `<tr data-planseq="${row.planSeq}" data-ups="${row.ups}">
                <td>${row.planSeq}
                    <input type="hidden" name="planseq[]" value="${row.planSeq}">
                    <input type="hidden" name="doc_entry[]" value="${row.docEntryVal}">
                    <input type="hidden" name="line_num[]" value="${row.lineNumVal}">
                    <input type="hidden" name="tracing_days[]" value="${row.tracingDaysVal}">
                    <input type="hidden" name="machineid[]" value="${row.machineId}">
                </td>
                <td>${row.docEntry}</td>
                <td>${row.docNum}</td>
                <td>${row.docDate}</td>
                <td>${row.cardName}</td>
                <td>${row.productCode}</td>
                <td>${row.productName}</td>
                <td>${row.rawMaterial}</td>
                <td>${row.ups}</td>
                <td>${row.qty}</td>
                <td>${row.territory}</td>
                <td>${row.insidePrinting}</td>
                <td>${row.printingPendingDays}</td>
                <td>${row.tracingDays}</td>
                <td>${row.machine}</td>
                <td><input type="number" name="usedups[]" class="form-control" value="1"/></td>
            </tr>`;
            $('#previewTableBody').append(tr);
        });
    }
    
    // Show the modal
    var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
    previewModal.show();
}

/**
 * Submit the form after confirmation from preview modal
 */
function submitForm() {

    let planSeqTotals = {};
    let isValid = true;
    let errorMessage = "";

    $('#previewTableBody tr').each(function() {
        let row = $(this);
        let planSeq = row.data('planseq'); 
        let totalUps = parseInt(row.data('ups'));
        let usedUps = parseInt(row.find('input[name="usedups[]"]').val()) || 0;
        
        if(planSeq) {
             if (!planSeqTotals[planSeq]) {
                planSeqTotals[planSeq] = {
                    total: totalUps,
                    current: 0
                };
            }
            planSeqTotals[planSeq].current += usedUps;
        }
    });

    for (let planSeq in planSeqTotals) {
         if (planSeqTotals[planSeq].current !== planSeqTotals[planSeq].total) {
            isValid = false;
            errorMessage += `For Plan Sequence ${planSeq}: Sum of used ups (${planSeqTotals[planSeq].current}) must equal total ups (${planSeqTotals[planSeq].total}).\n`;
         }
    }

    if (!isValid) {
         alert(errorMessage);
         return;
    }

            // Close the modal
            var previewModal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
            if (previewModal) {
                previewModal.hide();
            }
            
            
    // Submit the existing form
    // Submit the new modal form
    $('#previewForm').submit();
}


</script>
