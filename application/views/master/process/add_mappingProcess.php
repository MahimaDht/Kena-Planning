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

                    <h1 class="h3 mb-3"><?php echo $title ?>
                    <a href="<?php echo base_url('master/process_mapping')?>" class="btn  btn-primary float-end">Back</a>
                    </h1>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">  
                                      Product Type: <?php echo $productdata->product_name?> 
                                  </h5>
                              </div>
                              <div class="card-body">
                                <form class="form-group row" role="form" action="<?php echo base_url('Master/save_processMapping') ?>" method="post">
                                    <input type="hidden" name="id" value="<?php echo $productdata->id ; ?>">
                                    <input type="hidden" name="edit_id" value="<?php if(isset($editdata)){ echo $editdata->id ;}?>">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

                                    <div class="col-md-6">
                                        <label class="form-label">Process Group*</label>
                                        <select class="form-control" name="process_group" id="process_group" onchange="getsubgroup()" required>
                                            <option value="">Select</option>
                                            <?php foreach ($process_group as $key => $value) { ?>
                                             <option value="<?php echo $value->id ;?>" <?php if(isset($editdata) && ($value->id==$editdata->process_group_id)){echo "selected";}?>><?php echo $value->name ?></option>
                                         <?php }?>
                                     </select>
                                 </div>
                                 <div class="col-md-6">
                                    <label class="form-label">Process Subgroup*</label>
                                    <select class="form-control" name="process_subgroup" id="process_subgroup" onchange="fetchProcess()">
                                        <option value="">Select</option>

                                    </select>
                                </div>
                                <div class=" col-md-10">
                                    <label class="form-label">Process*</label> 
                                    <select class="form-control" name="process" id="process"   required onchange="showParameters()">
                                      

                                  </select>
                              </div>
                              
                            <div class="form-check col-md-2 mt-4">
                                  <input class=" form-check-input" type="checkbox" value="Y" id="default_select" name="is_default" style=" transform: scale(1.2);" <?php if(isset($editdata) &&($editdata->is_default=='Y')){echo "checked" ;}?>>
                                  <label class="form-check-label" for="default_select">
                                   Is Default ?
                                  </label>
                            </div>
                             
                              <div class=" col-md-6">
                                <label class="form-label">Product Layout*</label>
                                <select class="form-control" name="product_layout" id="product_layout" required>
                                  <option value="">Select</option>
                                  <?php foreach ($layoutDetails as $key => $value) { ?>
                                   <option value="<?php echo $value->id ;?>" <?php if(isset($editdata)&&($editdata->product_layout_id==$value->id)){echo "selected" ;} ?>><?php echo $value->layoutName?></option>
                               <?php  } ?>
                           </select>
                       </div>
                        <div class=" col-md-3">
                                <label class="form-label">Formula*</label>
                                <select class="form-control" name="formula" id="formula" required>
                                  <option value="">Select</option>
                                  <?php foreach ($formulas as $key => $value) { ?>
                                   <option value="<?php echo $value->id ;?>" <?php if(isset($editdata)&&($editdata->formula_id==$value->id)){echo "selected" ;} ?>><?php echo $value->formula?></option>
                               <?php  } ?>
                           </select>
                       </div>
                      <!-- <div class=" col-md-3">
                        <label class="form-label">Sequence*</label>
                         <input type="number" name="sequence" class="form-control" id="sequence" value="<?php if(isset($editdata)){echo $editdata->sequence;}?>"> 
                           
                       </div> -->
                     
                    <div class=" col-12" id="parameterContainer">
                    <?php if(isset($editdata) ) {
                            //print_r($parameters);
                            ?>
                            <h5 class="text-info mt-4 mb-3">Set Default Value Parameters</h5>
                    <table class="table table-striped table-hover table-bordered" id="parameterTable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Field Type</th>
                                        <th>Default Value</th>

                                        <th> <button type="button" class="btn btn-sm btn-info" onclick="fetchValidParams()">Add Row</button></th>
                                    </tr>
                                </thead>
                                <tbody>


                           <?php if($parameters){ foreach($parameters as $pv){ ?>

                            <tr class="align-middle" id="row_<?php echo $pv->parameter_id ?>">
                                    <td><strong><?php echo $pv->parameter_name?></strong><input type="hidden" name="parameter_id[]" value="<?php echo $pv->parameter_id ?>"></td>
                                    <td>

                                        <select class="form-select form-control-sm " id="field_type_<?php echo $pv->parameter_id ?>" name="fieldtype[]" onchange="changeFieldType('<?php echo $pv->parameter_id ?>')">
                                            <option value="text" <?php if($pv->field_type=='text'){ echo "selected" ;}?>>Text</option>
                                            <option value="number" <?php if($pv->field_type=='number'){ echo "selected" ;}?>  >Number</option>
                                            <option value="dropdown" <?php if($pv->field_type=='dropdown'){ echo "selected" ;}?> >Dropdown</option>
                                          <!--   <option value="switch" <?php if($pv->field_type=='switch'){ echo "selected" ;}?> >Switch</option> -->
                                        </select>
                                    </td>
                                <td id="input_container_<?php echo $pv->parameter_id ?>">
                                    <?php if ($pv->field_type == 'dropdown') { 
                                        $optionsArray = explode(',', $pv->options); // Convert comma-separated options into an array
                                    ?>
                                        <input type="hidden" name="options[]" value="<?php echo $pv->options; ?>" id="hiddenoptions_<?php echo $pv->parameter_id; ?>">

                                    <input type="hidden" name="dropdown_type[]" value="<?php echo $pv->dropdown_type; ?>" id="dropdownType_<?php echo $pv->parameter_id; ?>">
                                    <h6>Type:<span id="typehead_<?php echo $pv->parameter_id; ?>"><?php echo $pv->dropdown_type; ?></span></h6>
                                        <select class="form-select form-control-sm select2" id="dropdownOptions_<?php echo $pv->parameter_id; ?>" name="defaultValue[]" >
                                            <option value="">Select Option</option>
                                            <?php foreach ($optionsArray as $option) { ?>
                                                <option value="<?php echo trim($option); ?>" <?php echo ($pv->default_value == trim($option)) ? 'selected' : ''; ?>>
                                                    <?php echo trim($option); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <button type="button" id="editbutton_<?php echo $pv->parameter_id; ?>" class="btn btn-warning btn-sm mt-1" onclick="openEditModal(<?php echo $pv->parameter_id; ?>)">Edit Options</button>
                                    <?php } else{?>

                                         <input type="hidden" name="options[]" value="" id="hiddenoptions_<?php echo $pv->parameter_id; ?>">

                                    <input type="hidden" name="dropdown_type[]" value="" id="dropdownType_<?php echo $pv->parameter_id; ?>">

                                        <input type="<?php echo $pv->field_type ?>" class="form-control shadow-sm rounded-2" id="textinput_<?php echo $pv->parameter_id ?>"  value="<?php echo $pv->default_value ;?>" name="defaultValue[]" >
                                    <?php } ?>
                                 </td>
                                 <td>  <button type="button" class="btn btn-danger btn-sm mt-1" onclick="deleteRow('<?php echo $pv->parameter_id ?>')"><i class="fa fa-trash"></i></button></td>
                                  </tr>
                        <?php } } }?>
                    </tbody>
                </table>
                    </div>
                    <div class="form-group col-md-12 mb-0 mt-3">
                        <?php if(isset($editdata)){?>

                        <button type="submit" formaction="<?php echo base_url()?>Master/save_editprocessMapping" class="btn btn-info"><i class="fa fa-check"></i> Update </button>

                       <?php  }else{ ?>
                        <button type="submit" class="btn btn-info" ><i class="fa fa-check"></i> Save </button>
                    <?php }?>
                    </div>


                </form>

            </div>
        </div>
    </div>

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
                            <th>Layout Name</th>
                            <th>Process Group</th>
                            <th>Process Subgroup</th>
                            <th>Process Name</th>
                            <th>Parameter Name</th>
                           <!--  <th>Sequence</th> -->
                            <th>Is Default</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Sr.</th>
                            <th>Layout Name</th>
                            <th>Process Group</th>
                            <th>Process Subgroup</th>
                            <th>Process Name</th>
                            <th>Parameter Name</th>
                            <!-- <th>Sequence</th> -->
                             <th>Is Default</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $x=1; 
                        foreach($processMapping as $value)
                        { 
                            ?>
                            <tr>
                                <td><?php echo $x; ?></td>
                                <td><?php echo $value->layoutName; ?></td>
                                <td><?php echo $value->groupName; ?></td>
                                <td><?php echo $value->subgroup_name; ?></td>
                                <td><?php echo $value->process_name ;?></td>
                                <td><?php echo $value->parameters ;?></td>
                              <!--   <td><?php echo  $value->sequence ;?></td> -->
                                 <td><?php echo $value->is_default ;?></td>
                                <td class="text-center">
                                <a href="<?php echo base_url().'Master/editprocessMapping/'.base64_encode($value->product_type_id).'/'.base64_encode($value->id)?>" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                                   <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_processMapping/'.base64_encode($value->id).'/'.base64_encode($value->product_type_id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> 
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
</main>
<?php 
$this->load->view('include/footer');
?>
<!-- Modal for Editing Options -->
<div class="modal fade" id="editOptionsModal" tabindex="-1" aria-labelledby="editOptionsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOptionsModalLabel">Edit Options</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h6>Options (comma separated):</h6>
        <textarea id="editOptionsTextarea" class="form-control" rows="4" placeholder="Enter options (comma separated)"></textarea>
        <div class="col-md-12">
            <label>Dropdown Type</label>
            <select class="form-control" name="edit_dropdownType" id="edit_dropdownType">
                <option value="Single">Single</option>
                <option value="Multiple">Multiple</option>
            </select>
        </div>
        <div class="mt-3">
          <button class="btn btn-primary" onclick="saveOptions()">Save Options</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
   var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
   var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

$(document).ready(function() {
     $('.select2').select2();
    <?php if (isset($editdata)) { ?>
        <?php if (empty($editdata->process_subgroup_id)) { ?> 
            getsubgroup('<?php echo $editdata->process_subgroup_id; ?>', '<?php echo $editdata->process_id; ?>'); 
        <?php } else { ?>
            getsubgroup('<?php echo $editdata->process_subgroup_id; ?>');
            
            setTimeout(function() {
                fetchProcess('<?php echo $editdata->process_id; ?>', '<?php echo $editdata->process_subgroup_id; ?>');
            }, 100);
        <?php } ?>
      
     
    <?php } ?>
});

function funValid() {
    var table = $("#parameterTable"); // Get the table
    if (table.length > 0) { // Check if the table exists
        var tableRows = table.find("tbody tr"); 
        if (tableRows.length == 0) { // If no rows exist
            alert("At least one Parameter Required");
            return false;
        }
    } 

    return true;
}



function  getsubgroup(selected=null,selectedProcess=null)
   
   {   

    var group=$("#process_group").val();
    

    $.ajax({
        url: '<?= base_url("Master/getProcessSubgroupByGroup"); ?>',
        type: 'POST',
        data: {
            group: group,
            [csrfName]: csrfHash 
        },
        dataType:'json',
        success: function(response) {


            $('#csrf_token').val(response.csrfHash);

            $("#process_subgroup").html('<option value="">select</option>'+response.subgroup);
            $("#process").html('<option value="">select</option>'+response.process);
            if(selected)
            {
                 $("#process_subgroup").val(selected);
            }
            if(selectedProcess)
            {
                  $("#process").val(selectedProcess);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}

function fetchProcess(selectedprocess=null,subgroup=null)
{
    var group=$("#process_group").val();
     
    if (!subgroup) {
        subgroup = $("#process_subgroup").val();
    }


    $.ajax({
        url: '<?= base_url("Master/fetchProcess"); ?>',
        type: 'POST',
        data: {
            group: group,
            subgroup:subgroup,
            [csrfName]: csrfHash 
        },
        dataType:'json',
        success: function(response) {
            //console.log(response.process);

           $('#csrf_token').val(response.csrfHash);

           $("#process").html('<option value="">select</option>'+response.process);

           if(selectedprocess){
            $("#process").val(selectedprocess);
            // showParameters();
           }
            

       },
       error: function(xhr, status, error) {
        console.error("AJAX Error:", status, error);
    }
});

}



function createTable(validParams) {


    let fieldHtml = `<h5 class="text-info mt-4 mb-3">Set Default Value Parameters</h5>`;
    fieldHtml += `
        <table class="table table-striped table-hover table-bordered" id="parameterTable">
            <thead class="thead-dark">
                <tr>
                    <th>Parameter</th>
                    <th>Field Type</th>
                    <th>Default Value</th>
                    <th><button class="btn btn-sm btn-info" type="button" data-params='${JSON.stringify(validParams)}' onclick="addRow(this)"><i class="fa fa-plus"></i></button></th>
                </tr>
            </thead>
            <tbody>
    `;

    // Create rows for each valid parameter
    validParams.forEach(param => {
        fieldHtml += createRow(param);  // Use createRow function to generate each row
    });

    fieldHtml += `</tbody></table>`;

    // Append the table HTML to the container
    document.getElementById('parameterContainer').innerHTML = fieldHtml;
     $('.select2').select2();
}

function createRow(param) {
    const inputType = param.field_type || 'text';
    const label = param.parameter_name;
    const fieldId = 'param_' + param.id;


    let rowHtml = `
        <tr class="align-middle" id="row_${param.id}">
            <td><strong>${label}</strong><input type="hidden" name="parameter_id[]" value="${param.id}"></td>
            <td>
                ${createSelectField(param)}  
            </td>
            <td id="input_container_${param.id}">
                ${createInputField(param)}  
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm mt-1" onclick="deleteRow(${param.id})"><i class="fa fa-trash"></i></button>
            </td>
        </tr>
    `;
    return rowHtml;
}


function createSelectField(param) {
    const inputType = param.field_type || 'text';
    return `
        <select class="form-select form-control-sm" id="field_type_${param.id}" name="fieldtype[]" onchange="changeFieldType(${param.id})">
            <option value="text" ${inputType === 'text' ? 'selected' : ''}>Text</option>
            <option value="number" ${inputType === 'number' ? 'selected' : ''}>Number</option>
            <option value="dropdown" ${inputType === 'dropdown' ? 'selected' : ''}>Dropdown</option>
        </select>
    `;
}


function createInputField(param) {
    const inputType = param.field_type || 'text';

    let inputHtml = '';
    
    if (inputType === 'dropdown' && param.options) {
        const optionsArray = param.options.split(',').map(option => option.trim());
        const safeValue = param.options.replace(/"/g, '&quot;');
        inputHtml += `
            <input type="hidden" name="options[]" value="${safeValue}" id="hiddenoptions_${param.id}">
            <input type="hidden" name="dropdown_type[]" value="${param.dropdown_type}" id="dropdownType_${param.id}">
            <h6>Type:<span id="typehead_${param.id}">${param.dropdown_type}</span></h6>
            <select class="form-select form-control-sm select2" id="dropdownOptions_${param.id}" name="defaultValue[]" >
                <option value="">Select Option</option>
        `;
        
        optionsArray.forEach(option => {
            inputHtml += `<option value="${option}" ${param.value === option ? 'selected' : ''}>${option}</option>`;
        });

        inputHtml += `</select>
            <button type="button" id="editbutton_${param.id}" class="btn btn-warning btn-sm mt-1" onclick="openEditModal(${param.id})">Edit Options</button>
        `;
    } else {
        
        inputHtml += `<input type="${inputType}" class="form-control shadow-sm rounded-2" id="textinput_${param.id}" value="" name="defaultValue[]">
         <input type="hidden" name="options[]" value="" id="hiddenoptions_${param.id}">
            <input type="hidden" name="dropdown_type[]" value="" id="dropdownType_${param.id}">`;
    }

    return inputHtml;
}

function deleteRow(paramId) {
    const row = document.getElementById('row_' + paramId);
    if (row) {
        row.remove();
    }
}


function showParameters(mode = null, callback = null) {
    const selectProcess = $("#process").val();
    //console.log(mode);
    
    if (selectProcess) {
        $.ajax({
            url: '<?= base_url("Master/fetchParametersByProcess"); ?>',
            type: 'POST',
            data: {
                processid: selectProcess,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                $('#csrf_token').val(response.csrfHash);
               

                if (response.parametersdetails && response.parametersdetails.length > 0) {
                    const paramList = response.parametersdetails;
                    const validParams = paramList.filter(param =>
                        param.parameter_ids !== null && param.parameter_ids !== ''
                    );



                    if (validParams.length > 0) {
                         if (mode === 'edit') {
                            if (callback) {
                                callback(validParams);  // Pass validParams to the callback
                            }
                        }
                        else{
                             $('#parameterContainer').empty();  

                             createTable(validParams);
                        }
                         
                    }
                }
                  $('.select2').select2();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
        
    } else {
        alert("First Select Process");
    }
}


function fetchValidParams()
{
    //var validParams=showParameters('edit');

   showParameters('edit', function(validParams) {
     
        addRow(null, validParams);  
    });
   
}
function addRow(button,validParams) {
   
    var params = validParams || JSON.parse(button.getAttribute('data-params'));  
    

    const existingIds = [];


    $('#parameterTable tbody tr').each(function() {
       
        const rowId = $(this).find('input[name="parameter_id[]"]').val(); 
      
        existingIds.push(rowId); 
    });

    
 // const missingParams = params.filter(param => !existingIds.includes(param.id.toString()));

  const missingParams = existingIds.length === 0 
        ? params  
        : params.filter(param => !existingIds.includes(param.id.toString()));

  

    if (missingParams.length > 0) {


        
        openAddParameterModal(missingParams);

    } else {
        alert('All parameters are already added to the table.');
    }
}

/*function openAddParameterModal(missingParams) {
  
    const encodedParams = encodeURIComponent(JSON.stringify(missingParams));
   // console.log(encodedParams);
$('#addParameterModal').remove();
    const modalHtml = `
        <div class="modal fade" id="addParameterModal" tabindex="-1" aria-labelledby="addParameterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addParameterModalLabel">Add Parameters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${missingParams.map(param => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${param.id}" id="paramCheckbox_${param.id}">
                                <label class="form-check-label" for="paramCheckbox_${param.id}">
                                    ${param.parameter_name}
                                </label>
                            </div>
                        `).join('')}
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"  data-params='${JSON.stringify(missingParams)}' onclick="addSelectedParameters(this)">Add Selected</button>
                    </div>
                </div>
            </div>
        </div> `;
    
    $('body').append(modalHtml);
    $('#addParameterModal').modal('show');
}



function addSelectedParameters(button) {
    //console.log(button);
 const missingParams = JSON.parse(button.getAttribute('data-params'));

   // const missingParams = JSON.parse(jsonData);
    const selectedIds = Array.from($('#addParameterModal input[type="checkbox"]:checked')).map(input => input.value);
    const selectedParams = missingParams.filter(param => selectedIds.includes(param.id.toString()));

   // console.log(selectedParams);
     selectedParams.forEach(param => {
        const rowHtml = createRow(param);

        $('#parameterTable tbody').append(rowHtml);
         $('.select2').select2();
    });

    
    $('#addParameterModal').modal('hide');
    $('#addParameterModal').remove(); // Clean up modal after use
}*/


function openAddParameterModal(missingParams) {

    // Remove old modal if exists
    $('#addParameterModal').remove();

    const modalHtml = `
        <div class="modal fade" id="addParameterModal" tabindex="-1" aria-labelledby="addParameterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addParameterModalLabel">Add Parameters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ${missingParams.map(param => `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${param.id}" id="paramCheckbox_${param.id}">
                                <label class="form-check-label" for="paramCheckbox_${param.id}">
                                    ${param.parameter_name}
                                </label>
                            </div>
                        `).join('')}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addSelectedBtn">Add Selected</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append modal to body
    const $modal = $(modalHtml).appendTo('body');

    // Attach JSON safely using jQuery .data()
    $modal.data('params', missingParams);

    // Show modal
    $modal.modal('show');

    // Attach click event
    $modal.find('#addSelectedBtn').on('click', function () {
        addSelectedParameters($modal);
    });
}


function addSelectedParameters($modal) {
    const missingParams = $modal.data('params'); // Retrieve JSON object safely

    // Get selected checkboxes
    const selectedIds = Array.from($modal.find('input[type="checkbox"]:checked')).map(input => input.value);

    // Filter selected parameters
    const selectedParams = missingParams.filter(param => selectedIds.includes(param.id.toString()));

    // Append rows
    selectedParams.forEach(param => {
        const rowHtml = createRow(param); // Your existing function
        $('#parameterTable tbody').append(rowHtml);
        $('.select2').select2();
    });

    // Hide and remove modal
    $modal.modal('hide');
    $modal.remove();
}

function deleteRow(paramId) {
    $(`#row_${paramId}`).remove();
}

function changeFieldType(paramId) {
    var fieldType = $(`#field_type_${paramId}`).val();
    var container = $(`#input_container_${paramId}`); 
    container.empty(); 

    if (fieldType == 'dropdown') {
        //Fetch options from the server/database
        $.ajax({
            url: '<?= base_url("Master/getParameterById"); ?>',  // URL to fetch options dynamically
            type: 'POST',
            data: {
                id: paramId,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
              
               var optionsHtml='';
                if (response.parameter && response.parameter.options ) {
                    let optionsArray = response.parameter.options.split(',').map(option => option.trim());
                 
                     optionsHtml += `<input type="hidden" name="options[]"  id="hiddenoptions_${paramId}" value="${response.parameter.options}">`;
                     optionsHtml +=`<input type="hidden" name="dropdown_type[]" value="${response.parameter.dropdown_type}" id="dropdownType_${paramId}">`;
                     optionsHtml += '<select class="form-select form-control-sm" id="dropdownOptions_' + paramId + '" name="defaultValue[]">';
                    optionsHtml += '<option value="">Select Option</option>';

                    optionsArray.forEach(function(option) {
                        optionsHtml += `<option value="${option}">${option}</option>`;
                    });
                    optionsHtml += '</select>';

                   
                }else{
                    var optionsHtml = '<select class="form-select form-control-sm" id="dropdownOptions_' + paramId + '" name="defaultValue[]">';
                    optionsHtml += '<option value="">Select Option</option></select>';
                    optionsHtml += `<input type="hidden" name="options[]" value="" id="hiddenoptions_${paramId}" >`;
                    optionsHtml +=`<input type="hidden" name="dropdown_type[]" value="Single" id="dropdownType_${paramId}">`;

                }

               optionsHtml += '<button type="button" id="editbutton_' + paramId + '" class="btn btn-warning btn-sm mt-1" onclick="openEditModal(' + paramId + ')">Edit Options</button>';
                   

                 container.append(optionsHtml); 
            },
            error: function(xhr, status, error) {
                console.error("Error fetching dropdown options:", status, error);
            }
        });

    }  else {
        container.append(`
            <input type="${fieldType}" class="form-control shadow-sm rounded-2" id="textinput_${paramId}" name="defaultValue[]">
             <input type="hidden" name="options[]" value="" id="hiddenoptions_${paramId}">
            <input type="hidden" name="dropdown_type[]" value="" id="dropdownType_${paramId}">
        `);
    }
}

let currentParamId = null;

function openEditModal(paramId) {
    currentParamId = paramId;
    
 
    const dropdown = $(`#dropdownOptions_${paramId}`);
    const optionsArray = [];

    dropdown.find('option').each(function() {
        if ($(this).val() !== "") {
            optionsArray.push($(this).text().trim());
        }
    });
    const optionsString = optionsArray.join(',');
   
    $('#editOptionsTextarea').val(optionsString);

   
      var dropdowntype=$("#dropdownType_"+currentParamId).val();
   
      $("#edit_dropdownType").val(dropdowntype);
    
   
    $('#editOptionsModal').modal('show');
}
function saveOptions() {
    const newOptions = $('#editOptionsTextarea').val();
    var dropdowntype=$("#edit_dropdownType").val();
    const optionsArray = newOptions.split(',').map(option => option.trim());
    //console.log(newOptions);

    $("#hiddenoptions_"+currentParamId).val(newOptions);
    $("#dropdownType_"+currentParamId).val(dropdowntype);
    $("#typehead_"+currentParamId).text(dropdowntype);
    const dropdown = $(`#dropdownOptions_${currentParamId}`);
    dropdown.empty(); 
    dropdown.append('<option value="">Select Option</option>');  // Default option

  
    optionsArray.forEach(option => {
        dropdown.append(`<option value="${option}">${option}</option>`);
    });

    
    $('#editOptionsModal').modal('hide');
}



</script>