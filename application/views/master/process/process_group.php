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
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Edit Process Group</h5>
                                    <?php } else { ?>
                                        <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Add Process Group</h5>
                                    <?php } ?>


                                </h5>
                            </div>
                            <div class="card-body">
                                <form class="form-group row" role="form" action="<?php echo base_url('Master/save_processGroup') ?>" method="post">
                                    <input type="hidden" name="id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->id; ?>" <?php } ?>>
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                    <div class=" col-md-12">
                                        <label class="form-label"> Code*</label>
                                        <input type="text" name="code" class="form-control"  placeholder="Enter  Code" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->code; ?>" <?php } ?> autocomplete="off" required <?php if(isset($editdata)){ echo "readonly" ;}?>>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Name*</label>
                                        <input type="text" name="name" class="form-control"  placeholder="Enter Name" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->name; ?>" <?php } ?> autocomplete="off" required>
                                    </div>
                                    <div class=" col-md-12">
                                        <label class="form-label">Description*</label>
                                       <textarea class="form-control" name="description" id="description" rows="3"><?php if(isset($editdata)){ echo $editdata->description ;}?></textarea>
                                    </div>
                                  
                                    <div class="col-md-6">
                                        <label class="form-label">Active*</label>
                                        <select name="status" class="form-control">
                                            <option value="ACTIVE" <?php if(isset($editdata) && $editdata->status=='ACTIVE') { echo "selected"; } ?>>ACTIVE</option>
                                            <option value="INACTIVE" <?php if(isset($editdata) && $editdata->status=='INACTIVE') { echo "selected"; } ?>>INACTIVE</option>
                                        </select>
                                    </div>
                                    <div class=" col-md-6">
                                        <label class="form-label">Sequence*</label>
                                        <input type="number" min="0" class="form-control" name="sequence" id="sequence" value="<?php if(isset($editdata)){ echo $editdata->sequence; }?>">
                                    </div>

                                
                                    <div class="form-group col-md-12 mb-0 mt-3">
                                        <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save <?php if(isset($editdata)) { echo "Changes"; } ?></button>
                                        <?php if(isset($editdata)) { ?>
                                            <a href="<?php echo base_url(); ?>Master/process_group"><input type="button" class="btn btn-secondary ml-2" value="Back"></a>
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
                                            <th> Code</th>
                                            <th> Name</th>
                                            <th>Description</th>
                                            <th>Sequence</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Sr.</th>
                                            <th> Code</th>
                                            <th> Name</th>
                                            <th>Description</th>
                                             <th>Sequence</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $x=1; 
                                        foreach($process_group as $value)
                                        { 
                                            ?>
                                            <tr>
                                                <td><?php echo $x; ?></td>
                                                <td><?php echo $value->code; ?></td>
                                                <td><?php echo $value->name; ?></td>
                                                <td><?php echo $value->description; ?></td>
                                                <td><?php echo $value->sequence ;?></td>
                                                <td><?php if($value->status=='ACTIVE') { echo "ACTIVE"; } else { echo "INACTIVE"; } ?></td>
                                                <td class="text-center">
                                                   <a href="<?php echo base_url().'Master/edit_process_group/'.base64_encode($value->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

                                                 <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_processgroup/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> 
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