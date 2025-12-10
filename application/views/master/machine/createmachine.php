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
                  <div class="d-flex justify-content-between align-items-center mb-3">
                       <h1 class="h3 mb-0"> <?= isset($editdata) ? 'Edit' : 'Add' ?> Machine</h1>
                       
                        <a href="<?php echo base_url()?>Master/machines" class="btn btn-primary">Back</a>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    
                                </div>
                                <div class="card-body">

                            <?php if(isset($editdata)){ ?>
                             <form class="form-group " action="<?php echo base_url()?>Master/save_Machine" method="post">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                          <input type="hidden" name="id" value="<?php echo $editdata->id ;?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Code*</label>
                                                <input type="text" name="machine_code" id="code" class="form-control" value="<?php echo $editdata->code; ?>" required readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Name*</label>
                                                <input type="text" name="machine_name" id="name" class="form-control"  value="<?php echo $editdata->machine_name; ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input type="text" name="description" id="description" class="form-control"  value="<?php echo $editdata->description; ?>">
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <label class="form-label">Hourly Impression*</label>
                                                <input type="number" name="hourly_impression" id="hourly_impression" class="form-control" min="0"  value="<?php echo $editdata->hourly_impression; ?>">
                                            </div> -->


                                            <!-- <div class="col-md-6">
                                                <label class="form-label">Changeover Time*</label>
                                                <input type="text" name="changeover_time" id="changeover_time" class="form-control"  value="<?php echo $editdata->change_overtime; ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Capacity*</label>
                                                <input type="text" name="production_capacity" id="production_capacity" class="form-control" value="<?php echo $editdata->production_capacity; ?>">
                                            </div> -->


                                              <div class="col-md-6">
                                                <label class="form-label">Shift Pattern</label>
                                                 <input type="text"  class="form-control" name="shift_pattern" id="shift_pattern" value="<?php echo $editdata->shift_pattern;?>" >
                                             </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Shift Time (Hours)</label>
                                                <input type="number" name="shift_time" id="shift_time" class="form-control" value="<?php echo $editdata->shift_time ;?>">

                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Setup Time (Minutes)</label>
                                                <input type="number" name="setup_time" id="setup_time" min="0" step="0.1" class="form-control" value="<?php echo $editdata->setup_time ;?>">
                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Changeover Time(Minutes)</label>
                                                 <input type="text" name="changeover_time" id="changeover_time" class="form-control" value="<?php echo $editdata->changeover_time ;?>">
                                             </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Job Setting Time(Minutes)</label>
                                                 <input type="number" name="jobsetting_time" id="jobsetting_time" class="form-control" value="<?php echo $editdata->jobsetting_time ;?>">
                                             </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Availablity(Days/Week)</label>
                                                 <input type="number" name="availablity" id="availablity" class="form-control" value="<?php echo $editdata->availablity;?>">
                                             </div>
                                              <div class="col-md-6">
                                                <label class="form-label">Max Operational Hours(Per Week)</label>
                                                 <input type="number" name="max_operational_hour" id="max_operational_hour" class="form-control" value="<?php echo $editdata->max_operational_hour ;?>">
                                             </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Main Operator</label>
                                               <select class="form-control select2" name="operators[]" id="operators" multiple>
                                                <option value="">Select</option>
                                                <?php

                                                  $selectedoperator =explode(',', $editdata->main_operator);
                                                
                                                 foreach($operators as $value) {

                                                    $selected = in_array($value->em_id, $selectedoperator ?? []) ? 'selected' : '';

                                                    ?>

                                                 <option value="<?php echo $value->em_id ?>" <?php echo $selected ;?>>
                                                    <?php echo $value->first_name .' '. $value->last_name ;?>
                                                 </option>
                                                <?php } ?>
                                               </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Helper </label>
                                                 <select class="form-control select2" name="helpers[]" id="helper" multiple>
                                                <option value="">Select</option>
                                                <?php 

                                                 $selectedHelper =explode(',', $editdata->helper);
                                                foreach($helpers as $value) {
                                                      $selected = in_array($value->em_id, $selectedHelper ?? []) ? 'selected' : '';
                                                    ?>

                                                  <option value="<?php echo $value->em_id ?>" <?php echo $selected ;?>>
                                                    <?php echo $value->first_name .' '. $value->last_name ;?>
                                                 </option>
                                                <?php } ?>
                                               </select>
                                            </div>
                                             <div class="col-md-6">
                                                <label>Type</label>
                                                <select class="form-control" name="job_type" id="job_type">
                                                    <option value="job_work" <?php if($editdata->job_type=='job_work'){echo "selected "; }?>>Job Work</option>
                                                    <option value="in_house" <?php if($editdata->job_type=='in_house'){echo "selected "; }?>>in house</option>
                                                </select>
                                            </div>

                                           <div class="col-md-6">
                                                <label class="form-label">Gauge</label>
                                                <button type="button" class="btn btn-sm btn-primary ml-2 mt-2" onclick="selectAllGuage()">Select All</button>
                                                <select class="form-select select2" name="guage[]" id="guage" multiple>
                                                    <option value="">Select</option>
                                                <?php 

                                                 $gaugearray = explode(',', $editdata->gauge);

                                                foreach($gauges as $value) { ?>

                                                    <option value="<?php echo $value->id?>"  <?php echo (isset($editdata) && in_array($value->id, $gaugearray)) ? 'selected' : ''; ?>>
                                                        <?php echo $value->gauge_name?>
                                                            
                                                        </option>
                                                <?php }?>
                                            </select>
                                         
                                            </div>
                                                   <div class="col-md-6">
                                                <label class="form-label">Changeover frequency(per day)*</label>
                                                <input type="number" name="changeover_frequency" id="changeover_frequency" class="form-control" value="<?php echo $editdata->changeover_frequency?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Capacity(Day)*</label>
                                                <input type="number" name="production_capacity" id="production_capacity" class="form-control" value="<?php echo $editdata->production_capacity?>">
                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Min Width(Row Material)</label>
                                                <input type="number"  step="0.01"name="min_width" id="min_width"
                                                    class="form-control" value="<?php echo $editdata->min_width ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Width(Row Material)</label>
                                                <input type="number" step="0.01" name="max_width" id="max_width" class="form-control" value="<?php echo $editdata->max_width ?>">
                                            </div>

                                             <div class="col-md-6">
                                                <label class="form-label">Min Length(Row Material)</label>
                                                <input type="number" step="0.01" name="min_height" id="size"
                                                    class="form-control" value="<?php echo $editdata->min_length ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Length(Row Material)</label>
                                                <input type="number" step="0.01" name="max_height" id="max_height" class="form-control" value="<?php echo $editdata->max_length ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Status*</label>
                                                <select class="form-control" name="status" id="status" required>
                                                    <option value="ACTIVE" <?php if($editdata->status=='ACTIVE'){echo "selected" ;} ?>>ACTIVE</option>
                                                    <option value="INACTIVE" <?php if($editdata->status=='INACTIVE'){echo "selected" ;} ?>>INACTIVE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn  btn-info">Submit</button>
                                        </div>
                                    </form>
                            <?php }else{ ?>
                                    <form class="form-group " action="<?php echo base_url()?>Master/save_Machine" method="post">
                                    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Code*</label>
                                                <input type="text" name="machine_code" id="code" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Name*</label>
                                                <input type="text" name="machine_name" id="name" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Description</label>
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                          <!--   <div class="col-md-6">
                                                <label class="form-label">Hourly Impression*</label>
                                                <input type="number" name="hourly_impression" id="hourly_impression" class="form-control" min="0">
                                            </div> -->
                                            <div class="col-md-6">
                                                <label class="form-label">Shift Pattern</label>
                                                 <input type="text" name="shift_pattern" id="shift_pattern" class="form-control">
                                             </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Shift Time (Hours)</label>
                                                <input type="number" name="shift_time" id="shift_time" class="form-control">

                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Setup Time (Minutes)</label>
                                                <input type="number" name="setup_time" id="setup_time" step="0.1" min="0" class="form-control">
                                            </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Changeover Time(Minutes)</label>
                                                 <input type="number" name="changeover_time" id="changeover_time" class="form-control">
                                             </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Job Setting Time(Minutes)</label>
                                                 <input type="number" name="jobsetting_time" id="jobsetting_time" class="form-control">
                                             </div>
                                             <div class="col-md-6">
                                                <label class="form-label">Availablity(Days/Week)</label>
                                                 <input type="number" name="availablity" id="availablity" class="form-control">
                                             </div>
                                              <div class="col-md-6">
                                                <label class="form-label">Max Operational Hours(Per Week)</label>
                                                 <input type="number" name="max_operational_hour" id="max_operational_hour" class="form-control">
                                             </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Main Operator</label>
                                               <select class="form-control select2" name="operators[]" id="operators" multiple>
                                                <option value="">Select</option>
                                                <?php foreach($operators as $value) {?>

                                                 <option value="<?php echo $value->em_id ?>">
                                                    <?php echo $value->first_name .' '. $value->last_name ;?>
                                                 </option>
                                                <?php } ?>
                                               </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Helper </label>
                                                 <select class="form-control select2" name="helpers[]" id="helper" multiple>
                                                <option value="">Select</option>
                                                <?php foreach($helpers as $value) {?>

                                                  <option value="<?php echo $value->em_id ?>">
                                                    <?php echo $value->first_name .' '. $value->last_name ;?>
                                                 </option>
                                                <?php } ?>
                                               </select>
                                            </div>
                                             
                                            <div class="col-md-6">
                                                <label>Type</label>
                                                <select class="form-control" name="job_type" id="job_type">
                                                    <option value="job_work">Job Work</option>
                                                    <option value="in_house">in house</option>
                                                </select>
                                            </div>

                                             <div class="col-md-6">
                                                <label class="form-label">Gauge</label>
                                                 <button type="button" class="btn btn-sm btn-primary ml-2 mt-2" onclick="selectAllGuage()">Select All</button>

                                                <select class="form-select select2" name="guage[]" id="guage" multiple>
                                                    <option value="">Select</option>
                                                <?php foreach($gauges as $value) { ?>
                                                    <option value="<?php echo $value->id?>"><?php echo $value->gauge_name?></option>
                                                <?php }?>
                                              </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Changeover frequency(per day)*</label>
                                            <input type="number" name="changeover_frequency"  step="0.01" id="changeover_frequency" class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Production Capacity(Day)*</label>
                                                <input type="text" name="production_capacity" id="production_capacity" class="form-control">
                                            </div>
                                              <div class="col-md-6">
                                                <label class="form-label">Min Width(Row Material)</label>
                                                <input type="number" step="0.01"  name="min_width" id="min_width"
                                                    class="form-control" >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Width(Row Material)</label>
                                                <input type="number" step="0.01" name="max_width" id="max_width" class="form-control" >
                                            </div>

                                             <div class="col-md-6">
                                                <label class="form-label">Min length(Row Material)</label>
                                                <input type="number" step="0.01" name="min_height" id="min_height" class="form-control" >
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max length</label>
                                                <input type="number" step="0.01" name="max_height" id="max_height" class="form-control">
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <label class="form-label">Status*</label>
                                                <select class="form-control" name="status" id="status" required>
                                                    <option value="ACTIVE">ACTIVE</option>
                                                    <option value="INACTIVE">INACTIVE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-4">
                                            <button type="submit" class="btn  btn-info">Submit</button>
                                        </div>
                                    </form>
                                <?php }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        <?php
        $this->load->view('include/footer');
    ?>
<script type="text/javascript">
    $(document).ready(function(){
 $('.select2').select2();
    });

    function selectAllGuage()

    {
         $("#guage > option[value!='']").prop("selected", true);
      //  $("#guage > option").prop("selected", true);

        $("#guage").trigger("change");

    }
</script>


