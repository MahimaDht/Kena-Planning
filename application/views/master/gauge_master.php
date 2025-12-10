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
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#ImportModal" style="margin-left: 10px;">Import</button>
                     
                          <a href="<?php echo base_url('uploads/documents/gauge.csv'); ?>"  class="btn btn-primary float-end "><i class="fa fa-download"></i>Template </a>
                    </h1>
                     
                    <div class="row">
                        <div class="col-5">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">  <?php if(isset($editdata)) { ?>
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Edit Gauge </h5>
                                    <?php } else { ?>
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Add Gauge</h5>
                                    <?php } ?>


                                </h5>
                            </div>
                            <div class="card-body">
                                
                                <form class="form-group" role="form" action="<?php echo base_url('Master/save_gauge') ?>" method="post">
                                    <input type="hidden" name="id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->id; ?>" <?php } ?>>
                                    <input type="hidden" name="gauge_code" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->gauge_code; ?>" <?php } ?>>
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                            

                                    <div class="col-md-12">
                                        <label class="form-label">Name*</label>
                                        <input type="text" name="gauge_name" class="form-control" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->gauge_name; ?>" <?php } ?> autocomplete="off" required>
                                    </div>


                                   <!--  <div class="col-md-12">
                                          <label class="form-label">Unit*</label>
                                           <select class="form-select" name="unit" id="unit" required>
                                            <option value="">Select</option>
                                                <option value="GSM" <?php if(isset($editdata) && ($editdata->gauge_unit=='GSM')) { echo "selected" ;}?>>GSM</option>
                                                <option value="MM" <?php if(isset($editdata) && ($editdata->gauge_unit=='MM')) { echo "selected" ;}?> >MM</option>
                                           </select>
                                    </div> 
 -->
                                
                                    <div class="form-group col-md-12 mb-0 mt-3">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save <?php if(isset($editdata)) { echo "Changes"; } ?></button>
                                        <?php if(isset($editdata)) { ?>
                                            <a href="<?php echo base_url(); ?>Master/gaugeMaster"><input type="button" class="btn btn-secondary ml-2" value="Back"></a>
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
                                <h5 class="card-title mb-0">Gauge</h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered" id="datatable_index">
                                    <thead>
                                        <tr>
                                            <th>Sr.</th>
                                            <th> Code</th>
                                            <th> Name</th>
                                           <!-- <th>Unit</th> -->
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                           <th>Sr.</th>
                                            <th> Code</th>
                                            <th> Name</th>
                                        <!--    <th>Unit</th> -->
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $x=1; 
                                        foreach($gauges as $value)
                                        { 
                                            ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                               <td><?php echo $value->gauge_code; ?></td> 
                                                <td><?php echo $value->gauge_name; ?></td>
                                             <!--    <td><?php echo $value->gauge_unit; ?></td> -->
                                               
                                                <td class="text-center">
                                                   <a href="<?php echo base_url().'Master/edit_gaugeMaster/'.base64_encode($value->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a> 

                                                   <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/deleteGauge/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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

<div class="modal fade" id="ImportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?php echo base_url('Master/importGaugeExcel'); ?>" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    
                    <input type="file" name="excel_file" accept=".csv" required class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>