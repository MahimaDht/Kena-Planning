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
                  <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3 mb-0">Mapping Machine</h1>
                        <!-- <a href="<?php echo base_url()?>Master/machines" class="btn btn-primary">Back</a> -->
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                     <h5 class="card-title mb-0">  
                                      Product Type: <?php echo $productdata->product_name?> 
                                  </h5>
                                </div>
                                <div class="card-body">

                            <?php if(isset($editdata)){?>
                             <form class="form-group " action="<?php echo base_url()?>Master/save_editMachineMapping" method="post">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <input type="hidden" name="id" value="<?php echo $editdata->id ;?>">
                                        <div class="row">
                                             <input type="hidden" name="product_type_id" id="product_type_id" value="<?php echo $product_type_id ?>">
                                          
                                         <div class="col-md-6">
                                                <label class="form-label">Process Group*</label>
                                                <select class="form-control" name="process_group" id="process_group" onchange="getProcess()" required>
                                                   <option value="">Select</option>
                                                   <?php foreach ($groupByProductType as $key => $value) { ?>
                                                    <option value="<?php echo $value->process_group_id ;?>"
                                                 <?php if($editdata->process_group_id==$value->process_group_id) {echo "selected" ;}?>><?php echo $value->name ?></option>
                                                 <?php }?>
                                                </select>
                                            </div>
                                             
                                            <div class=" col-md-6">
                                                <label class="form-label">Process*</label> 
                                                <select class="form-control" name="process" id="process" required  onchange="fetchProducts()">
                                                  
                                              </select>
                                          </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Machine*</label>
                                               <select class="form-control" name="machine" id="machine" onchange="fetchProducts()">
                                                <option value="">Select</option>
                                                <?php foreach ($machines as $key => $value) { ?>
                                                    
                                                    <option value="<?php echo $value->id ?>" <?php if($editdata->machine_id==$value->id){echo "selected";}?>><?php echo $value->machine_name ;?></option>

                                               <?php }?>
                                               </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Hourly Impression*</label>
                                                <input type="number" name="hourly_impression" id="hourly_impression" class="form-control" min="0" value="<?php echo $editdata->hourly_impression?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Changeover frequency(per day)*</label>
                                                <input type="number" name="changeover_frequency" id="changeover_frequency" class="form-control" value="<?php echo $editdata->changeover_frequency?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Capacity(Day)*</label>
                                                <input type="number" name="production_capacity" id="production_capacity" class="form-control" value="<?php echo $editdata->production_capacity?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Min Quantity</label>
                                                <input type="number" name="min_qty" id="min_qty"
                                                class="form-control" value="<?php echo $editdata->min_qty?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Quantity</label>
                                                <input type="number" name="max_qty" id="max_qty"
                                                class="form-control" value="<?php echo $editdata->max_qty ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Min Width(Row Material)</label>
                                                <input type="number"  step="0.01"name="min_width" id="min_width"
                                                    class="form-control" value="<?php echo $editdata->min_width ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Width(Row Material)</label>
                                                <input type="number" step="0.01" name="max_width" id="max_width" class="form-control" value="<?php echo $editdata->max_width ?>">
                                            </div>

                                             <div class="col-md-4">
                                                <label class="form-label">Min Length(Row Material)</label>
                                                <input type="number" step="0.01" name="min_height" id="size"
                                                    class="form-control" value="<?php echo $editdata->min_height ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Max Length(Row Material)</label>
                                                <input type="number" step="0.01" name="max_height" id="max_height" class="form-control" value="<?php echo $editdata->max_height ?>">
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">Gauge</label>
                                                <select class="form-select select2" name="guage[]" id="guage" multiple>
                                                    <button type="button" class="btn btn-sm btn-primary ml-2 mt-2" onclick="selectAllRegion()">Select All</button>
                                                    <option value="">Select</option>
                                                <?php 

                                                 $gaugearray = explode(',', $editdata->guage);
                                                foreach($gauges as $value) { ?>

                                                    <option value="<?php echo $value->id?>"  <?php echo (isset($editdata) && in_array($value->id, $gaugearray)) ? 'selected' : ''; ?>>
                                                        <?php echo $value->gauge_name?>
                                                            
                                                        </option>
                                                <?php }?>
                                            </select>
                                         
                                            </div>
                                          
                                         
                                          <!--   <h6 class="mt-4">Products :</h6>
                                               <input type="hidden" name="products" id="products" value="">
                                            <div class="col-12 mt-2">
                                                <table class="table table-bordered " id="datatable_index1" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="form-check" id="select_all" ></th>
                                                            <th>Item Code</th>
                                                            <th>Item Name</th> 
                                                        </tr>
                                                    </thead>
                                                    <tbody id="productBody">
                                                       
                                                    </tbody>
                                                </table>
                                            </div> -->
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn  btn-info">Submit</button>
                                        </div>
                                    </form>

                            <?php }else{ ?>
                                    <form class="form-group" action="<?php echo base_url()?>Master/save_addMachineMapping" method="post">
                                          <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                        <div class="row">
                                            <input type="hidden" name="product_type_id" id="product_type_id" value="<?php echo $product_type_id ?>">
                                          
                                           
                                         <div class="col-md-6">
                                                <label class="form-label">Process Group*</label>
                                                <select class="form-control" name="process_group" id="process_group" onchange="getProcess()" required>
                                                   <option value="">Select</option>
                                                   <?php foreach ($groupByProductType as $key => $value) { ?>
                                                    <option value="<?php echo $value->process_group_id ;?>" ><?php echo $value->name ?></option>
                                                 <?php }?>
                                                </select>
                                            </div>
                                             
                                            <div class="col-md-6">
                                                <label class="form-label">Process*</label> 
                                                <select class="form-control" name="process" id="process" required  onchange="fetchProducts()">
                                                  
                                              </select>
                                          </div>
                                              <div class="col-md-6">
                                                <label class="form-label">Machine*</label>
                                               <select class="form-control" name="machine" id="machine" onchange="fetchProducts()">
                                                <option value="">Select</option>
                                                <?php foreach ($machines as $key => $value) { ?>
                                                    
                                                    <option value="<?php echo $value->id ?>"><?php echo $value->machine_name ;?></option>

                                               <?php }?>
                                               </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Hourly Impression*</label>
                                                <input type="number" name="hourly_impression" id="hourly_impression" class="form-control" min="0">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Changeover frequency(per day)*</label>
                                            <input type="number" name="changeover_frequency"  step="0.01" id="changeover_frequency" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Capacity(Day)*</label>
                                                <input type="text" name="production_capacity" id="production_capacity" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Min Quantity</label>
                                                <input type="number" name="min_qty" id="min_qty"
                                                class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Quantity</label>
                                                <input type="number" name="max_qty" id="max_qty"
                                                class="form-control">
                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Min Width(Row Material)</label>
                                                <input type="number" step="0.01"  name="min_width" id="min_width"
                                                    class="form-control" >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Width(Row Material)</label>
                                                <input type="number" step="0.01" name="max_width" id="max_width" class="form-control" >
                                            </div>

                                             <div class="col-md-4">
                                                <label class="form-label">Min length(Row Material)</label>
                                                <input type="number" step="0.01" name="min_height" id="min_height" class="form-control" >
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Max length</label>
                                                <input type="number" step="0.01" name="max_height" id="max_height" class="form-control">
                                            </div>
                                          <!--   <div class="col-md-3">
                                                <label class="form-label">Size</label>
                                                <input type="number" name="size" id="size"
                                                class="form-control">
                                            </div> -->
                                            <div class="col-md-3">
                                                <label class="form-label">Gauge</label>
                                                 <button type="button" class="btn btn-sm btn-primary ml-2 mt-2" onclick="selectAllRegion()">Select All</button>

                                                <select class="form-select select2" name="guage[]" id="guage" multiple>
                                                    <option value="">Select</option>
                                                <?php foreach($gauges as $value) { ?>
                                                    <option value="<?php echo $value->id?>"><?php echo $value->gauge_name?></option>
                                                <?php }?>
                                            </select>
                                            </div>
                                        
                                          <!--   <h6 class="mt-4">Products :</h6>
                                            <div class="col-12 mt-2">
                                                <input type="hidden" name="products" id="products" value="">
                                                <table class="table table-bordered " id="datatable_index1" width="100%">
                                                    <thead>
                                                        <tr>
                                                        <th><input type="checkbox" class="form-check" id="select_all"></th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                       
                                                    </tr>
                                                    </thead>
                                                    <tbody id="productBody">
                                                       
                                                    </tbody>
                                                </table>
                                            </div> -->
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn  btn-info">Submit</button>
                                        </div>
                                    </form>
                                <?php }?>
                                </div>
                            </div>
                <!------- Assign Machine -------------------------------------------------->
                    <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Existing Mappings</h5>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table  " id="datatable_index">
                                        <thead>
                                            <tr>
                                                <th>Sr.</th>
                                                <th>Process Group</th>
                                                <th>Process Name</th>
                                                <th>Machine Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                  <th>Sr.</th>
                                                <th>Process Group</th>
                                                <th>Process Name</th>
                                                <th>Machine Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                            $x=1; 
                                            foreach($mappingMachineData as $value)
                                            { 
                                                ?>
                                                <tr>
                                                    <td><?php echo $x; ?></td>
                                                    <td><?php echo $value->name; ?></td>
                                                    <td><?php echo $value->process_name; ?></td>
                                                    <td><?php echo $value->machine_name; ?></td>
                                                
                                                    <td class="text-center">
                                                    <a href="<?php echo base_url().'Master/editmappingMachine/'.base64_encode($value->product_type_id).'/'.base64_encode($value->id)?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                                       <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_machineMapping/'.base64_encode($value->id).'/'.base64_encode($value->product_type_id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> 
                                                   </td>
                                               </tr>
                                               <?php
                                               $x=$x+1;
                                           }
                                           ?>
                                       </tbody>
                                   </table>

                               </div>
                           </div>
                         </div>
                           
                        </div>
                    </div>
                </div>
            </main>
        <?php
        $this->load->view('include/footer');
    ?>


<script type="text/javascript">

var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

var mappedProducts = <?php echo isset($editproductmapping) ? json_encode(array_column($editproductmapping, 'item_code')) : '[]'; ?>;
var selectedItems = [...mappedProducts]; 
var table = null;

$(document).ready(function(){
    $('.select2').select2();

    <?php if (isset($editdata)) { ?>
        getProcess('<?php echo $editdata->process_id; ?>');
        setTimeout(function() {
            fetchProducts();
        }, 100);
    <?php } ?>

    $('form').on('submit', function() {
        syncSelectedItems();
    });
});

 function selectAllRegion()

    {

        $("#guage > option").prop("selected", true);

        $("#guage").trigger("change");

    }

function getProcess(selectedprocess=null) {
    var group = $("#process_group").val();
    var product_type_id = $("#product_type_id").val();

    $.ajax({
        url: '<?= base_url("Master/processByProductType"); ?>',
        type: 'POST',
        data: {
            group: group,
            product_type_id: product_type_id,
            [csrfName]: csrfHash 
        },
        dataType:'json',
        success: function(response) {
            $('#csrf_token').val(response.csrfHash);
            $("#process").html('<option value="">Select</option>'+response.process);
            if(selectedprocess){
                $("#process").val(selectedprocess);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}

/*function fetchProducts() {
    var product_type_id = $("#product_type_id").val();
    var process_id = $("#process").val();
    var machine_id = $("#machine").val();

    if(process_id && machine_id && product_type_id){
        table = $('#datatable_index1').DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "destroy": true,
            "columnDefs": [
                { orderable: false, targets: 0 },
                { className: "text-nowrap", "targets": [1,2] },
                { className: "text-center", "targets": [0] }
            ],
            "ajax": {
                "url": "<?php echo base_url('Master/fetchProductFormapping'); ?>",
                "type": "POST",
                "data": {  
                    product_type_id: product_type_id,
                    process_id: process_id,
                    machine_id: machine_id,
                    selectedProducts: mappedProducts,
                    [csrfName]: csrfHash  
                }
            },
            "drawCallback": function() {
                updateCheckboxes();
            }
        });
    }
}

function updateCheckboxes() {
    if (!table) return;

    
    table.rows({ search: 'applied' }).every(function () {
        var checkbox = $(this.node()).find('.item');
        var itemId = checkbox.val();
        checkbox.prop('checked', selectedItems.includes(itemId));
    });

   
    var allVisibleChecked = true;
    table.rows({ page: 'current' }).every(function () {
        if (!$(this.node()).find('.item').is(':checked')) {
            allVisibleChecked = false;
        }
    });
    $('#select_all').prop('checked', allVisibleChecked);
}


$(document).on('click', '#select_all', function () {
    var isChecked = this.checked;

    table.rows({ page: 'current' }).every(function () {
        var checkbox = $(this.node()).find('.item');
        var itemId = checkbox.val();
        if (isChecked) {
            if (!selectedItems.includes(itemId)) selectedItems.push(itemId);
        } else {
            selectedItems = selectedItems.filter(id => id !== itemId);
        }
    });

    updateCheckboxes();
});


$(document).on('change', '#datatable_index1 .item', function () {
    var itemId = $(this).val();
    if (this.checked) {
        if (!selectedItems.includes(itemId)) selectedItems.push(itemId);
    } else {
        selectedItems = selectedItems.filter(id => id !== itemId);
        $('#select_all').prop('checked', false);
    }
});

function syncSelectedItems() {
    $("#products").val(selectedItems.join(",")); 
}*/
</script>

