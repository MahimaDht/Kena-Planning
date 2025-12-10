<?php
$this->load->view('include/header');
?>
<style type="text/css">
    
.rawMaterialDropdown
    {
        width: 200px;
    }
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
                                                <th rowspan="2" width="2%">sr.no</th>
                                                <th rowspan="2" width="2%">Item Code</th>
                                                <th rowspan="2" width="20%">Item Name</th>
                                                <th rowspan="2" width="5%">Size</th>
                                                <th rowspan="2"  width="5%">Guage </th>
                                               
                                                <th colspan="3" style="text-align: center;" width="60%">Row Material</th>
                                               
                                                <th  width="2%" rowspan="2">Action</th>
                                           </tr>
                                            <tr>
                                               
                                                 <th width="20%">1 UPS</th>
                                                <th width="20%">2 UPS</th>
                                                <th width="20%">4 UPS</th>


                                            </tr>
                                            <!-- end tr -->
                                        </thead>
                                        <?php
                                        $x=1;
                                        foreach ($products as $key => $value) { 

                                            $mapped = null;
                                            foreach ($map as $m) {
                                                if ($m->item_code == $value->item_code) {
                                                    $mapped = $m;
                                                    break;
                                                }
                                            }
                                            ?>
                                         <tr>
                                            <td  style="width:2%"><?php echo $x ?></td>
                                            <td style="width:5%">
                                             <input type="hidden" name="item_code " id="item_code_<?php echo $x?>" value="<?php echo $value->item_code ?>">
                                                <?php echo $value->item_code ?></td>
                                            <td style="width:20%"><?php echo $value->item_name ?></td>
                                            <td style="width:5%"><?php echo $value->u_ait_prod_size ?></td>
                                            <td style="width:5%"><?php echo $value->u_ait_guage ?></td>
                                          
                                            <td style="width:20%">
                                                <select class="form-select rawMaterialDropdown select2" name="singleUPSmaterial" id="singleUPSraw_<?php echo $x?>">
                                                       <option value="">Select</option>
                                                    <?php if ($mapped && $mapped->singleupsMaterial): ?>
                                                        <option value="<?= $mapped->singleupsMaterial ?>" selected>
                                                            <?= $mapped->singleUPSmaterial_name ?>
                                                        </option>
                                                    <?php endif; ?>
                                                    
                                                 
                                                </select>
                                            </td>
                                            <td style="width:20%"> 
                                                <select class="form-select rawMaterialDropdown select2" name="doubleUPSmaterial " id="doubleUPSraw_<?php echo $x?>" >
                                                    <option value="">Select</option>
                                                     <?php if ($mapped && $mapped->doubleupsMaterial): ?>
                                                        <option value="<?= $mapped->doubleupsMaterial ?>" selected>
                                                            <?= $mapped->doubleUPSmaterial_name ?>
                                                        </option>
                                                    <?php endif; ?>

                                                  
                                                </select>
                                            </td>
                                             <td style="width:20%">
                                                 <select class="form-select rawMaterialDropdown select2" name="fourUPSmaterial" id="fourUPSraw_<?php echo $x?>">
                                                    <option value="">Select</option>
                                                    <?php if ($mapped && $mapped->fourupsMaterial): ?>
                                                        <option value="<?= $mapped->fourupsMaterial ?>" selected>
                                                            <?= $mapped->fourUPSmaterial_name ?>
                                                        </option>
                                                    <?php endif; ?>

                                                  
                                                </select>
                                             </td>
                                             <td style="width:5%">
                                                 
                                                <button type="button" class="btn btn-primary btn-sm" onclick="submitrow(<?php echo $x; ?>)" >Submit</button>

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


<script >
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

var base_url='<?php echo base_url() ?>';

$(document).ready(function () {
      $('#datatable_index1').DataTable({                                                     
       
    });


  //  initializeSelect2(".rawMaterialDropdown");

  $(document).on('focus', '.rawMaterialDropdown', function () {
        if (!$(this).data("select2-initialized")) {
          

            initializeSelect2(this);
            $(this).data("select2-initialized", true); // mark as initialized
        }
    });

});

function initializeSelect2(elementSelector) {
    $(elementSelector).select2({
        placeholder: "-Select Item-",
        allowClear: true,
        minimumInputLength: 1,
        width: "200px",
        ajax: {
            url: base_url + "Master/getRawMaterial",
            type: "GET",
           
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    term: params.term 
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            id: item.code,   // <-- value
                            text: item.name  // <-- displayed text
                        };
                    })
                };
            },
            cache: true
        }
    });
}


 function submitrow(x) {
 var base_url='<?php echo base_url();?>';

    
        var item_code=document.getElementById('item_code_'+ x).value;
      //  var id = document.getElementById('id_' + x).value;
        var ups1material=document.getElementById('singleUPSraw_'+ x).value;
        var ups2material=document.getElementById('doubleUPSraw_'+ x).value;
        var ups4material=document.getElementById('fourUPSraw_'+ x).value;
        
  
        if(ups1material=='' || ups2material ==''  ||ups4material =='' ){
       //  e.preventDefault();
           alert("Please Select Material");
       }else{

   
        $.ajax({
         type: 'POST',
        url: base_url+'Master/saveProductWithRawMaterial', 
        data: {
           item_code:item_code,
       //    id:id, data:{  [csrfName]: csrfHash },
       [csrfName]: csrfHash ,
           singleUPSmaterial:ups1material,
            doubleUPSmaterial:ups2material,
             fourUPSmaterial:ups4material
            
        },
        success: function(response) {
           
            location.reload();
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.error(error);
        }
    });
    }
}
</script>

