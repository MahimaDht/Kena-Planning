<?php
$this->load->view('include/header');

?>
<?php 
if(isset($editdata) && !empty($editdata->main_operator)) {
    $selectedoperator = explode(',', $editdata->main_operator);
} else {
    $selectedoperator = []; // always an array
}
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
                    <h1 class="h3 mb-3"><?=$title;?>
                         <a href="<?php echo base_url() ?>Master/exportExcelMachine" class="btn btn-info float-end">Export</a>
                    </h1>
                    <div class="row">
                         
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0"><?=$title;?></h5>
                                        <a href="<?php echo base_url()?>Master/addMachine" class="btn btn-sm btn-primary">Add New</a>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered" id="datatable_index">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>sr.no</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Description </th>
                                           
                                                <th>Active</th>
                                                <th class="rounded-end">Action</th>
                                               
                                            </tr>
                                            <!-- end tr -->
                                        </thead>
                                        <tbody>
                                        <?php
                                        $x=1;
                                        foreach ($machines as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $x ?></td>
                                            <td><?php echo $value->code ?></td>
                                            <td><?php echo $value->machine_name ?></td>
                                            <td><?php echo $value->description ?></td>
                                         
                                            <td><?php echo $value->status ?></td>
                                            <td class="text-center">
                                                <a href="<?php echo base_url()?>Master/edit_machine/<?php echo base64_encode($value->id)?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                
                                                <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_machine/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                           
                                        </tr>
                                        <?php $x++ ;} ?>
                                        
                                            </tbody><!-- end tbody -->
                                            </table><!-- end table -->
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