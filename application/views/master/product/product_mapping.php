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
                        <div class="col-12">
                            <div class="card">
                              
                            <div class="card-body table-responsive">
                               <table class="table table-striped">
                                   <thead>
                                    <tr>
                                       <th>PT CODE</th>
                                       <th>Product Type Name</th>
                                       <th>Action</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                    <?php foreach ($product_type as $key => $value) { ?>
                                       <tr>
                                           <td><?php echo $value->product_code ;?></td>
                                           <td><?php echo $value->product_name ;?></td>
                                           <td>
                                               <button class="btn btn-sm btn-info" name="product_mapping" onclick="openModal('<?php echo $value->id ;?>')">Update Layout</button>

                                            <a class="btn btn-sm btn-primary" name="product_rawMaterial" href="<?php echo base_url()?>Master/productToRawMaterial/<?php echo base64_encode($value->id)?>" >Raw Material</a>
                                           </td>
                                       </tr>
                                   <?php }?>
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

       <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Update Product Type Layouts</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form class="form-group" action="<?php echo base_url()?>Master/updateProductMapping" method="post">
            <div class="modal-body">
                   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                <input type="hidden" name="id"  class="form-control" id="id">
               <h5>Product Type : <span id="product_type"></span></h5>
                <div class="row mt-3">
                    <?php foreach ($product_layout as $key => $value) { ?>
                     <div class="col-md-6">
                        <div class="form-check mb-3">
                            <input type="checkbox" id="layout_<?php echo $value->id ?>" name="layoutIds[]" value="<?php echo $value->id ?>" class="form-check-input">
                            <label class="form-check-label" for="layout_<?php echo $value->id ?>"><?php echo $value->layoutName ?></label>
                        </div>
                     </div>
                    <?php }?>
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
      

<script>

    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    function openModal(id) {
      
        $("#exampleModal").modal('show');
        $("#id").val(id);
        $("input[name='layoutIds[]']").prop('checked', false);

        $.ajax({
            url: '<?= base_url("Master/getproductTypeById"); ?>',
            type: 'POST',
            data: {
                id: id,
                [csrfName]: csrfHash 
            },
         dataType:'json',
            success: function(response) {
            
                $('#csrf_token').val(response.csrfHash);
             
               $("#product_type").text(response.product_type);

                  const layoutIds = response.layoutIds || [];
                    const layoutIdsArray = (typeof layoutIds === 'string') ? layoutIds.split(',') : layoutIds;

                    layoutIdsArray.forEach(function(layoutId) {
                        $('#layout_' + layoutId).prop('checked', true);
                    });
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }




</script>