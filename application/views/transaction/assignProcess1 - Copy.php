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
                        <div class="col-11 mx-auto">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        Assign Process
                                    </h5>
                                    
                                </div>
                                <div class="card-body">

                                    <?php 

                                    if(isset($editdata)){ 
                                       // print_r($editdata);
                                      $selectedProcesses = [];


                                      foreach ($editdata as $data) {
                                         if (!isset($selectedProcesses[$data->layout_id][$data->group_id])) {
                                               
                                                $selectedProcesses[$data->layout_id][$data->group_id] = [];
                                            }

                                          
                                            $processIds = explode(',', $data->process_id);
                                            foreach ($processIds as $processId) {
                                               
                                                $selectedProcesses[$data->layout_id][$data->group_id][] = trim($processId);
                                            }
                                        }

   
                                    ?>
                                    <form class="form-group" action="<?= base_url('Transaction/update_salesProcess') ?>" method="post">
                                        <input type="hidden" name="process_header_id" id="process_header_id" value="<?php echo $editprocessheader->id ;?>">
                                          <input type="hidden" name="doc_entry" value="<?php echo $doc_entry ;?>">
                                        <input type="hidden" name="sales_id" value="<?= $sales_itemdata->header_id; ?>" id="sales_id">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="id" class="form-control" id="id" value="<?= $sales_itemdata->salesitem_id; ?>">
                                        <input type="hidden" name="product_id" id="product_id" class="form-control" value="<?= $product_id?? ''; ?>">


                                     <h6 class="text-secondary d-flex justify-content-between align-items-center">
                                        <span>Product Type: <span class="fw-semibold text-dark"><?= $sales_itemdata->u_product_type; ?></span></span>
                                        <span class="fw-semibold text-dark">SO.No: 
                                            <?= $sales_itemdata->DocNum ?></span>
                                        <span class="fw-semibold text-dark">Quantity: <?= $sales_itemdata->Quantity ?></span>
                                        <input type="hidden" id="so_qty" name="so_qty" value="<?= $sales_itemdata->Quantity ?>">
                                    </h6>

                                      <div class="row bg-light p-3 mt-4 mb-3 rounded mx-2">
                                        <h5 class="mb-3">Routing Type</h5>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" 
                                                   name="routing_name" id="routing_name" 
                                                   placeholder="Routing Name" 
                                                   value="<?php echo $editprocessheader->routing_name ;?>" readonly>

                                            <input type="hidden" class="form-control" 
                                                   name="routing_id" id="routing_id" 
                                                   value="<?php echo $editprocessheader->routing_id;?>" >
                                            <input type="hidden" class="form-control" 
                                                   name="routing_code" id="routing_code"value="<?php echo $editprocessheader->routing_code;?>" >
                                        </div>

                                        <div class="col-md-3">
                                            <button class="btn btn-info" type="button"
                                                onClick="openRoutingModal('<?php echo $editprocessheader->routing_id;?>')">
                                                Routing
                                            </button>
                                        </div>
                                    </div>

                                        <div class="container mt-4">

                                        
                                            <?php
                                            $grouped = [];
                                            // Group the items

                                           // print_r($process_mapping);
                                            foreach ($process_mapping as $item) {
                                                $layout = $item->layout_name;
                                                $group = $item->process_group;
                                                $grouped[$layout][$group][] = $item;
                                            }
                                            $layoutIndex = 0;
                                            foreach ($grouped as $layoutName => $layoutGroups):
                                                $firstLayoutItem = reset($layoutGroups)[0];
                                                ?>
                                                <div class="card mb-4 shadow-sm col-md-12 bg-light">

                                                    <div class="card-body">
                                                        <h4 class="card-title">Layout: <?= htmlspecialchars($layoutName) ?></h4>
                                                        <input type="hidden" name="layout_id[]" value="<?= $firstLayoutItem->product_layout_id ?>">
                                                        <?php
                                                        $groupIndex = 0;
                                                        foreach ($layoutGroups as $groupName => $items):
                                                            $firstItem = $items[0];
                                                           // print_r($firstItem);

                                                            ?>
                                                            <div class="group-card" id="parameter_box_<?= $layoutIndex ?>_<?= $groupIndex ?>">
                                                                <input type="hidden" name="group_id[<?= $layoutIndex ?>][]" value="<?= $firstItem->process_group_id ?>">
                                                                <h5 class="mb-2 mt-2 text-dark fw-semibold">
                                                                    Group: <span class="text-primary"><?= htmlspecialchars($groupName) ?></span> <span class="group-total-impression"></span>
                                                                </h5>
                                                                 <div class="impression-display card mb-3 p-2 bg-light border" id="impression_<?= $layoutIndex ?>_<?= $groupIndex ?>"><h6 class="text-primary mb-3">Impressions:</h6></div>
                                                                <select
                                                                class="form-control select2 mb-2 process-select"
                                                                name="process_name[<?= $layoutIndex ?>][<?= $groupIndex ?>][]"
                                                                 id="process_<?= $layoutIndex ?>_<?= $groupIndex ?>"
                                                                onchange="parametersshow(this)"
                                                                data-layout-index="<?= $layoutIndex ?>"
                                                                data-group-index="<?= $groupIndex ?>"
                                                                data-group_id="<?= $firstItem->process_group_id ?>"
                                                                data-layout_id="<?= $firstItem->product_layout_id ?>"
                                                                data-subgroup_id="<?= $firstItem->process_subgroup_id ?>"
                                                                multiple
                                                                required >
                                                                <option value="">Select Process</option>
                                                                <?php
                                                                $subgroupMap = [];

                                                                foreach ($items as $item) {
                                                                    $sub = $item->subgroup_name ?? '';
                                                                    $subgroupMap[$sub][] = $item;

                                                                }

                                                                foreach ($subgroupMap as $subgroup => $processes):
                                                                    if (!empty(trim($subgroup))): ?>
                                                                        <optgroup label="<?= htmlspecialchars($subgroup) ?>">
                                                                            <?php foreach ($processes as $proc): ?>
                                                                      <option value="<?= $proc->process_id ?>" data-process_code='<?= $proc->process_code ?>'
                                                                        data-processmappingLayoutId="<?php echo $proc->processmappingLayoutId ?>"
<?= in_array($proc->process_id, (array)($selectedProcesses[$firstItem->product_layout_id][$firstItem->process_group_id] ?? [])) ? 'selected' : '' ?>>
                                                                                    <?= htmlspecialchars($proc->process_name . ' (' . $proc->process_code . ')') ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </optgroup>
                                                                    <?php else: ?>
                                                                        <?php foreach ($processes as $proc): ?>
                                                                            <option value="<?= $proc->process_id ?>"  data-processmappingLayoutId='<?php echo $proc->processmappingLayoutId?>' data-process_code='<?= $proc->process_code ?>'
                                                                                <?= is_array($selectedProcesses[$firstItem->product_layout_id][$firstItem->process_group_id] ?? null) 
        && in_array($proc->process_id, $selectedProcesses[$firstItem->product_layout_id][$firstItem->process_group_id]) ? 'selected' : '' ?>>
                                                                                <?= htmlspecialchars($proc->process_name . ' (' . $proc->process_code . ')') ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif;
                                                                endforeach;
                                                                ?>
                                                            </select>
                                                            <!-- Impression Display for Group -->
                                                            <div class="impression-display-group mt-3 mb-3" id="group_impression_<?= $layoutIndex ?>_<?= $groupIndex ?>" style="display: none;">
                                                                <div class="alert alert-info">
                                                                    <h6 class="mb-2"><i class="bi bi-calculator me-2"></i>Group Impression Summary</h6>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <small class="text-muted">Total Group Impression:</small>
                                                                            <div class="fw-bold text-primary" id="total_group_impression_<?= $layoutIndex ?>_<?= $groupIndex ?>">0</div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <small class="text-muted">Process Count:</small>
                                                                            <div class="fw-bold text-secondary" id="process_count_<?= $layoutIndex ?>_<?= $groupIndex ?>">0</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Placeholder for parameters -->
                                                            <div class="parameter-list"></div>
                                                        </div>
                                                        <?php
                                                        $groupIndex++;
                                                    endforeach;
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            $layoutIndex++;
                                        endforeach;
                                        ?>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="container">
                                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                                                <div class="card-body p-4">
                                                    <h5 class="mb-4 text-primary fw-semibold">Add Product Type Information</h5>
                                                    <div class="row g-4">
                                                        <!-- New Design Checkbox -->
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="form-floating">
                                                                <div class="form-check mb-3">
                                                                    <input type="checkbox" class="form-check-input" id="new_design" name="new_design" value="Y" <?= $editprocessheader->new_design == 'Y' ? 'checked' : '' ?> onchange="newDesignText()">
                                                                    <label class="form-check-label" for="new_design">New Design</label>
                                                                </div>
                                                                <div id="divnewDesign" <?= $editprocessheader->new_design == 'Y' ? '' : 'hidden' ?>>
                                                                    <input type="text" id="textnewDesign" name="new_designtext" class="form-control" placeholder="New Design" value="<?= $editprocessheader->new_design_text ?? '' ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Repeat Design Checkbox -->
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="form-floating">
                                                                <div class="form-check mb-3">
                                                                    <input type="checkbox" class="form-check-input" id="repeat_design" name="repeat_design" value="Y" <?= 
                                                                    $editprocessheader->repeat_design == 'Y' ? 'checked' : '' ?> onchange="repeattext()">
                                                                    <label class="form-check-label" for="repeat_design">Repeat Design</label>
                                                                </div>
                                                                <div id="divRepeatDesign" <?= $editprocessheader->repeat_design == 'Y' ? '' : 'hidden' ?>>
                                                                    <input type="text" id="repeat_designText" name="repeat_designText" class="form-control" placeholder="Repeat Design" value="<?= $editprocessheader->repeat_design_text ?? '' ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Correction Checkbox -->
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="form-floating">
                                                                <div class="form-check mb-3">
                                                                    <input type="checkbox" class="form-check-input" id="correction" name="correction" value="Y" <?= $editprocessheader->correction == 'Y' ? 'checked' : '' ?> onchange="correctiontext()">
                                                                    <label class="form-check-label" for="correction">Correction</label>
                                                                </div>
                                                                <div id="divcorrection" <?= $editprocessheader->correction == 'Y' ? '' : 'hidden' ?>>
                                                                    <input type="text" id="correction_text" name="correction_text" class="form-control" placeholder="Correction" value="<?= $editprocessheader->correction_text ?? '' ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                      
                                    </div>            
                                   
                                    <button type="submit" class="btn btn-primary mt-4">Save changes</button>
                                </form>
                            <?php }else{ ?>
                                <form class="form-group" action="<?= base_url('Transaction/save_salesProcess') ?>" method="post">
                                    <input type="hidden" name="sales_id" value="<?= $sales_itemdata->header_id; ?>" id="sales_id">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    <input type="hidden" name="id" class="form-control" id="id" value="<?= $sales_itemdata->salesitem_id; ?>">
                                    <input type="hidden" name="doc_entry" value="<?php echo $doc_entry ;?>">
                                    <input type="hidden" name="product_id" id="product_id" class="form-control" value="<?= $product_id?? ''; ?>">
                                    <h6 class="text-secondary d-flex justify-content-between align-items-center">
                                        <span>Product Type: <span class="fw-semibold text-dark"><?= $sales_itemdata->u_product_type; ?></span></span>
                                        <span class="fw-semibold text-dark">SO.No: 
                                            <?= $sales_itemdata->DocNum ?></span>
                                        <span class="fw-semibold text-dark">Quantity: <?= $sales_itemdata->Quantity ?></span>
                                         <input type="hidden" id="so_qty" name="so_qty" value="<?= $sales_itemdata->Quantity ?>">
                                    </h6>

                                    <div class="row bg-light p-3 mt-4 mb-3 rounded mx-2">
                                        <h5 class="mb-3">Routing Type</h5>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" 
                                                   name="routing_name" id="routing_name" 
                                                   placeholder="Routing Name" 
                                                   readonly>

                                            <input type="hidden" class="form-control" 
                                                   name="routing_id" id="routing_id" >
                                            <input type="hidden" class="form-control" 
                                                   name="routing_code" id="routing_code" >
                                        </div>

                                        <div class="col-md-3">
                                            <button class="btn btn-info" type="button"
                                                onClick="openRoutingModal()">
                                                Routing
                                            </button>
                                        </div>
                                    </div>


                                    <div class="container mt-4">
                                        <?php
                                        $grouped = [];
                                            // Group the items
                                        // print_r($process_mapping);

                                        foreach ($process_mapping as $item) {
                                            $layout = $item->layout_name;
                                            $group = $item->process_group;
                                            $grouped[$layout][$group][] = $item;

                                        }

                                        $layoutIndex = 0;
                                        foreach ($grouped as $layoutName => $layoutGroups):
                                            $firstLayoutItem = reset($layoutGroups)[0];
                                            ?>
                                            <div class="card mb-4 shadow-sm col-md-12 bg-light" >
                                                <div class="card-body">
                                                    <h4 class="card-title ">Layout: <?= htmlspecialchars($layoutName) ?></h4>
                                                    <input type="hidden" name="layout_id[]" value="<?= $firstLayoutItem->product_layout_id ?>">
                                                    <?php
                                                    $groupIndex = 0;
                                                    foreach ($layoutGroups as $groupName => $items):
                                                        $firstItem = $items[0];
                                                        //print_r($firstItem);
                                                        ?>
                                                        <div class="group-card" id="parameter_box_<?= $layoutIndex ?>_<?= $groupIndex ?>">
                                                            <input type="hidden" name="group_id[<?= $layoutIndex ?>][]" value="<?= $firstItem->process_group_id ?>">
                                                            <h5 class="mb-2 mt-2 text-dark fw-semibold ">
                                                                Group: <span class="text-primary"><?= htmlspecialchars($groupName) ?></span>
                                                            </h5>
                                                             <div class="impression-display" id="impression_<?= $layoutIndex ?>_<?= $groupIndex ?>"></div>
                                                      
                                                            <select
                                                            class="form-control select2 mb-2 process-select"  id="process_<?= $layoutIndex ?>_<?= $groupIndex ?>"
                                                            name="process_name[<?= $layoutIndex ?>][<?= $groupIndex ?>][]"
                                                            onchange="parametersshow(this)"
                                                            data-layout-index="<?= $layoutIndex ?>"
                                                            data-group-index="<?= $groupIndex ?>"
                                                            data-group_id="<?= $firstItem->process_group_id ?>"
                                                            data-layout_id="<?= $firstItem->product_layout_id ?>"

                                                            multiple
                                                            required
                                                            >
                                                     <option value="">Select Process</option>
                                                            <?php
                                                            $subgroupMap = [];


                                                            foreach ($items as $item) {

                                                                $sub = $item->subgroup_name ?? '';
                                                                   
                                                                $subgroupMap[$sub][] = $item;
                                                            }
                                                            foreach ($subgroupMap as $subgroup => $processes):
                                                                if (!empty(trim($subgroup))): ?>
                                                                    <optgroup label="<?= htmlspecialchars($subgroup) ?>" >

                                                                        <?php foreach ($processes as $proc): ?>
                                                                            <option value="<?= $proc->process_id ?>"  data-processmappingLayoutId='<?php echo $proc->processmappingLayoutId?>' data-process_code='<?php echo $proc->process_code ;?>' <?php if($proc->is_default=='Y'){ echo "selected" ;}?>>
                                                                                <?= htmlspecialchars($proc->process_name . ' (' . $proc->process_code . ')') ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </optgroup>
                                                                <?php else: ?>
                                                                    <?php foreach ($processes as $proc): ?>
                                                                        <option value="<?= $proc->process_id ?>"   data-processmappingLayoutId='<?php echo $proc->processmappingLayoutId?>' data-process_code='<?php $proc->process_code ;?>' <?php if($proc->is_default=='Y'){ echo "selected" ;}?>>
                                                                            <?= htmlspecialchars($proc->process_name . ' (' . $proc->process_code . ')') ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                <?php endif;
                                                            endforeach;
                                                            ?>

                                                        </select>
                                                        <!-- Impression Display for Group -->
                                                        <div class="impression-display-group mt-3 mb-3" id="group_impression_<?= $layoutIndex ?>_<?= $groupIndex ?>" style="display: none;">
                                                            <div class="alert alert-info">
                                                                <h6 class="mb-2"><i class="bi bi-calculator me-2"></i>Group Impression Summary</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <small class="text-muted">Total Group Impression:</small>
                                                                        <div class="fw-bold text-primary" id="total_group_impression_<?= $layoutIndex ?>_<?= $groupIndex ?>">0</div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <small class="text-muted">Process Count:</small>
                                                                        <div class="fw-bold text-secondary" id="process_count_<?= $layoutIndex ?>_<?= $groupIndex ?>">0</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Placeholder for parameters -->
                                                        <div class="parameter-list"></div>
                                                    </div>
                                                    <?php
                                                    $groupIndex++;
                                                endforeach;
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $layoutIndex++;
                                    endforeach;
                                    ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="container">
                                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="card-body p-4">
                                                <h5 class="mb-4 text-primary fw-semibold">Add Product Type Information</h5>
                                                <div class="row g-4">
                                                    <!-- Printing Color Required -->
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="form-floating">
                                                            <div class="form-check  mb-3">
                                                                <input type="checkbox" class="form-check-input" id="new_design" name="new_design" value="Y" onchange="newDesignText()">
                                                                <label class="form-check-label" for="new_design">New Design</label>
                                                            </div>
                                                            <div id="divnewDesign" hidden>
                                                                <input type="text" id="textnewDesign" name="new_designtext"  class="form-control" placeholder="New Design">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Repeat Design -->
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="form-floating">
                                                            <div class="form-check  mb-3">
                                                                <input type="checkbox" class="form-check-input" id="repeat_design" name="repeat_design" value="Y" onchange="repeattext()">
                                                                <label class="form-check-label" for="repeat_design">Repeat Design</label>
                                                            </div>
                                                            <div id="divRepeatDesign" hidden>
                                                                <input type="text" id="repeat_designText" name="repeat_designText" class="form-control" placeholder="Repeat Design" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!---------- correction ------------>
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="form-floating">
                                                            <div class="form-check mb-3">
                                                                <input type="checkbox" class="form-check-input" id="correction" name="correction" value="Y" onchange="correctiontext()">
                                                                <label class="form-check-label" for="correction">Correction</label>
                                                            </div>
                                                            <div id="divcorrection" hidden>
                                                                <input type="text" id="correction_text" name="correction_text" class="form-control" placeholder="Correction " >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                      
                                    </div>
                                  <!--    <div class="row container">
                                        <h5>Routing Type</h5>
                                         <div class="col-md-6">
                                             <input type="text" class="form-control" name="routing_name" id= "routing_name" placeholder="Routing Name" readonly>

                                            <input type="hidden" class="form-control" name="routing_id" id="routing_id">
                                        </div>
                                       <div class="col-md-3">
                                            <button class="btn btn-primary btn-info" name="openModal"  type="button"onClick="openRoutingModal()">Routing</button>
                                       </div>
                                    </div> -->
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    $this->load->view('include/footer');
    ?>

<!-------------------------------- modal------------------------------------------------>
<div class="modal fade" id="RoutingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Routing Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body row ">
                <div class="col-md-12 table-responsive">
                    <table class="table table-striped table-hover table-bordered align-middle" id="data-index">
                        <thead class="bg-light">
                            <tr>
                                <th></th>
                                <th>Sr. No</th>
                                <th>Routing Code</th>
                                <th>Product Type</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $x=1; foreach ($routingheders as $value): ?>
                            <tr>
                                <td width="5%"><input type="radio" name="routingid" value="<?php echo $value->id ?>"></td>
                               
                                <td><?= $x ?></td>
                               <td><?php echo $value->rout_code ;?></td>
                                <td><?= $value->product_name ?></td>
                                <td><?= $value->name ?></td>
                                <td><?= $value->description ?></td>
                                <td class="text-center">
                                    <button type="button"
                                    class="btn btn-primary view-routing btn-sm"
                                    onclick="loadRoutingDetails(<?= $value->id ?>,this)">
                                    <i class="fa fa-eye"></i> View
                                    </button>

                                    <button type="button" class="btn btn-warning btn-sm"
                                      onclick="openEditRouting('<?= base64_encode($value->id) ?>')">
                                        <i class="fa fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                           
                            <?php $x++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitRout()">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!---------------------------------------------------------------------------->
    <script>
        var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
        $(document).ready(function() {
            $('.select2').select2();

         
        });

     
     /*
        var allowedProcessesByRouting = null;

        function fetchRoutingProcesses(routingId) {
            if (!routingId) { return; }


            $.ajax({
                url: '<?= base_url("Transaction/getProcessesByRouting") ?>',
                type: 'POST',
                data: {
                    routing_id: routingId,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    // Save allowed mapping for validation
                    allowedProcessesByRouting = response;

                    // Apply mapping to each process-select
                    $('.process-select').each(function() {
                        var groupId = $(this).data('group_id');
                        var layoutId = $(this).data('layout_id');
                        if (response[layoutId] && response[layoutId][groupId]) {
                            $(this).val(response[layoutId][groupId]).trigger('change');
                        } else {
                            $(this).val([]).trigger('change');
                        }
                    });
                },
                error: function(err) {
                    console.error(err);
                    alert('Failed to fetch processes for this routing.');
                }
            });
        }
*/
        function openEditRouting(id) {
            var url = "<?= base_url('Master/edit_routing/') ?>" + id + "/1";
            window.open(url, '_blank', 'width=1000,height=700,scrollbars=yes,resizable=yes');
            $("#RoutingModal").modal('hide');
        }

        function newDesignText()
        {
            const checkbox = document.getElementById("new_design");
            const newDesign = document.getElementById("divnewDesign");
             var textnewDesign=document.getElementById("textnewDesign");
            if (checkbox.checked) {
                newDesign.removeAttribute("hidden");
            } else {
                newDesign.setAttribute("hidden", true);
                textnewDesign.value='';
            }
        }
        function repeattext()
        {
            const checkbox = document.getElementById("repeat_design");
            var repeatdesignText=document.getElementById("repeat_designText");
            const repeatDiv = document.getElementById("divRepeatDesign");
            if (checkbox.checked) {
                repeatDiv.removeAttribute("hidden");
            } else {
                repeatDiv.setAttribute("hidden", true);
                repeatdesignText.value='';

            }
        }
        function correctiontext()
        {
            const checkbox = document.getElementById("correction");
            const correctionDiv = document.getElementById("divcorrection");
             var correction_text=document.getElementById("correction_text");
            if (checkbox.checked) {
                correctionDiv.removeAttribute("hidden");
            } else {
                correctionDiv.setAttribute("hidden", true);
                correction_text.value='';
            }
        }

        function openRoutingModal(selected = null) {
        if (selected) {
            $('input[name="routingid"][value="' + selected + '"]').prop('checked', true);
        }

    $("#RoutingModal").modal('show');

    var $table = $('#data-index');
    if (!$table.length) return;

    // Remove hidden collapse rows before initializing DataTable
   // $table.find('tbody > tr.collapse-row').remove();

    // Initialize DataTable (only once)
    if (!$.fn.DataTable.isDataTable($table)) {
        $table.DataTable({
            order: [[1, 'asc']], // sort by Sr. No
            pageLength: 10,
            autoWidth: false,
            destroy: true,
            columnDefs: [
                { targets: 0, orderable: false, searchable: false }, // radio
                { targets: 6, orderable: false, searchable: false }  // action
            ]
        });
    }
}

function loadRoutingDetails(routingId, btnEl) {
    var $table = $('#data-index');
    var table = $table.DataTable ? $table.DataTable() : null;

    // Fallback if DataTable not initialized
    if (!table) {
        // Optional: your old collapse logic can go here
        return;
    }



    var $tr = $(btnEl).closest('tr');
    var row = table.row($tr);

    var rowData = row.data(); 
    var description = rowData[4]; 

   // ✅ Toggle open/close
    if ($tr.hasClass('shown')) {
        row.child.hide();
        $tr.removeClass('shown');
        return;
    }

    // Close any other open rows (optional accordion behavior)
    table.rows('.shown').every(function() {
        this.child.hide();
        $(this.node()).removeClass('shown');
    });
    // Show loading placeholder
    row.child('<div class="p-3"><div class="spinner-border text-primary"></div> Loading...</div>').show();
    $tr.addClass('shown');

    $.ajax({
        url: "<?= base_url('Master/getRoutingDetails'); ?>",
        type: "POST",
        data: { id: routingId, [csrfName]: csrfHash },
        success: function (response) {
            row.child(
                '<div class="card shadow-sm border-light mb-2">' +
                    '<div class="card-header bg-info text-white fw-bold">'+ description+'</div>' +
                    '<div class="card-body p-3">' + response + '</div>' +
                '</div>'
            ).show();
        },
        error: function () {
            row.child('<div class="alert alert-danger m-2">Failed to load details.</div>').show();
        }
    });
}
// function loadRoutingDetails(routingId) {
//     let row = $("#collapseRow" + routingId);
//     let container = $("#details" + routingId);

//     // Toggle row manually
//     if (row.is(":visible")) {
//         row.hide();
//         return;
//     }

//     // Hide other open rows if you want accordion behavior
//     $(".collapse-row").hide();

//     // Show the current row
//     row.show();

//     // If already loaded, don’t fetch again
//     if (container.data("loaded")) return;

//     container.html('<div class="text-center p-2"><div class="spinner-border text-primary"></div> Loading...</div>');

//     $.ajax({
//         url: "<?= base_url('Master/getRoutingDetails'); ?>",
//         type: "POST",
//         data: { id: routingId ,[csrfName]: csrfHash},
//         success: function(response) {
//             //console.log(response);
//            $("#details" + routingId).html(response);
//         },
//         error: function() {
//             container.html('<div class="alert alert-danger">Failed to load details.</div>');
//         }
//     });
// }

function submitRout()
{

      var selectedRadio = $('input[name="routingid"]:checked');

        if(selectedRadio.length === 0){
            alert("Please select a routing first!");
            return;
        }

        var routingId = selectedRadio.val(); 

        // Get the corresponding row
        var row = selectedRadio.closest("tr");
         var routingName = row.find("td:nth-child(5)").text().trim();
        var routingCode = row.find("td:nth-child(3)").text().trim();

    
       $("#routing_name").val(routingName);
       $("#routing_code").val(routingCode);
       $("#routing_id").val(routingId);
       $('#RoutingModal').modal('hide');

       blockUIWithLogo();

       setTimeout(() => {
        $.ajax({
        url: '<?= base_url("Transaction/getProcessesByRouting") ?>', // Create this endpoint
        type: 'POST',
        data: {
            routing_id: routingId,
            '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
        },
        dataType: 'json',
        success: function(response) {
            // Save allowed mapping for later validation; do not disable options
           // allowedProcessesByRouting = response;

            // Loop through each process-select and set selected processes
            $('.process-select').each(function() {
                var layoutIndex = $(this).data('layout-index');
                var groupIndex = $(this).data('group-index');
                var groupId = $(this).data('group_id');
                  var layoutId = $(this).data('layout_id');


                if (response[layoutId] && response[layoutId][groupId]) {
                    $(this).val(response[layoutId][groupId]).trigger('change');

                } else {
                    $(this).val([]).trigger('change'); // clear if not in routing
                }

              
                
              //  parametersshow(this);

            });
             $.unblockUI();
        },
        error: function(err) {
            console.error(err);
            alert('Failed to fetch processes for this routing.');
             $.unblockUI();
        }

    });
    },100);

}



    </script>
    <script>


    $(document).ready(function() {
   
        $('.select2').select2();
        
      
        $(".process-select").each(function() {
            parametersshow(this); 
        });

        // If editing and a routing is already set, fetch and apply allowed processes
       /* var existingRoutingId = $('#routing_id').val();
        if (existingRoutingId) {
            fetchRoutingProcesses(existingRoutingId);
        }*/
    });

var editdata = <?php echo isset($editdata) ? json_encode($editdata) : 'null'; ?>;


function parametersshow(selectElem) {
    const selectedProcessIds = $(selectElem).val();

    const layoutIndex = $(selectElem).data('layout-index');
    const groupIndex = $(selectElem).data('group-index');
    const groupid = $(selectElem).data('group_id');
    const product_layout = $(selectElem).data('layout_id');
    const product_id = $("#product_id").val();

    const targetBox = $(`#parameter_box_${layoutIndex}_${groupIndex}`);
    const selected = selectedProcessIds || [];

    // Enforce routing: if a routing is selected, block processes not in it
 /*   if (allowedProcessesByRouting && allowedProcessesByRouting[product_layout] && allowedProcessesByRouting[product_layout][groupid]) {
        const allowed = (allowedProcessesByRouting[product_layout][groupid] || []).map(String);
        const invalid = selected.filter(function(id){ return !allowed.includes(String(id)); });
        if (invalid.length > 0) {
            const filtered = selected.filter(function(id){ return allowed.includes(String(id)); });
            // Revert to only allowed selections and inform user
            $(selectElem).val(filtered).trigger('change');
            alert('This process is not in the selected routing.');
            return;
        }
    }*/


    // Clear impression display when processes change
    $(`#impression_${layoutIndex}_${groupIndex}`).html('<h6 class="text-primary mb-3">Impressions:</h6>');

    // Remove cards for unselected processes
    targetBox.find('.param-card').each(function () {
        const pid = $(this).data('process-id') + '';
        if (!selected.includes(pid)) {
            $(this).remove();
            $(`input[name='process_impression[${product_layout}][${groupid}][${pid}]']`).remove();
            $(`#impression_${layoutIndex}_${groupIndex} .process-impression[data-process-id="${pid}"]`).remove();
        }
    });

    // For each selected process
    selected.forEach(processId => {
        const processCode = $(selectElem).find(`option[value="${processId}"]`).data('process_code') || '';
        const processmappingLayoutId = $(selectElem).find(`option[value="${processId}"]`).data('processmappinglayoutid') || '';

        // Special case auto-set
        if (processCode === 'CLWIRI' && product_id === '2014') {
            setTimeout(function() {
                $('[id^="param_"][id$="_13"]').each(function() {
                    $(this).val('NO').trigger('change');
                });
            }, 500);
        }

        // If card does not exist yet, create it via AJAX
        if (targetBox.find(`.param-card[data-process-id="${processId}"]`).length === 0) {
            $.ajax({
                url: '<?= base_url("Master/fetchparametersBymapping") ?>',
                type: 'POST',
                data: {
                    processid: processId,
                    processmappingLayoutId: processmappingLayoutId,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function (res) {
                    if (res.parametersdetails && res.parametersdetails.length > 0) {
                        const paramList = res.parametersdetails;
                        const validParams = paramList.filter(param =>
                            param.parameter_ids !== null && param.parameter_ids !== ''
                        );
                        if (validParams.length === 0) return;

                        const selectedProcess = paramList.find(param => param.process_id == processId);
                        const processName = selectedProcess ? selectedProcess.process_name : 'Unknown Process';

                        let paramFields = validParams.map(param => {
                            const inputType = param.field_type || 'text';
                            const label = param.parameter_name;
                            const fieldId = `param_${processmappingLayoutId}_${param.parameter_id}`;
                            const nameAttr = `param_value[${product_layout}][${groupid}][${processId}][${param.parameter_id}]`;
                            const existingValue = getExistingParamValue(processId, param.parameter_id, param.default_value);

                            let fieldHtml = `<div class="col-md-6">
                                <label for="${fieldId}" class="form-label fw-semibold text-secondary">${label}</label>`;

                            if (inputType === 'dropdown' && param.options) {
                                const isMultiple = param.dropdown_type === 'Multiple';
                                const multipleAttr = isMultiple ? 'multiple' : '';
                                const nameSuffix = isMultiple ? '[]' : '';
                                const options = param.options.split(',').map(opt => {
                                    const selectedOption = existingValue && existingValue.includes(opt) ? 'selected' : '';
                                    return `<option value="${opt}" ${selectedOption}>${opt}</option>`;
                                }).join('');

                                fieldHtml += `<select class="form-select select2 shadow-sm rounded-2" 
                                    id="${fieldId}" name="${nameAttr}${nameSuffix}" ${multipleAttr} 
                                    data-paramId="${param.parameter_id}" 
                                    onchange="handleParameterChange(this, '${processCode}')">
                                    <option value="">Select</option>${options}</select>`;
                            } else if (inputType === 'switch') {
                                const checked = existingValue === '1' ? 'checked' : '';
                                fieldHtml += `<div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" id="${fieldId}" 
                                    name="${nameAttr}" value="1" ${checked} 
                                    onchange="handleParameterChange(this, '${processCode}')">
                                </div>`;
                            } else {
                                fieldHtml += `<input type="${inputType}" class="form-control shadow-sm rounded-2" 
                                    id="${fieldId}" name="${nameAttr}" value="${existingValue}" 
                                    data-paramId="${param.parameter_id}" 
                                    onchange="handleParameterChange(this, '${processCode}')">`;
                            }
                            fieldHtml += `</div>`;
                            return fieldHtml;
                        }).join('');

                        const finalCard = `
                            <div class="card mb-4 param-card border-0 shadow-sm bg-white rounded-3 mt-2" data-process-id="${processId}">
                                <div class="card-header bg-secondary bg-opacity-10 text-dark border-bottom border-secondary py-2 px-3">
                                    <strong><i class="bi bi-gear-fill me-2 text-info"></i>Process Code: ${processName}</strong>
                                </div>
                                <div class="card-body row g-3 px-3 pt-3 pb-2">
                                    ${paramFields}
                                </div>
                            </div>`;

                        const $card = $(finalCard);
                        targetBox.append($card);
                        $card.find('.select2').select2({ width: '100%' });
                    }
                },
                error: function () {
                    alert("Error fetching parameters.");
                }
            });
        }

       
        setTimeout(function() {
            findFormulaus(processmappingLayoutId, groupIndex, layoutIndex);
        }, 500);
    });
}



function getExistingParamValue(processId, paramId, default_value) {
   
    if (editdata && Array.isArray(editdata)) {
        const existing = editdata.find(item => {
           
            return item.process_id == processId && item.parameter_id == paramId;
        });
       
        return existing ? existing.parameter_value : default_value; 
    }
    return default_value; 
}




function findFormulaus(processmappingLayoutId,groupIndex=null,layoutIndex=null) {
   // console.log(processmappingLayoutId);
   let calculatedImpression=0;
    
    $.ajax({
        url: '<?= base_url("Master/fetchFormulaBymappingId") ?>', // Replace with your actual API endpoint
        method: 'POST',
        data:{
            processmappingLayoutId:processmappingLayoutId,
            [csrfName]: csrfHash
        },
        dataType: 'json',
        success: function(response) {
      
           
             
            if (response && response.formula) {

            calculatedImpression = handleCalculationScenario(response, processmappingLayoutId);

            displayImpression(processmappingLayoutId, layoutIndex, groupIndex, calculatedImpression);
            
          
              calculateGroupImpression(layoutIndex, groupIndex);
            }
          
           
        },
        error: function() {
            alert("Error fetching formula.");
        }

    });

   
}



function displayImpression(processmappingLayoutId, layoutIndex, groupIndex, calculatedImpression) {
  
        const processOption = $('option[data-processmappinglayoutid="' + processmappingLayoutId + '"]');
        const processId = processOption.val();
        const processName = processOption.text();
        const layoutId = processOption.closest('select').data('layout_id');
        const groupId = processOption.closest('select').data('group_id');
        
        // Remove previous impression
        $("#impression_" + layoutIndex + "_" + groupIndex + " .process-impression[data-process-id='" + processId + "']").remove();
        
        // Create or update hidden input
        if ($("input[name='process_impression[" + layoutId + "][" + groupId + "][" + processId + "]']").length === 0) {
            $("form").append('<input type="hidden" name="process_impression[' + layoutId + '][' + groupId + '][' + processId + ']" value="' + calculatedImpression + '">');
        } else {
            $("input[name='process_impression[" + layoutId + "][" + groupId + "][" + processId + "]']").val(calculatedImpression);
        }
        
        // Append new impression to the UI
        $("#impression_" + layoutIndex + "_" + groupIndex).append('<div class="process-impression mb-2" data-process-id="' + processId + '" data-impression="' + calculatedImpression + '"><span class="badge bg-info me-2">' + processName + '</span> Impression: <span class="fw-bold">' + calculatedImpression + '</span></div>');
        
    
}



function handleCalculationScenario(formulaData, processmappingLayoutId) {
  
   

     const processSelect = $('option[data-processmappinglayoutid="' + processmappingLayoutId + '"]').closest('select');
     const isPocketGroup =(processSelect.data('group_id')=='2009');


 return calculateStandardImpression(formulaData, processmappingLayoutId,isPocketGroup);
    
}


function calculateStandardImpression(formulaData, processmappingLayoutId,isPocketGroup) {
  
    
    var so_qty = $("#so_qty").val();
    var operator = formulaData.operator.trim();
    var second_operand = 1;

    
    if (formulaData.is_number == 'Y') {
        second_operand = formulaData.number;
      
    } else {
    

        if(isPocketGroup && formulaData.parameter=='7')
        {
            var secondvalue = processmappingLayoutId + "_" + formulaData.parameter;
            var paramValue = $("#param_" + secondvalue).val();
             paramValue = parseFloat(paramValue);  
            if(paramValue > 5.5)
            {
                 second_operand =  3;
            }
            else{
                 second_operand =  1;
            }
        }
       
        else{

              var secondvalue = processmappingLayoutId + "_" + formulaData.parameter;
                var paramValue = $("#param_" + secondvalue).val();
                second_operand = paramValue || 0;

               // console.log("Standard Process - Using parameter value:", second_operand);
        }

       
    }
    
    return performCalculation(so_qty, operator, second_operand, "Standard");
}


function performCalculation(so_qty, operator, second_operand, processType) {
    var calculate = 0;
    
   
    if (operator == '+') {
        calculate = parseInt(so_qty) + parseInt(second_operand);
    } else if (operator == '-') {
        calculate = parseInt(so_qty) - parseInt(second_operand);
    } else if (operator == '*') {
        calculate = parseInt(so_qty) * parseInt(second_operand);
    } else if (operator == '/') {
        calculate = parseInt(so_qty) / parseInt(second_operand);
    } else {
      
        calculate = 0;
    }
    
   
    return calculate;
}

// Function to display process-wise impression
function displayProcessImpression(processmappingLayoutId, impression, processName) {
    const processCard = $(`[data-processmappinglayoutid="${processmappingLayoutId}"]`).closest('.param-card');
    
    if (processCard.length > 0) {
        // Check if impression display already exists
        let impressionDisplay = processCard.find('.process-impression-display');
        
        if (impressionDisplay.length === 0) {
            // Create impression display if it doesn't exist
            impressionDisplay = $(`
                <div class="process-impression-display mt-2 mb-2">
                    <div class="alert alert-success py-2 px-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Process Impression:</small>
                            <span class="fw-bold text-success" id="process_impression_${processmappingLayoutId}">${impression}</span>
                        </div>
                    </div>
                </div>
            `);
            
            // Insert after the card header
            processCard.find('.card-header').after(impressionDisplay);
        } else {
            // Update existing impression display
            impressionDisplay.find(`#process_impression_${processmappingLayoutId}`).text(impression);
        }
    }
}

// Function to calculate and display group-wise impression
function updateGroupImpression(layoutIndex, groupIndex) {
    const groupCard = $(`#parameter_box_${layoutIndex}_${groupIndex}`);
    const processCards = groupCard.find('.param-card');
    
    let totalGroupImpression = 0;
    let processCount = 0;
    
    processCards.each(function() {
        const impressionDisplay = $(this).find('.process-impression-display .fw-bold');
        if (impressionDisplay.length > 0) {
            const impression = parseInt(impressionDisplay.text()) || 0;
            totalGroupImpression += impression;
            processCount++;
        }
    });
    
    // Update group impression display
    const groupImpressionDiv = $(`#group_impression_${layoutIndex}_${groupIndex}`);
    const totalGroupImpressionDiv = $(`#total_group_impression_${layoutIndex}_${groupIndex}`);
    const processCountDiv = $(`#process_count_${layoutIndex}_${groupIndex}`);
    
    if (processCount > 0) {
        groupImpressionDiv.show();
        totalGroupImpressionDiv.text(totalGroupImpression);
        processCountDiv.text(processCount);
    } else {
        groupImpressionDiv.hide();
    }
}


function handleParameterChange(inputElement, processCode) {

    const $input = $(inputElement);
    const processCard = $input.closest('.param-card');
    const processId = processCard.data('process-id');
    const paramId = $input.data('paramid'); // Get parameter ID from data attribute
    
    const product_id = $("#product_id").val();
    
    if (processCode === 'CIWIRI' && product_id === '2014') {
       
        const holeParams = processCard.find('input, select').filter(function() {
            const $paramInput = $(this);
            const paramId = $paramInput.data('paramid');
           
            return paramId == '13' 
        });
        
        holeParams.each(function() {
            const $holeParam = $(this);
            if ($holeParam.val() !== 'NO') {
                $holeParam.val('NO').trigger('change');
                //console.log('Enforced hole parameter to NO for CIWIRI process with product 2014');
            }
        });
    }
    
   
    if (paramId) {
         recalculateImpressionForParameterChange(inputElement, paramId);
       
    }
    
   
}


function recalculateImpressionForParameterChange(inputElement, paramId) {
    const $input = $(inputElement);
    const processCard = $input.closest('.param-card');
  
    if (processCard.length > 0) {
        // Check if this is a pocket group
        const isPocketGroup = processCard.closest('.group-card').find('h5').text().toLowerCase().includes('pocket');
        
        // Get layout and group indices from the group card ID
        const groupCard = processCard.closest('.group-card');
        const groupCardId = groupCard.attr('id');
        const indices = groupCardId.replace('parameter_box_', '').split('_');
        const layoutIndex = indices[0];
        const groupIndex = indices[1];
        
        // Clear the impression display before recalculating
      //  $(`#impression_${layoutIndex}_${groupIndex}`).html('<h6 class="text-primary mb-3">Impressions:</h6>');
        
        if (isPocketGroup && paramId === '2029') {
            // For count parameters, recalculate all processes in the group
            const allProcessCards = groupCard.find('.param-card');
            
            allProcessCards.each(function() {
                const $processCard = $(this);
                const firstInput = $processCard.find('input').first();
                const inputId = firstInput.attr('id');
                
                if (inputId) {
                    const processmappingLayoutId = inputId.split('_')[1];
                    if (processmappingLayoutId) {
                       
                        findFormulaus(processmappingLayoutId, groupIndex, layoutIndex);
                    }
                }
            });
        } else {
            // For size and other parameters, recalculate current process only
            const firstInput = processCard.find('input, select').first();
            const inputId = firstInput.attr('id');
            
            if (inputId) {
                const processmappingLayoutId = inputId.split('_')[1];
                
                if (processmappingLayoutId) {

                    findFormulaus(processmappingLayoutId, groupIndex, layoutIndex);
                }
            }
        }
    }
}


function calculateGroupImpression(layoutIndex, groupIndex) {
    let totalImpression = 0;
    
  
    $(`#impression_${layoutIndex}_${groupIndex} .process-impression`).each(function() {
        const impression = parseInt($(this).data('impression')) || 0;
        totalImpression += impression;
    });
    
  
    const groupCard = $(`#parameter_box_${layoutIndex}_${groupIndex}`);
    const layoutId = groupCard.find('select').data('layout_id');
    const groupId = groupCard.find('select').data('group_id');
    
    
    if ($("input[name='group_impression["+layoutIndex+"]["+groupIndex+"]']").length === 0) {
        $("form").append('<input type="hidden" name="group_impression['+layoutIndex+']['+groupIndex+']" value="'+totalImpression+'">');
    } else {
        $("input[name='group_impression["+layoutIndex+"]["+groupIndex+"]']").val(totalImpression);
    }
    
    
    const groupHeading = $(`#parameter_box_${layoutIndex}_${groupIndex} h5`);
    
    
    const existingTotal = groupHeading.find('.group-total-impression');
    
   
    if (existingTotal.length) {
  
        existingTotal.html(`<span class="badge bg-primary ms-2">Impression: ${totalImpression}</span>`);
    } else {
      
        groupHeading.find('.text-primary').after(
            `<span class="group-total-impression"><span class="badge bg-primary ms-2">Impression: ${totalImpression}</span></span>`
        );
    }
    
   
    $(`#impression_${layoutIndex}_${groupIndex} .group-total-impression:not(.badge)`).remove();
}


    </script>

