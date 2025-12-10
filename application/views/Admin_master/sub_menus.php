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

                    <h1 class="h3 mb-3">Role</h1>

                    <div class="row">
                    <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">   <?php if(isset($editdata)) { ?>
                            <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Edit Sub Menu</h5>
                        <?php } else { ?>
                            <h5 class="mb-0"><i class="fa fa-plus" aria-hidden="true"> </i> Add Sub Menu</h5>
                        <?php } ?>
                    
                    
                    </h5>
                                </div>
                                <div class="card-body">
                               <form class="row" role="form" action="<?php echo base_url('Admin_master/save_sub_menus') ?>" method="post">
                            <input type="hidden" name="id" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->id; ?>" <?php } ?>>
<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                            <div class="form-group col-md-12">
                                <label>Main Menu</label>
                                <select name="menu_id" class="form-control select2">
                                    <option value="" selected disabled>Select</option>
                                <?php foreach($menudata as $value) { if(!isset($editdata) || (isset($editdata) && $editdata->menu_id!=$value->id)) ?>
                                    <option value="<?php echo $value->id; ?>"  <?php if(isset($editdata) && $editdata->menu_id==$value->id) { echo "selected"; } ?>><?php echo $value->menu_name; ?></option>
                                <?php } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Sub Menu name*</label>
                                <input type="text" name="sub_menu_name" class="form-control" minlength="3" placeholder="Enter Menu Name" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->sub_menu_name; ?>" <?php } ?> autocomplete="off" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Url*</label>
                                <input type="text" name="sub_url" class="form-control" minlength="1" placeholder="Enter Url" <?php if(isset($editdata)) { ?> value="<?php echo $editdata->sub_url; ?>" <?php } ?> autocomplete="off" required>
                            </div>
                           
                            
                            <div class="form-group col-md-6">
                                <label>Active*</label>
                                <select name="active" class="form-control">
                                    <option value="1" <?php if(isset($editdata) && $editdata->active==1) { echo "selected"; } ?>>ACTIVE</option>
                                    <option value="0" <?php if(isset($editdata) && $editdata->active==0) { echo "selected"; } ?>>INACTIVE</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" class="btn btn-info"><i class="fa fa-check"></i> Save <?php if(isset($editdata)) { echo "Changes"; } ?></button>
                                <?php if(isset($editdata)) { ?>
                                    <a href="<?php echo base_url(); ?>Admin_master/sub_menus"><input type="button" class="btn btn-secondary ml-2" value="Back"></a>
                                <?php } else { ?>
                                    <button type="reset" class="btn btn-secondary ml-2">Reset</button>
                                <?php } ?>
                            </div>
                        </form>

                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Role</h5>
                                </div>
                                <div class="card-body table-responsive">
                                <table class="table table-bordered" id="datatable_index">
    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Menu Name</th>
                                        <th>Sub Menu</th>
                                        <th>Url</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Menu Name</th>
                                        <th>Sub Menu</th>
                                        <th>Url</th>
                                        <th>Active</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                            <?php
                                $x=1; 
                                foreach($submenudata as $value)
                                { 
                            ?>
                                    <tr>
                                        <td><?php echo $x; ?></td>
                                        <td><?php echo $value->menu_name; ?></td>
                                        <td><?php echo $value->sub_menu_name; ?></td>
                                        <td><?php echo $value->sub_url; ?></td>
                                        <td><?php if($value->active==1) { echo "ACTIVE"; } else { echo "INACTIVE"; } ?></td>
                                        <td class="text-center">

                                             <a href="<?php echo base_url().'Admin_master/edit_sub_menus/'.base64_encode($value->id); ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>

                                            <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Admin_master/remove_sub_menus/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                        
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