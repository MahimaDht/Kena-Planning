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
                    <a href="<?php echo base_url()?>Transaction/salesorder" class="btn btn-info" style="float: right;"><i class="fa fa-list"></i> Sales List</a>

                    <h1 class="h3 mb-3">Sales Order Items</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">

                                   <table class="table table-striped" id="example123" width="100%">
                                       <thead>
                                        <tr>
                                           <th>Header ID</th>
                                           <th>Doc Number</th>
                                           <th>Line Num</th>
                                           <th>Item Name</th>
                                           <th>Item Type</th>
                                           <th>Product Type</th>
                                           <th>Description</th>
                                           <th>Quantity</th>
                                           <th></th>
                                       </tr>
                                   </thead>
                                   <tbody>
                                    <?php 

                                    foreach ($salesItems as $key => $value) { 
                                        ?>
                                        <tr>
                                            <td><?php echo $value->header_id ;?></td>
                                            <td><?php echo $value->DocNum ;?></td>
                                            <td><?php echo $value->LineNum ;?></td>
                                            <td><?php echo $value->item_name ;?></td>
                                            <td><?php echo $value->item_type ;?></td>
                                             <td><?php echo $value->u_product_type ;?></td>
                                             <td><?php echo $value->Dscription ;?></td>
                                             <td><?php echo $value->Quantity ;?></td>
                                            <td>
                                       
                                      <?php if($value->is_process_assign == 'Y') {
                                          $process_header = $this->db->query("SELECT * FROM sales_process_header WHERE sales_item_id='".$value->salesitem_id ."' ")->row();   
                                     ?>

                                            <a href="<?php echo base_url()?>Master/showAssignProcessForm/<?php echo $value->DocEntry ?>/<?php echo $value->LineNum ?>/<?php echo $process_header->id?>" class="btn btn-sm btn-info">Edit Process</a>

                                        <?php }else { ?>
                                         <a href="<?php echo base_url()?>Master/showAssignProcessForm/<?php echo $value->DocEntry ?>/<?php echo $value->LineNum ?>" class="btn btn-sm btn-info">Assign Process</a>

                                         <?php }?>
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
 
  


<script>

$(document).ready(function() {
    $('.select2').select2();
     $('#exampleModal').on('shown.bs.modal', function () {
        $('.select2').select2({
            dropdownParent: $('#exampleModal') 
        });
    });
});




</script>
