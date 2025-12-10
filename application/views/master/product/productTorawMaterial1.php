<?php
$this->load->view('include/header');
?>
<style type="text/css">

</style>
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
                    <h1 class="h3 mb-3">Mapping Raw Material</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                     <!--    <h5 class="card-title mb-0">Mapping Raw Material</h5> -->
                                        
                                        <h5 class="card-title mb-0">Product Type:<?php echo $product_type_name ?></h5>   
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="table table-responsive">
                                         <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <table class="table table-bordered "  id="datatable_index1">
                                        <thead class="bg-light">
                                            <tr>
                                                <th  width="5%">sr.no</th>
                                                <th  width="20%">Item Code</th>
                                                <th  width="20%">Item Name</th>
                                                <th  width="5%">Size</th>
                                                <th  width="5%">Guage </th>
                                               
                                                <th  style="text-align: center;" width="60%">Row Material</th>
                                               
                                                <!-- <th  width="2%" rowspan="2">Action</th> -->
                                            </tr>
                                        
                                            <!-- end tr -->
                                        </thead>
                                        <?php
                                        $x=1;
                                        foreach ($products as $key => $value) { 
                                    ?>
                                         <tr>
                                            <td  style="width:2%"><?php echo $x ?></td>
                                            <td style="width:5%">
                                             <input type="hidden" name="item_code " id="item_code_<?php echo $x?>" value="<?php echo $value->item_code ?>">
                                                <?php echo $value->item_code ?></td>
                                            <td style="width:20%"><?php echo $value->item_name ?></td>
                                            <td style="width:5%"><?php echo $value->u_ait_prod_size ?></td>
                                            <td style="width:5%"><?php echo $value->u_ait_guage ?></td>
                                         <td style="width:10%">
                                            <button type="button" class="btn btn-info" onclick="openModal('<?php echo $value->item_code ;?>','<?php echo $value->item_name ;?>')">Raw Material</button>
                                        </td>   
                                        </tr> 
                                        <?php $x++ ;} ?>
                                        <tbody>
                                            </tbody><!-- end tbody -->
                                            </table><!-- end table -->
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
<div class="modal fade" id="rawMaterialModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Raw Material</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form class="form-control" action="<?php echo base_url()?>Master/productToMaterial" method="post">
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <input type="hidden" class="form-control" id="item_code_modal" name="item_code_modal">
                <input type="hidden" class="form-control" name="product_type" value="<?php echo $product_id ?>" >

               <h6>Product : <span id="Main_product"></span></h6> 
               <table class="table table-bordered" id="rawMaterialTable" width="100%">
                 <thead>
                    <tr>
                        <th style="width: 70%">Raw Material</th>
                        <th style="width: 20%">UPS</th>
                        <th style="width:10%">
                            <button class="btn btn-info btn-sm" name="add-button" id="add-button"  onclick="addRow()" type="button"><i class="fa fa-plus"></i> </button>
                        </th>
                    </tr>
                 </thead>
                 <tbody>
                     <tr>
                        <td>
                            <select class="form-control select2 rawmaterial" name="rawMaterialName[]" id="rawMaterialName_1" onfocus ="rawItems(this.id)">

                            </select>
                        </td>
                        <td><input type="number" min="0" name="ups_input[]" id="ups_1" class="form-control"></td>
                        <td><button class="btn btn-sm btn-danger" name="delete" id="delete_1"><i class="fa fa-trash"></i></button></td>
                     </tr>
                 </tbody>

               </table>

            </div>

        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
  </form>
    </div>
  </div>
</div>

<script >
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

var base_url='<?php echo base_url() ?>';

$(document).ready(function () {
      $('#datatable_index1').DataTable({                                                     
       
    });

   // $('.select2').select2();
   


});

// function openModal(item_code, item) {
//     $("#rawMaterialModal").modal('show');
//     $("#item_code_modal").val(item_code);
//     $("#Main_product").html(item);


//     initializeSelect2("rawMaterialName_1");
// }

function openModal(item_code, item) {
    $("#rawMaterialModal").modal('show');
    $("#item_code_modal").val(item_code);
    $("#Main_product").html(item);

    // Clear existing rows first
    $("#rawMaterialTable tbody").empty();

    // Fetch saved raw materials via AJAX
    $.ajax({
        url: base_url + "Master/getSavedRawMaterials",
        type: "POST",
        data: {
            item_code: item_code,
            [csrfName]: csrfHash
        },
        dataType: "json",
        success: function(data) {
           
            if (data.length > 0) {
                // Fill rows with existing data
                data.forEach(function(row, index) {
                    let rowNumber = index + 1;
                    let newRow = `<tr>
                        <td>
                            <select class="form-control select2 rawmaterial" name="rawMaterialName[]" id="rawMaterialName_${rowNumber}" onfocus="initializeSelect2(this.id)">
                            </select>
                        </td>
                        <td>
                            <input type="number" min="0" name="ups_input[]" id="ups_${rowNumber}" class="form-control" value="${row.ups}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                    $("#rawMaterialTable tbody").append(newRow);

                    // Initialize Select2 and set selected value
                    initializeSelect2(`rawMaterialName_${rowNumber}`, row.material_code, row.ItemName);
                });
            } else {
                // No saved data, create a blank first row
                let newRow = `<tr>
                    <td>
                        <select class="form-control select2 rawmaterial" name="rawMaterialName[]" id="rawMaterialName_1" onfocus="initializeSelect2(this.id)"></select>
                    </td>
                    <td><input type="number" min="0" name="ups_input[]" id="ups_1" class="form-control"></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                $("#rawMaterialTable tbody").append(newRow);
                initializeSelect2("rawMaterialName_1");
            }
        }
    });
}

function initializeSelect2(elementId,rawCode=null,rawItemName=null) {
    var item_code = $("#item_code_modal").val();

    $("#" + elementId).select2({
        placeholder: "-Select Item-",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#rawMaterialModal"), // essential for modal
        ajax: {
            url: base_url + "Master/getRawMaterial",
            type: "POST",
            dataType: "json",
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,        // user typed search term
                    item_code: item_code,
                    [csrfName]: csrfHash
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(item => ({
                        id: item.code,       // value
                        text: item.name      // display text
                    }))
                };
            },
            cache: true
        }
    });
    if (rawCode && rawItemName) {
        let option = new Option(rawItemName, rawCode, true, true);
        $("#" + elementId).append(option).trigger('change');
    }
}


function addRow() {
  
    let tableBody = $("#rawMaterialTable tbody");

    let rowCount = tableBody.find("tr").length;
   
    let newRowNumber = rowCount + 1;

    // Create new row
    let newRow = document.createElement("tr");

    newRow.innerHTML = `
        <td>
            <select class="form-control select2 rawmaterial" name="rawMaterialName[]" id="rawMaterialName_${newRowNumber}" onfocus="initializeSelect2(this.id)">
            </select>
        </td>
        <td>
            <input type="number" min="0" name="ups_input[]" id="ups_${newRowNumber}" class="form-control">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="deleteRow(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;

    // Append the new row
    tableBody.append(newRow);
 
}


function deleteRow(button) {
    let row = button.closest("tr");
    row.remove();
}



</script>

