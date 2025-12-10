
<?php $this->load->view('include/header'); ?>
<?php $this->load->view('include/sidebar'); ?>

    <div class="page-body">
        <div class="container-fluid">        
            <div class="page-title">
                <div class="row">
                    <div class="col-6">
                        <h3>Employee : <?php echo $employeedata->em_id; ?></h3>
                    </div>
                    <div class="col-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo base_url(); ?>">                                       
                                    <svg class="stroke-icon">
                                        <use href="<?php echo base_url(); ?>assets/svg/icon-sprite.svg#stroke-home"> Dashboard</use>
                                    </svg>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Employee List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 style="display: inline-block; width: 32%;">Name : <?php echo $employeedata->first_name.' '.$employeedata->last_name; ?></h5>
                            <h5 style="display: inline-block; float: center; width: 30%;">Department : <?php echo $employeedata->dep_name; ?></h5>
                            <h5 style="display: inline-block; float: right; width: 32%;">Designation : <?php echo $employeedata->des_name; ?></h5>
                        </div>
                        <div class="card-body">
                            <form class="row" action="<?php echo base_url(); ?>employee/employeeListUpdate" method="post" enctype="multipart/form-data">
                                <div class="form-group col-md-4">
                                 <?php 
                                   $region_id= $employeedata->region_id;
                                   $regionIdArray = explode(',', $region_id);

                                   ?>
                                    <label>Region</label>
                                    <button type="button" class="btn btn-sm btn-primary ml-2" onclick="selectAllRegion()">Select All</button>
                                    <select style="width: 100%;" class="form-control select2" id="region_id" name="region_id[]" onchange="regionChange()" multiple>
                                        <option value=""  disabled>Select</option>
                                    <?php foreach($regiondata as $value){ ?>
                                        <option value="<?php echo $value->id; ?>" <?php echo (isset($employeedata) && in_array($value->id, $regionIdArray)) ? 'selected' : ''; ?>><?php echo $value->region_title; ?></option>
                                    <?php } ?>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">
                                    <?php 
                                       $zone_id= $employeedata->zone_id;
                                       $zoneIdArray = explode(',', $zone_id);

                                    ?>
                                        <label>Zone</label>
                                        <button type="button" class="btn btn-sm btn-primary ml-2" onclick="selectAllZone()">Select All</button>
                                        <select style="width: 100%;" class="form-control select2" id="zone_id" name="zone_id[]" onchange="zoneChange()" multiple>
                                            <option value=""  disabled>Select</option>
                                        <?php if ($employeedata->region_id) {
       
                                    $region_ids = explode(",", $employeedata->region_id);
                                    
                                    foreach ($region_ids as $region_id) {
                                        $zonedata = $this->db->query("SELECT a.*, b.region_title FROM dhtcrm_zone as a, dhtcrm_region as b WHERE a.status='ACTIVE' AND a.region_id=b.id AND a.region_id='" . $region_id . "'")->result();
                                     
                                        foreach ($zonedata as $value) {

                                            ?>
                                            <option value="<?php echo $value->id; ?>" <?php echo (isset($employeedata) && in_array($value->id, $zoneIdArray)) ? 'selected' : ''; ?>><?php echo $value->zone_title; ?></option>
                                            <?php
                                        }
                                    }
                                }?>

                                    </select>
                                </div>

                                <div class="form-group col-md-4" >


                                     <?php 
                               $subzone_id= $employeedata->subzone_id;
                               $subzoneIdArray = explode(',', $subzone_id);
                               ?>

                                    <label>Sub-Zone</label>
                                    <button type="button" class="btn btn-sm btn-primary ml-2" onclick="selectAllSubZone()">Select All</button>
                                    <select style="width: 100%;" class="form-control select2" id="subzone_id" name="subzone_id[]" multiple>
                                        <option value=""  disabled>Select</option>
                                    <?php

                                    $zone_ids = explode(",", $employeedata->zone_id);

                                     if($employeedata->region_id && $employeedata->zone_id) {

                                        foreach ($zone_ids as $zone_id) {
                                     $subzonedata=$this->db->query("SELECT a.*, b.region_id, b.zone_title, c.region_title FROM dhtcrm_subzone as a, dhtcrm_zone as b, dhtcrm_region as c WHERE a.status='ACTIVE' AND a.zone_id=b.id AND b.region_id=c.id AND a.zone_id='".$zone_id."'")->result();
                                 
                                        
                                    foreach ($subzonedata as $value) {

                                        ?>
                                        <option value="<?php echo $value->id; ?>" <?php echo (isset($employeedata) && in_array($value->id, $subzoneIdArray)) ? 'selected' : ''; ?>><?php echo $value->subzone_title; ?></option>
                                        <?php
                                    }
                                }
                            }?>
                                    </select>
                                </div>

                                <div class="col-md-12 form-group"><hr></div>
                                
                                <div class="form-group col-md-12 m-t-10">
                                    <input type="hidden" id="emid" name="em_id" value="<?php echo $employeedata->em_id; ?>">
                                    <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
                                    <button type="button" class="btn btn-info"><a href="<?php echo base_url(); ?>employee/employeeList" class="text-white">Cancel</a></button>
                                </div>  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?php $this->load->view('include/footer'); ?>

<script>

    function selectAllRegion()
    {
        $("#region_id > option").prop("selected", true);
        $("#region_id").trigger("change");
    }

    function selectAllZone()
    {
        $("#zone_id > option").prop("selected", true);
        $("#zone_id").trigger("change");
    }

    function selectAllSubZone()
    {
        $("#subzone_id > option").prop("selected", true);
        $("#subzone_id").trigger("change");
    }

    function regionChange()
    {
        var val=$("#region_id").val();
        //console.log(val);
        if (val && val.length > 0) 
        {
            var oldval=$("#zone_id").val();
            var oldlist = oldval.slice();
            
            $.ajax({
                url: '<?php echo base_url(); ?>CRM/fetchZoneByRegionData',
                type: 'post',
                data:{id:val},
                dataType: 'json',
                success:function(response) 
                {
                    var html='<option value="" selected disabled>Select</option>';
                    
                    $.each(response, function(index, value) 
                    {
                        var isSelected = oldlist.map(String).includes(value.id.toString()) ? 'selected' : '';

                        html += '<option value="'+value.id+'" '+isSelected+'>'+value.zone_title+'</option>';
                    });
            
                    $("#zone_id").html(html);
                    zoneChange();
                }
            });
        }
        else
        {
            $("#zone_id").html('<option value="" selected disabled>Select</option>');
            zoneChange();
        }
    }

    function zoneChange()
    {
        var val=$("#zone_id").val();
        //console.log(val);
        if (val && val.length > 0) 
        {
            var oldval=$("#subzone_id").val();
            var oldlist = oldval.slice();
            
            $.ajax({
                url: '<?php echo base_url(); ?>CRM/fetchSubZoneByZoneData',
                type: 'post',
                data:{id:val},
                dataType: 'json',
                success:function(response) 
                {
                    var html='<option value="" selected disabled>Select</option>';
                    
                    $.each(response, function(index, value) 
                    {
                        var isSelected = oldlist.map(String).includes(value.id.toString()) ? 'selected' : '';

                        html += '<option value="'+value.id+'" '+isSelected+'>'+value.subzone_title+'</option>';
                    });
            
                    $("#subzone_id").html(html);
                }
            });
        }
        else
        {
            $("#subzone_id").html('<option value="" selected disabled>Select</option>');
        }
    }
</script>