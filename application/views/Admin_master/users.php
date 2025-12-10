<?php $this->load->view('inc/header'); ?>

    <div class="az-content-body">
        <div class="row">
            <div class="col-12 mb-2">        
                <a href="<?php echo base_url().'Admin_master/add_users'; ?>"><button type="button" class="btn btn-info"><i class="fa fa-plus"> </i> Add User</button></a>          
            </div>

            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-header border-bottom">
                        <h4 class="m-b-0 "><i class="fa fa-list" aria-hidden="true"> </i> User List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table id="example23" class="display nowrap table table-striped table-hover table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Username</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                            <?php
                                $x=1; 
                                foreach($userdata as $value)
                                { 
                            ?>
                                    <tr>
                                        <td><?php echo $x; ?></td>
                                        <td><?php echo $value->first_name.' '.$value->last_name; ?></td>
                                        <td><?php echo $value->email; ?></td>
                                        <td><?php echo $value->phone; ?></td>
                                        <td><?php echo $value->username; ?></td>
                                        <td class="text-center">
                                            <a href="<?php echo base_url().'Admin_master/edit_users/'.base64_encode($value->user_id); ?>" title="Edit"><button class="btn btn-info"><i class="fa fa-pencil-square-o"></i></button></a>
                                            <a onclick="return confirm('Are You Sure To Remove This Data !')" href="<?php echo base_url().'Admin_master/remove_users/'.base64_encode($value->user_id); ?>" title="Delete"><button class="btn btn-danger"><i class="fa fa-trash-o"></i></button></a>
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
    </div>

<?php $this->load->view('inc/footer'); ?>

<script type="text/javascript">

$(document).ready(function() 
{
    $('#link-Admin_master').addClass('show');
    $("#link-Admin_master").addClass('active');
    $("#li-users").addClass('active');
});

    $('#example23').DataTable({
        "ordering": false,
        "aLengthMenu": [[10, 25, 100, -1], [10, 25, 100, "All"]],
        "iDisplayLength": 10
    });
</script>