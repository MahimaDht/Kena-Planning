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
                    <h1 class="h3 mb-0">Routing Master</h1>
                    <!-- <a href="<?php echo base_url()?>Master/machines" class="btn btn-primary">Back</a> -->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                               <h5 class="card-title mb-0">  
                                Routing Master
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if(isset($editdata)){

                                   $action =   ($assign) ? base_url('Master/save_routing') : base_url('Master/save_editRouting'); 

                             ?>
                                <form class="form-group " action="<?php echo $action?>" method="post">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <input type="hidden" name="id" value="<?php echo $editdata->id ;?>">
                                     <input type="hidden" class="form-control" name="rout_code" value="<?php if($assign==0){echo $editdata->rout_code ;}?>"readonly>

                                 <?php if ($assign == 1): ?>
                                    <input type="hidden" name="assign" value="1">
                                <?php elseif ($assign == 0): ?>
                                    <h6>Product Code: <span class="badge bg-primary fs-6"><?= htmlspecialchars($editdata->rout_code); ?></span></h6>
                                <?php endif; ?>

                                    <div class="row mt-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Product Type</label>
                                            <select class="form-select" name="product_type" id="product_type" onchange="fetchgroups()">
                                                <?php foreach($product_types as $value){?>
                                                    <option value="<?php echo $value->id?>" 
                                                        <?php if($value->id==$editdata->product_type){ echo "selected ";} ?>

                                                        ><?php echo $value->product_name?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Name</label>
                                            <input class="form-control" type="text" name="name" id="name" value="<?php  if($assign==0){echo $editdata->name;} ?>">
                                        </div>
                                        
                                        <div class=" col-md-4">
                                            <label class="form-label">Description</label>
                                            <input class="form-control" type="text" name="description" id="description" value="<?php  if($assign==0) {echo htmlspecialchars($editdata->description); }?>">
                                        </div>
                                        <div class="col-md-12">
                                            <table class="table table-striped">
                                                <thead>
                                                    <th width="10%">sr.no</th>
                                                    <th width="20%">Layout</th>
                                                    <th width="20%">Group</th>
                                                    <th width="20%">Subgroup</th>
                                                    <th width="20%">Process</th>
                                                  <!--   <th>Process Time</th> -->
                                                    <th><button class="btn btn-info btn-sm" type="button" onclick="addRow()"><i class="fa fa-plus"></i></button></th>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                    $x=1;
                                                    foreach($editroutdetail as $value){ ?>
                                                        <tr>
                                                            <td>
                                                        <input type="number" class="form-control" name="sequence[]" id="sequence_<?php echo $x ?>" value="<?php echo $value->seq?>">
                                                        </td>
                                                        <td>
                                                        <?php $layoutIds = explode(',', $layouts->mapping_layouts); ?>
                                                        <select class="form-select layout-select" name="layouts[]" id="layout_<?php echo $x?>" >
                                                        
                                                            <?php foreach ($layoutIds as $id){
                                                            
                                                            $layout = $this->master_model->getProductLayoutData($id);
                                                            ?>
                                                            <option value="<?= $id ?>" <?php if($value->layout_id==$id){echo "selected";} ?>><?= $layout->layoutName ?></option>
                                                            <?php } ?>
                                                            
                                                        </select>
                                                        </td>
                                                           <td>
                                                               <select name="group[]" class="form-select group-select" id="group_<?php echo $x?>">
                                                                <option value="">Select</option>
                                                                <?php foreach($groups as $g): ?>
                                                                    <option value="<?= $g->process_group_id ?>" <?= ($g->process_group_id == $value->group_id ? 'selected' : '') ?>>
                                                                        <?= $g->name ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="sub_group[]" class="form-select sub-group-select" id="sub_group_<?php echo $x?>">
                                                                <option value="">Select</option>
                                                                <?php 

                                                         $subgroups=$this->master_model->fetchProcessSubGroupByProductType($editdata->product_type,$value->group_id );


                                                                foreach($subgroups as $sg): ?>
                                                                    <option value="<?= $sg->process_subgroup_id ?>" <?= ($sg->process_subgroup_id == $value->sub_group_id ? 'selected' : '') ?>>
                                                                        <?= $sg->subgroup_name ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <!-- Process Dropdown -->
                                                            <select name="process[]" class="form-select" id="process_<?php echo $x?>">
                                                                <option value="">Select</option>
                                                                <?php 
                                                              
                                                              if($value->sub_group_id!='')
                                                              {
                                                                $processes=$this->master_model->getProcessByproduct_type($editdata->product_type,$value->group_id, $value->sub_group_id);
                                                              
                                                              }
                                                              else{
                                                                  $processes=$this->master_model->getProcessByproduct_type($editdata->product_type,$value->group_id);
                                                              }

                                                                foreach($processes as $p): ?>
                                                                    <option value="<?= $p->process_id ?>" <?= ($p->process_id == $value->process_id ? 'selected' : '') ?>>
                                                                        <?= $p->process_name ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </td>
                                                       <!--  <td>
                                                            <input type="number" name="process_time[]" class="form-control" id="process_time" value="<?php echo $value->process_time ;?>">
                                                        </td> -->
                                                        <td>
                                                            <button class="btn btn-sm btn-danger" type="button"  onclick="deleteRow(this)"><i class="fa fa-trash" ></i></button>
                                                        </td>
                                                    </tr>

                                                <?php $x++;} ?>

                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn  btn-info" onclick="return validate()">Submit</button>
                                </div>
                            </form>

                        <?php }else{  ?>


                            <form class="form-group" action="<?php echo base_url()?>Master/save_routing" method="post">
                              <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                              <div class="row">   
                                 <div class="col-md-4">
                                    <label class="form-label">Product Type</label>
                                    <select class="form-select" name="product_type" id="product_type" onchange="fetchLayouts()">
                                        <option value="">Select</option>
                                        <?php foreach($product_types as $value){?>
                                            <option value="<?php echo $value->id?>"><?php echo $value->product_name?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Name</label>
                                    <input class="form-control" type="text" name="name" id="name" >
                                </div>

                                <div class=" col-md-4">
                                    <label class="form-label">Description</label>
                                    <input class="form-control" type="text" name="description" id="description">
                                </div>
                                <div class="col-md-12 mt-4">
                                    <table class="table table-striped">
                                        <thead>
                                            <th width="10%">sr.no</th>
                                            <th width="20%">Layout</th>
                                            <th width="20%">Group</th>
                                            <th width="20%">Subgroup</th>
                                            <th width="20%">Process</th>
                                           <!--  <th>Process Time(Minuts)</th> -->
                                            <th><button class="btn btn-info btn-sm" onclick="addRow()" type="button"><i class="fa fa-plus"></i></button></th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <input type="number" name="sequence[]" id="sequence_1" value="1" class="form-control">
                                                </td>
                                                 <td>
                                                    <select class="form-select layout-select" name="layouts[]" id="layout_1" >
                                                       
                                                 </select> 
                                                 </td>
                                                <td>
                                                   
                                                 <select class="form-select group-select" name="group[]" id="group_1">
                                                        <option value="">Select</option>
                                                 </select>
                                             </td>
                                             <td>
                                                <select class="form-select sub-group-select" name="sub_group[]" id="sub_group_1">
                                                    <option value="">Select</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-select " name="process[]" id="process_1">
                                                 <option value="">Select</option>
                                             </select> 
                                         </td>
                                        <!--  <td>
                                            <input type="number" name="process_time[]" class="form-control" id="process_time_1">
                                        </td> -->
                                        <td>
                                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn  btn-info" onclick="return validate()">Submit</button>
                    </div>
                </form>
            <?php }?>
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

var rowCount = $("table tbody tr").length;

$(document).ready(function(){
    $('.select2').select2();
});


/*function validate() {
    let isValid = true;

    // Get all selects with name="process[]"
    document.querySelectorAll('select[name="process[]"]').forEach(function(select) {
        if (select.value.trim() === "") {
            isValid = false;
        }
    });

    if (!isValid) {
        alert("Please select a process for all rows.");
        return false; // Prevent form submit
    }

    return true; 
}*/


function validate() {
    let seqs = [], valid = true;

    // check process selected
    $('select[name="process[]"]').each(function () {
        if (!$(this).val()) valid = false;
    });
    if (!valid) { alert("Please select a process for all rows."); return false; }

    // check seq numbers unique
    $('input[name="sequence[]"]').each(function () {
        let v = $(this).val().trim();
        if (!v || seqs.includes(v)) {
            alert("Sequence numbers must be unique and not empty.");
            valid = false; return false; // break
        }
        seqs.push(v);
    });

    return valid;
}

/* --------------------------
   Add new row dynamically
   --------------------------- */
   function addRow(){
    // if(e) e.preventDefault(); // stop form submit

    rowCount++;
    let newRow = `
    <tr>
    <td> <input type="number" class="form-control" name="sequence[]" id="sequence_${rowCount}" value="${rowCount}"></td>
     <td>
        <select class="form-select layout-select" name="layouts[]" id="layout_${rowCount}" >
                                                   
        </select> 
    </td>
    <td>
    <select class="form-select group-select" name="group[]" id="group_${rowCount}">
    <option value="">Select</option>
   
    </select>
    </td>
    <td>
    <select class="form-select sub-group-select" name="sub_group[]" id="sub_group_${rowCount}">
    <option value="">Select</option>
    </select>
    </td>
    <td>
    <select class="form-select process-select" name="process[]" id="process_${rowCount}">
    <option value="">Select</option>
    </select>
    </td>
    
    <td>
    <button class="btn btn-sm btn-danger" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button>
    </td>
    </tr>
    `;

    $("table tbody").append(newRow);
    fetchLayouts(rowCount);
   // fetchgroups(rowCount);
}

/* --------------------------
   Delete row
   --------------------------- */
   function deleteRow(btn){
    $(btn).closest("tr").remove();
   
}

   $(document).on("change", ".layout-select", function() {
    let layoutId = $(this).val();
    let product_type = $("#product_type").val();
    let rowId = $(this).attr("id").split("_")[1];

    if(layoutId && product_type) {
        $.ajax({
            url: "<?= base_url('Master/fetchProcessGroupByProductType') ?>",
            type: "POST",
            data: {
                layout_id: layoutId,
                product_type: product_type,
                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(res) {
           
                let groupSelect = $("#group_" + rowId);

                   let subGroupSelect = $("#sub_group_" + rowId);
                let processSelect = $("#process_" + rowId);

              
                subGroupSelect.empty().append('<option value="">Select Subgroup</option>');
                processSelect.empty().append('<option value="">Select Process</option>');
                groupSelect.empty(); // clear previous options
               

               groupSelect.html('<option value="">select</option>'+res.group);
               

                csrfHash = res.csrfHash; // update CSRF token

             

            }
        });
    } else {
        alert("Please select a Product Type first");
    }
});


$(document).on("change", ".group-select", function(){
    let groupId = $(this).val();
     let product_type=$("#product_type").val();

    let rowId = $(this).attr("id").split("_")[1];

    if(groupId && product_type){
        $.ajax({
            url: "<?= base_url('Master/fetchProcessSubGroupByProductType') ?>",
            type: "POST",
            data: {
                group: groupId,
                product_type: product_type, 
                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(res){
                // console.log(res);
                let subSelect = $("#sub_group_" + rowId);

                subSelect.html('<option value="">select</option>'+res.subgroup);
                $("#process_"+rowId).html('<option value="">select</option>'+res.process);

             

                csrfHash = res.csrfHash; // update CSRF token
            }
        });
    }
    else{
        alert("Group and Product Type Select First");
    }
});


$(document).on("change", ".sub-group-select", function(){
    let subGroupId = $(this).val();
     let product_type=$("#product_type").val();

    let rowId = $(this).attr("id").split("_")[2];
    let groupId = $("#group_"+rowId).val();

    if(subGroupId){
        $.ajax({
            url: "<?= base_url('Master/fetchProcessByProductType') ?>",
            type: "POST",
            data: {
                group: groupId,
                product_type: product_type,
                subgroup: subGroupId,
                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(res){

              let processSelect = $("#process_" + rowId);

              processSelect.html('<option value="">select</option>'+res.process);

               

                csrfHash = res.csrfHash;
            }
        });
    }
});

function fetchLayouts(targetRowId = null) {
    let product_type = $("#product_type").val();

    if (product_type != '') {
        $.ajax({
            url: "<?= base_url('Master/getproductTypeById') ?>",
            type: "POST",
            data: {
                id: product_type,

                [csrfName]: csrfHash
            },
            dataType: "json",
            success: function(res) {
             
                let layoutSelect;

                // Determine which select to populate
                if (targetRowId) {
                    layoutSelect = $("#layout_" + targetRowId);
                } else {
                    layoutSelect = $(".layout-select");
                }

              
              //  layoutSelect.empty();
                layoutSelect.append('<option value="">Select Layout</option>');

                // Populate new options
                res.layouts.forEach(function(l) {
                    layoutSelect.append('<option value="' + l.id + '">' + l.layout_name + '</option>');
                });

                // Update CSRF token
                csrfHash = res.csrfHash;
            }
        });
    } else {
        alert("Select Product Type First");
    }
}


// function fetchgroups(targetRowId=null){
//    let product_type=$("#product_type").val();
//    let layout_id=$("#layout_id_"+targetRowId).val();

//     if(product_type !=''){
//         $.ajax({
//             url: "<?= base_url('Master/fetchProcessGroupByProductType') ?>",
//             type: "POST",
//             data: {
//                 product_type: product_type,
//                 layout_id:layout_id,
              
//                 [csrfName]: csrfHash
//             },
//             dataType: "json",
//             success: function(res){
//                   $("#group_" + targetRowId).empty();
//                if(targetRowId){
                  
//                     $("#group_" + targetRowId).html('<option value="">Select</option>' + res.group);
//                 }
//                 csrfHash = res.csrfHash;
//             }
//         });
//     }
//     else{
//         alert("Select Product Type First");
//     }
// }


</script>
