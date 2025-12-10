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
                    <h1 class="h3 mb-3"><?=$title;?></h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0"><?=$title;?></h5>
                                        <?php if($urldata->writep==1) {  ?>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_process">
                                        <i class="fa fa-plus"></i> Add New
                                        </button>
                                        <?php } ?>
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
                                                <th>Process Group </th>
                                                <th>Process Subgroup</th>
                                                <th>Parameters</th>
                                                <th>Active</th>
                                                <th class="rounded-end">Action</th>
                                            </tr>
                                            <!-- end tr -->
                                        </thead>
                                        <?php
                                        $x=1;
                                        foreach ($process as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $x ?></td>
                                            <td><?php echo $value->process_code ?></td>
                                            <td><?php echo $value->process_name ?></td>
                                            <td><?php echo $value->description ?></td>
                                            <td><?php echo $value->group_name ?></td>
                                            <td><?php echo $value->subgroup_name ?></td>
                                            <td><?php echo $value->parameter_names?></td>
                                            <td><?php echo $value->status ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#edit_process" onclick="editFunc('<?php echo $value->id; ?>')" ><i class="fa fa-edit"></i>
                                                </button>
                                                
                                                <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Master/remove_process/'.base64_encode($value->id); ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                    </main>
<?php
    $this->load->view('include/footer');
 ?>


<script >
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

    function  getsubgroup(idet,selected=null)
    {   
        if(idet=='edit')
        {
            var group=$("#edit_process_group").val();
        }
        else{
             var group=$("#process_group").val();
        }
       

        $.ajax({
            url: '<?= base_url("Master/getProcessSubgroupByGroup"); ?>',
            type: 'POST',
            data: {
                group: group,
                [csrfName]: csrfHash 
            },
         dataType:'json',
            success: function(response) {
             
                $('#csrf_token').val(response.csrfHash);
             
              if(idet=='edit'){
                 $("#edit_process_subgroup").html(response.subgroup);
                  $("#edit_process_subgroup").val(selected);

              }
              else{
                $("#process_subgroup").html('<option value="">select</option>'+response.subgroup);
              }
               
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }


     function editFunc(id)

    {

        $("#edit_id").val('');

        $("#edit_status").val('');



        $("#edit_plate_size_id").val('');

        $("#edit_material_id").val('');

        $("#edit_color_id").val('');

        $("#edit_plate_size").val('');

        $("#edit_material_name").val('');

        $("#edit_color_name").val('');

        $("#edit_back_plate_price").val('');

        $("#edit_material_price").val('');

        $("#edit_total_price").val('');
         $("input[name='parametersIds[]']").prop('checked', false);




        var base_url = "<?php echo base_url(); ?>";

        $.ajax({

            url: base_url + 'Master/getProcessById',

            type: 'post',

            data:{id:id  , [csrfName]: csrfHash },

            dataType: 'json',

            success:function(response) 

            {
               
                $('#csrf_token').val(response.csrfHash);
                $("#id").val(response.process.id);

                $("#edit_status").val(response.process.status);



                $("#edit_process_group").val(response.process.group_id);

                $("#edit_process_subgroup").val(response.process.subgroup_id);

                $("#edit_description").val(response.process.description);

                $("#edit_code").val(response.process.process_code);

                $("#edit_name").val(response.process.process_name);

                getsubgroup('edit',response.process.subgroup_id);



                 const parameterIds = response.process_parameter || [];
                    const parameterIdsArray = (typeof parameterIds === 'string') ? parameterIds.split(',') : parameterIds;

                    parameterIdsArray.forEach(function(parameterId) {
                        $('#edit_parameter_' + parameterId).prop('checked', true);
                    });

            }

        });



    }

</script>

<div class="modal fade" id="add_process" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Add Process</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="<?php echo base_url()?>Master/save_Addprocess" method="post">
                <div class="modal-body">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    
                    
                    <div class="col-md-12">
                        <label class="form-label">Process Group*</label>
                        <select class="form-control form-select" name="process_group" id="process_group" onchange="getsubgroup('create')" required>
                            <option value="">Select</option>
                            <?php foreach($process_group as $value){ ?>
                            <option value="<?php echo $value->id?>"> <?php echo $value->name?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Process Subgroup</label>
                        <select class="form-control form-select" name="process_subgroup" id="process_subgroup" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Code*</label>
                        <input type="text" name="code" id="code" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Name*</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Status*</label>
                        <select class="form-control" name="status" id="status" required>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>

                  <div class="col-md-12">
                    <label class="form-label">Parameters</label>
                    <div class="row"> <!-- Needed to align columns -->
                        <?php foreach ($parameters as $key => $value) { ?>
                            <div class="col-md-3 d-flex align-items-center"> <!-- Each checkbox column -->
                                <div class="form-check">
                                    <input type="checkbox" 
                                           id="parameter_<?php echo $value->id ?>" 
                                           name="parametersIds[]" 
                                           value="<?php echo $value->id ?>" 
                                           class="form-check-input">
                                    <label class="form-check-label" 
                                           for="parameter_<?php echo $value->id ?>">
                                        <?php echo $value->parameter_name ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
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

  <!------------------------ edit modal --------------------------------------------------->
<div class="modal fade" id="edit_process" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel">Edit Process</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-group" action="<?php echo base_url()?>Master/save_Editprocess" method="post">
                <div class="modal-body">
                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                    <input type="hidden" name="id"  class="form-control" id="id">
                    
                    <div class="col-md-12">
                        <label class="form-label">Process Group</label>
                        <select class="form-control form-select" name="process_group" id="edit_process_group" onchange="getsubgroup('edit')" required>
                            <option value="">Select</option>
                            <?php foreach($process_group as $value){ ?>
                            <option value="<?php echo $value->id?>"> <?php echo $value->name?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Process Subgroup</label>
                        <select class="form-control form-select" name="process_subgroup" id="edit_process_subgroup" >
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" id="edit_code" class="form-control" required readonly>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                    </div>

                      <div class="col-md-12">
                    <label class="form-label">Parameters</label>
                    <div class="row"> <!-- Needed to align columns -->
                        <?php foreach ($parameters as $key => $value) { ?>
                            <div class="col-md-3 d-flex align-items-center"> <!-- Each checkbox column -->
                                <div class="form-check">
                                    <input type="checkbox" 
                                           id="edit_parameter_<?php echo $value->id ?>" 
                                           name="parametersIds[]" 
                                           value="<?php echo $value->id ?>" 
                                           class="form-check-input">
                                    <label class="form-check-label" 
                                           for="edit_parameter_<?php echo $value->id ?>">
                                        <?php echo $value->parameter_name ?>
                                    </label>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
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