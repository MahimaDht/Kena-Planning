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
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0"><?=$title;?></h5>
                                        <?php if($urldata->writep==1) {  ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_process">
                                        <i class="fa fa-plus"></i> Add New
                                        </button>
                                        <?php } ?>
                                    </div>
                                </div>
                                 <div class="card-body">
                                    <table class="table table-bordered" id="datatable_index">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>sr.no</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Field Type</th>
                                                <th>Active</th>
                                                <th class="rounded-end">Action</th>
                                            </tr>
                                            
                                        </thead>
                                        <?php
                                        $x=1;
                                        foreach ($parameters as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $x ?></td>
                                            <td><?php echo $value->code ?></td>
                                            <td><?php echo $value->parameter_name ?></td>
                                            <td><?php echo $value->field_type ?></td>
                                            <td><?php echo $value->status ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#edit_parameter" onclick="editFunc('<?php echo $value->id; ?>')" ><i class="fa fa-edit"></i>
                                                </button>
                                                
                                                <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_parameter/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php $x++ ;} ?>
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


<script >
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';




     function editFunc(id)

    {

        $("#edit_id").val('');

        $("#edit_status").val('');



        $("#edit_code").val('');

        $("#edit_name").val('');

        $("#edit_field_type").val('');

        $("#edit_dropdownCount").val('');
        $("#edit_dropdownConfig").css("display","none");
        $("#edit_dropdown_type").val();

        $('input[name="dropdownOptions[]"]').each(function () {
            $(this).val('').removeAttr('required');
        });
     



        var base_url = "<?php echo base_url(); ?>";

        $.ajax({

            url: base_url + 'Master/getParameterById',

            type: 'post',

            data:{id:id  , [csrfName]: csrfHash },

            dataType: 'json',

            success:function(response) 

            {
              
                $('#csrf_token').val(response.csrfHash);
                $("#id").val(response.parameter.id);

                $("#edit_status").val(response.parameter.status);

                $("#edit_code").val(response.parameter.code);

                $("#edit_name").val(response.parameter.parameter_name);

                $("#edit_field_type").val(response.parameter.field_type);
                $("#edit_dropdown_type").val(response.parameter.dropdown_type);
                 handleFieldTypeChange('edit');
                if(response.parameter.field_type=='dropdown')
                {
                    

                     const options = response.parameter.options ? response.parameter.options.split(',') : [];

                     $("#edit_dropdownCount").val(response.parameter.dropdown_value);
                    dropdownoptions(response.parameter.dropdown_value, 'edit',options);
                }  

            }

        });



    }


    function handleFieldTypeChange(mode) {
        const isEdit = (mode === 'edit');

        const fieldType = document.getElementById(isEdit ? 'edit_field_type' : 'field_type').value;
       
        const dropdownSection = document.getElementById(isEdit ? 'edit_dropdownConfig' : 'dropdownConfig');
        const dropdownCount = document.getElementById(isEdit ? 'edit_dropdownCount' : 'dropdownCount');
        const container = document.getElementById(isEdit ? 'edit_dropdownValuesContainer' : 'dropdownValuesContainer');
        const dropdownType=document.getElementById(isEdit ? 'edit_dropdownType' :'dropdownType');

       
      
        dropdownSection.style.display = (fieldType === 'dropdown') ? 'block' : 'none';
        dropdownType.style.display= (fieldType === 'dropdown'  ) ? 'block':'none';
       
 
        if (fieldType === 'dropdown') {
            dropdownCount.setAttribute('required', 'required');
            dropdownType.setAttribute('required','required');
        } else {
            dropdownCount.removeAttribute('required');
             dropdownType.removeAttribute('required');
           
            const inputs = container.querySelectorAll('input[name="dropdownOptions[]"]');
            inputs.forEach(input => input.removeAttribute('required'));
        }


    }


   function dropdownoptions(inputvalue,type,options=null){
    
      const count = parseInt(inputvalue);
      if(type=='edit')
      {
           var container = document.getElementById('edit_dropdownValuesContainer');
           
      }
      else{
         var container = document.getElementById('dropdownValuesContainer');
      }
    
      container.innerHTML = '';
      
      for (let i = 1; i <= count; i++) {

        const div = document.createElement('div');
        div.classList.add('dropdown-input-container', 'd-flex', 'align-items-center', 'mb-2');

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = `Option ${i}`;
        input.name = `dropdownOptions[]`;
        input.className = 'form-control mt-2';

         if (options && options[i - 1]) {
            input.value = options[i - 1].trim(); 
        }
//////////////////////////////////////////////////////////////////
          const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.classList.add('btn', 'btn-danger', 'btn-sm','mt-2','ms-2');
        deleteButton.innerHTML = '<i class="fa fa-trash"></i>';
        
        deleteButton.onclick = function() {
           
            div.remove();
            updateDropdownCount(type);
        };
/////////////////////////////////////////////////
        div.appendChild(input);
        div.appendChild(deleteButton);
        container.appendChild(div);
      }
       updateDropdownCount(type);
    }


    function updateDropdownCount(type) {
    const count = type === 'edit'
        ? document.querySelectorAll('#edit_dropdownValuesContainer input').length
        : document.querySelectorAll('#dropdownValuesContainer input').length;

  
    const dropdownCountInput = type === 'edit'
        ? document.getElementById('edit_dropdownCount')
        : document.getElementById('dropdownCount');
    
    dropdownCountInput.value = count;
}

function addDropdownOption(type) {
    const container = type === 'edit'
        ? document.getElementById('edit_dropdownValuesContainer')
        : document.getElementById('dropdownValuesContainer');

    const div = document.createElement('div');
    div.classList.add('dropdown-input-container', 'd-flex', 'align-items-center', 'mb-2');
    
    const input = document.createElement('input');
    input.type = 'text';
    input.placeholder = 'New Option';
    input.name = 'dropdownOptions[]';
    input.className = 'form-control me-2'; 
    input.required = true;

    // Create delete button
    const deleteButton = document.createElement('button');
    deleteButton.type = 'button';
    deleteButton.classList.add('btn', 'btn-danger', 'btn-sm');
    deleteButton.innerHTML = '<i class="fa fa-trash"></i>';

    deleteButton.onclick = function() {
        // Remove the input and update the dropdown count
        div.remove();
        updateDropdownCount(type);
    };

    // Append input and delete button to the div
    div.appendChild(input);
    div.appendChild(deleteButton);
    container.appendChild(div);

    // Update the dropdown count after adding an option
    updateDropdownCount(type);
}

</script>

<div class="modal fade" id="add_process" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Add Parameter</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="<?php echo base_url()?>Master/saveAddParameter" method="post">
                <div class="modal-body">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    
                    <div class="col-md-12">
                        <label class="form-label">Code *</label>
                        <input type="text" name="code" id="code" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Name*</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Choose Field Type*</label>
                       <select class="form-control" name="field_type" id="field_type"  onchange="handleFieldTypeChange('create')" required>
                            <option value="">Select</option>
                              <option value="text">Text</option>
                              <option value="number">Number</option>
                              <option value="dropdown">Dropdown</option>
                             <!--  <option value="switch">Toggle Switch (Yes/No)</option> -->
                       </select>
                    </div>
                    
                    <div id="dropdownConfig" class="col-md-12 bg-light p-3 border rounded-3 mb-3 mt-2" style="display: none;">
                      <label class="form-label">How many dropdown values?</label>
                      <div class="d-flex">
                          <input type="number" id="dropdownCount" name="dropdownvalue" min="1"class="form-control" oninput="dropdownoptions(this.value,'create')">

                          <button type="button" class="btn btn-success btn-sm ms-2" onclick="addDropdownOption('create')"><i class="fa fa-plus"></i> </button>
                         </div>
                      <div id="dropdownValuesContainer" class="col-md-12 "></div>

                    </div>
                    <div class="col-md-12" id="dropdownType" style="display:none">
                          <label class="form-label">Dropdown Type</label>
                          <select class="form-control" name="dropdown_type" id="dropdown_type">
                            <option value ="Single">Single</option>
                            <option value ="Multiple">Multiple</option>
                          </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

  <!------------------------ edit modal --------------------------------------------------->
<div class="modal fade" id="edit_parameter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Edit Parameter</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="<?php echo base_url()?>Master/saveEditParameter" method="post">
                <div class="modal-body">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <input type="hidden" name="id"  class="form-control" id="id">
                  
                    <div class="col-md-12">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required readonly>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                   <div class="col-md-12">
                        <label class="form-label">Choose Field Type</label>
                       <select class="form-control" name="field_type" id="edit_field_type" required onchange="handleFieldTypeChange('edit')">
                              <option value="text">Text</option>
                              <option value="number">Number</option>
                              <option value="dropdown">Dropdown</option>
                              <!-- <option value="switch">Toggle Switch (Yes/No)</option> -->
                       </select>
                    </div>

                    <div id="edit_dropdownConfig" class="col-md-12 bg-light p-3 border rounded-3 mb-3 mt-2" style="display: none;">
                      <label class="form-label">How many dropdown values?</label>
                      <div class="d-flex">
                      <input type="number" id="edit_dropdownCount" name="dropdownvalue" min="1"class="form-control" oninput="dropdownoptions(this.value,'edit')">

                      <button type="button" class="btn btn-success btn-sm ms-2" onclick="addDropdownOption('edit')"><i class="fa fa-plus"></i> </button>
                  </div>
                      <div id="edit_dropdownValuesContainer" class="col-md-12 "></div>
                    </div>
                     <div class="col-md-12" id="edit_dropdownType" style="display:none">
                          <label class="form-label">Dropdown Type</label>
                          <select class="form-control" name="dropdown_type" id="edit_dropdown_type">
                            <option class="Single">Single</option>
                            <option class="Multiple">Multiple</option>
                          </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>