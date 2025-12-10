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
                        <a href="<?php echo base_url() ?>Master/exportExcelProcess" class="btn btn-info float-end">Export</a>
                    </h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                              
                            <div class="card-body table-responsive">
                               <table class="table table-striped" id="datatable_index">
                                   <thead>
                                    <tr>
                                       <th>PT CODE</th>
                                       <th>Product Type Name</th>
                                       <th>Description</th>
                                       <th>Action</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                    <?php foreach ($product_type as $key => $value) { ?>
                                       <tr>
                                           <td><?php echo $value->product_code ;?></td>
                                           <td><?php echo $value->product_name ;?></td>
                                            <td><?php echo $value->description ;?></td>
                                           <td>
                                              <a href="<?php echo base_url()?>Master/editprocessMapping/<?php echo base64_encode($value->id)?>" class="btn btn-info"><i class="fa fa-edit"></i></a>
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

  
