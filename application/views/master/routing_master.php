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
                                        <a href="<?php echo base_url()?>Master/add_routingMaster" class="btn btn-sm btn-primary">Add New</a>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered" id="datatable_index">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>sr.no</th>
                                                <th>Code</th>
                                                <th>Product Type</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                               
                                                <th class="rounded-end">Action</th>
                                            </tr>
                                            <!-- end tr -->
                                        </thead>
                                          <tbody>
                                        <?php
                                       $x=1;
                                        foreach ($routingheders as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $x ?></td>
                                            <td><?php echo $value->rout_code?></td>
                                            <td><?php echo  $value->product_name;?></td>
                                            <td><?php echo $value->name ?></td>
                                            <td><?php echo $value->description ?></td>
                                         
                                            <td class="text-center">
                                                <a href="<?php echo base_url()?>Master/edit_routing/<?php echo base64_encode($value->id)?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                
                                                <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/removeRouting/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php $x++ ;} ?>
                                      
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
 $(document).ready(function(){
    $('.select2').select2();
});
</script>