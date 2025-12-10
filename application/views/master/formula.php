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
                        <div class="col-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">  <?php if(isset($editdata)) { ?>
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Edit Formula</h5>
                                    <?php } else { ?>
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Add Formula</h5>
                                    <?php } ?>


                                </h5>
                            </div>
                            <div class="card-body">
                                <form class="form-group row" role="form" action="<?php echo base_url('Master/save_Formula') ?>" method="post">
                                    <input type="hidden" name="id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->id; ?>" <?php } ?>>
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <div class=" col-md-12">
                                        <label class="form-label">Code*</label>
                                        <input type="text" name="code" class="form-control"  placeholder="Enter  Code" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->code; ?>" <?php } ?> autocomplete="off" required <?php if(isset($editdata)){ echo "readonly" ;}?>>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">First Operand*</label>
                                        <input type="text" name="firstoprand" id="firstoprand" class="form-control"   value="<?php if(isset($editdata)){echo $editdata->first_operand; }else{ echo "SO.qty" ;} ?>" autocomplete="off" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Operator*</label>
                                       <select class="form-control" name="operator" id="operator"> 
                                          <option value="">Select</option>
                                           <option value="+" <?php if(isset($editdata) && trim($editdata->operator) === '+'){ echo "selected"; }?>>+</option>
                                            <option value="-" <?php if(isset($editdata) && trim($editdata->operator) === '-'){ echo "selected"; }?>>-</option>
                                            <option value="*" <?php if(isset($editdata) && trim($editdata->operator) === '*'){ echo "selected"; }?>>*</option>
                                            <option value="/" <?php if(isset($editdata) && trim($editdata->operator) === '/'){ echo "selected"; }?>>/</option>
                                       </select>
                                    </div>
                               
                                  
                                     <div class="col-md-8" id="parameterField">
                                        <label class="form-label">Parameter</label>
                                       <select class="form-control" name="parameter" id="parameter"> 
                                        <option value="">Select</option>
                                          <?php foreach($parameters as $value){ ?>
                                            <option value="<?php echo $value->id?>" data-pname="<?php echo $value->parameter_name ?>"
                                             <?php if(isset($editdata)&& $editdata->parameter==$value->id ){echo "selected";}?>>
                                             <?php echo $value->parameter_name ?></option>
                                          <?php }?>
                                       </select>
                                    </div>

                                     <div class="col-md-8"  style="display:none;" id="numberField">
                                        <label class="form-label">Number</label>
                                        <input type="number" class="form-control" min="0" name="secondoprand" id="number" value="<?php if(isset($editdata)){echo $editdata->number ;}?>">
                                    </div>

                                     <div class="form-check col-md-4 mt-4 ">
                                          <input class=" form-check-input ms-1" type="checkbox" value="Y" id="is_number" name="is_number" style="transform: scale(1.2);" <?php if(isset($editdata) &&($editdata->is_number=='Y')){echo "checked" ;}?>>
                                          <label class="form-check-label ms-2" for="is_number">
                                           Is Number?
                                          </label>
                                    </div>

                                    <div class="col-md-4 mt-3">
                                        <button type="button" class="btn btn-sm btn-success" onclick="fetchFormula()">Fetch Formula </button>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Formula*</label>
                                        <input type="text" class="form-control" name="formulashow" id="formulashow" value="<?php if(isset($editdata)){ echo $editdata->formula ; }?>" readonly required>
                                    </div>


                                    <div class="col-md-6">
                                        <label class="form-label">Active*</label>
                                        <select name="status" class="form-control">
                                            <option value="ACTIVE" <?php if(isset($editdata) && $editdata->status=='ACTIVE') { echo "selected"; } ?>>ACTIVE</option>
                                            <option value="INACTIVE" <?php if(isset($editdata) && $editdata->status=='INACTIVE') { echo "selected"; } ?>>INACTIVE</option>
                                        </select>
                                    </div>
                                    
                                
                                    <div class="form-group col-md-12 mb-0 mt-3">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save <?php if(isset($editdata)) { echo "Changes"; } ?></button>
                                        <?php if(isset($editdata)) { ?>
                                            <a href="<?php echo base_url(); ?>Master/formulaMaster"><input type="button" class="btn btn-secondary ml-2" value="Back"></a>
                                        <?php } else { ?>
                                        <?php } ?>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="col-7">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Process Group</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered" id="datatable_index">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th>Code</th>
                                            <th>Formula</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                              <th>Sr.</th>
                                            <th>Code</th>
                                            <th>Formula</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $x=1; 
                                        foreach($formulas as $value)
                                        { 
                                            ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $value->code; ?></td>
                                          
                                                  <td><?php echo $value->formula ;?></td>
                                                <td><?php if($value->status=='ACTIVE') { echo "ACTIVE"; } else { echo "INACTIVE"; } ?></td>
                                                <td class="text-center">
                                                   <a href="<?php echo base_url().'Master/editFormulaMaster/'.base64_encode($value->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

                                                 <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/removeformula/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> 
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
   <script>
    // jQuery to toggle visibility of parameter and number fields based on checkbox
    document.getElementById('is_number').addEventListener('change', function() {
        var isChecked = this.checked;
        var parameterField = document.getElementById('parameterField');
        var numberField = document.getElementById('numberField');
        var parameterSelect = document.getElementById('parameter');
        var numberInput = document.getElementById('number');

        // If checkbox is checked, hide the parameter field and show the number input
        if (isChecked) {
            // Hide the parameter field and show the number input
            parameterField.style.display = 'none';
            numberField.style.display = 'block';

            // Make the number input field required and remove the required from parameter select
            numberInput.setAttribute('required', 'required');
            parameterSelect.removeAttribute('required');
        } else {
            // If checkbox is unchecked, show the parameter field and hide the number input
            parameterField.style.display = 'block';
            numberField.style.display = 'none';

            // Make the parameter select field required and remove the required from number input
            parameterSelect.setAttribute('required', 'required');
            numberInput.removeAttribute('required');
        }
    });
    function fetchFormula() {
        // Get values from the form
        var operator = document.getElementById('operator').value;
        var parameter = document.getElementById('parameter').value;
        var number = document.getElementById('number').value;
        var formulashow = document.getElementById('formulashow');
        var firstoprand=document.getElementById('firstoprand').value;
        
        // If "Is Number?" is checked, use number in formula
        if (document.getElementById('is_number').checked) {
            if (number && operator) {
                formulashow.value = firstoprand +'' + operator + ' ' + number;
            } else {
                alert('Please provide an operator and a number.');
            }
        } else {
            // Otherwise, use parameter in formula
            if (parameter && operator) {

                formulashow.value = firstoprand +' ' + operator + ' ' + document.getElementById('parameter').options[document.getElementById('parameter').selectedIndex].text;
            } else {
                alert('Please provide an operator and a parameter.');
            }
        }
    }
</script>
