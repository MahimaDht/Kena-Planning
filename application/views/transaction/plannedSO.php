
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
                  
                    <div class="row">
                         
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">SO For Planning</h5>
                                       
                                    </div>
                                </div>
                            
                                <form action="<?php echo base_url('Transaction/save_plannedSO') ?>" method="post">
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered" id="datatable_index">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Plan Seq</th>
                                                <th>Item Name</th>
                                                <th>Raw Material</th>
                                                <th>Qty</th>
                                                <th>Machine</th>
                                                <th>Stock</th>
                                                <th>Date</th>
                                            </tr>
                                            <!-- end tr -->
                                        </thead>
                                        <tbody>
                                        <?php
                                        $x = 1;
                                        foreach ($plannedSO as $key => $value) { 
                                            // Assuming a helper or method exists to fetch stock via API
                                            $stock = getItemStockFromAPI($value->raw_material_code);
                                            

                                            ?>
                                        <tr>
                                            <td><?php echo $x ?><input type="hidden" name="planned_id[]" value="<?php echo $value->id ?>"></td>
                                            <td><?php echo $value->planSeq ?></td>
                                            <td><?php echo $value->Dscription ?></td>
                                            <td><?php echo $value->raw_material ?></td>
                                         
                                            <td><?php echo $value->OpenQty ?></td>
                                            <td><?php echo $value->machine_name ?></td>
                                            <td><?php echo $stock ?></td>
                                            <td><input type="date" class="form-control" name="plan_date[]" value=""></td>
                                            
                                        </tr>
                                        <?php $x++ ;} ?>
                                        
                                            </tbody><!-- end tbody -->
                                            </table><!-- end table -->
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
<?php
    $this->load->view('include/footer');
 ?>

