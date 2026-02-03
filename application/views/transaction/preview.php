<?php
$this->load->view('include/header');
?>
<?php

$grouped_data = [];
foreach ($process as $row) {
    $layout_id = $row->layout_id;
    $group_id = $row->group_id;
    $process_id = $row->process_id;
    $parameter_id = $row->parameter_id;

    $grouped_data[$layout_id]['layout_name'] = $row->layoutName;

    $grouped_data[$layout_id]['groups'][$group_id]['group_name'] = $row->group_name;
    
 
    if (!isset($grouped_data[$layout_id]['groups'][$group_id]['group_impression']) && isset($row->group_impression)) {
        $grouped_data[$layout_id]['groups'][$group_id]['group_impression'] = $row->group_impression;
    }

    $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_name'] = $row->process_name;
    $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_code'] = $row->process_code;
    
    // Store process impression if available
    if (isset($row->process_impression)) {
        $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['process_impression'] = $row->process_impression;
    }

    if (!empty($parameter_id)) {
        $grouped_data[$layout_id]['groups'][$group_id]['processes'][$process_id]['parameters'][] = [
            'name' => $row->parameter_name,
            'value' => $row->parameter_value
        ];
    }
}
?>
<?php $this->load->view('include/header'); ?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">
        <?php $this->load->view('include/nav_sidebar'); ?>
        <div class="main">
            <?php $this->load->view('include/nav_header'); ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h3 class="mb-4 text-primary">Preview Page</h3>
                    <!-- Sales Information -->
                    <form class="form-group" method="post" action="<?php echo base_url()?>Transaction/updatePreviewAndPrint">
                      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body px-4 py-4">
                            <h5 class="text-secondary fw-bold mb-3">Sales Information</h5>
                            <input type="hidden" name="id" id="id" value="<?php echo $process_header->id ?>">
                            <!-- <input type="hidden" name="sales_item_id" value="<?php echo $sales_item_id?>">
                            <input type="hidden" name="sales_header" value="<?= $salesdata->header_id ?>"> -->
                             <input type="hidden" name="doc_entry" value="<?= $salesdata->DocEntry ?>">
                              <input type="hidden" name="salesitem_line" value="<?= $salesdata->LineNum ?>">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="text-muted ">ID</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->header_id ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Doc No</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->DocNum ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Doc Date</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->DocDate ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Card Name</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->CardName ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Item Name</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->item_name ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Product Type</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->u_product_type ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Description</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->Dscription ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-muted ">Qty</label>
                                    <p class="fw-semibold text-dark"><?= $salesdata->Quantity ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assign Process -->
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body px-4 py-4">
                            <h5 class="text-secondary fw-bold mb-3">Assigned Process</h5>

                            <?php foreach ($grouped_data as $layout): ?>
                                <div class="mb-4 ps-2 border-start border-3 border-primary">
                                    <h6 class="text-primary fw-semibold mb-2">Layout: <?= htmlspecialchars($layout['layout_name']) ?></h6>

                                    <?php foreach ($layout['groups'] as $group): ?>
                                        <div class="ms-3 mb-3 ps-3 border-start border-2 border-secondary">
                                            <h5 class="text-dark fw-semibold mb-2 d-flex justify-content-between">
                                                <span>Group: <?= htmlspecialchars($group['group_name']) ?></span>
                                                <?php if (isset($group['group_impression'])): ?>
                                                <span class="badge bg-success ">Impression: <?= htmlspecialchars($group['group_impression']) ?></span>
                                                <?php endif; ?>
                                            </h5>
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($group['processes'] as $process): ?>
                                                    <li class="list-group-item bg-light rounded mb-2">
                                                        <div class="fw-semibold text-dark d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <?= htmlspecialchars($process['process_name']) ?>
                                                                <span class="text-muted">(<?= htmlspecialchars($process['process_code']) ?>)</span>
                                                            </div>
                                                            <?php if (isset($process['process_impression'])): ?>
                                                            <span class="badge bg-info">Impression: <?= htmlspecialchars($process['process_impression']) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <?php if (!empty($process['parameters'])): ?>
                                                            <ul class="ps-3 mt-2 mb-0 ">
                                                                <?php foreach ($process['parameters'] as $param): ?>
                                                                    <li class="text-muted">
                                                                        <?= htmlspecialchars($param['name']) ?>: 
                                                                        <span class="fw-semibold text-dark"><?= htmlspecialchars($param['value']) ?></span>
                                                                    </li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        <?php else: ?>
                                                            <p class="text-muted  mb-0 mt-1">No parameters</p>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                       <div class="col-12">
                        <h5 class="mb-4 text-primary fw-semibold">Add Product Type Information</h5>

                        <!-- Row to display cards side by side -->
                        <div class="row g-4">
                            <!-- New Design Card -->
                            <div class="col-md-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong class="text-dark">New Design:</strong>
                                            <span class="badge <?= isset($process_header->new_design) && $process_header->new_design === 'Y' ? 'bg-success' : 'bg-danger' ?>">
                                                <?= isset($process_header->new_design) && $process_header->new_design === 'Y' ? 'Yes' : 'No' ?>
                                            </span>
                                        </div>
                                        <div id="preview_newDesign" class="text-muted">
                                            <?= isset($process_header->new_design_text) ? htmlspecialchars($process_header->new_design_text) : 'N/A' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Repeat Design Card -->
                            <div class="col-md-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong class="text-dark">Repeat Design:</strong>
                                            <span class="badge <?= isset($process_header->repeat_design) && $process_header->repeat_design === 'Y' ? 'bg-success' : 'bg-danger' ?>">
                                                <?= isset($process_header->repeat_design) && $process_header->repeat_design === 'Y' ? 'Yes' : 'No' ?>
                                            </span>
                                        </div>
                                        <div id="preview_repeatDesign" class="text-muted">
                                            <?= isset($process_header->repeat_design_text) ? htmlspecialchars($process_header->repeat_design_text) : 'N/A' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Correction Card -->
                            <div class="col-md-4">
                                <div class="card shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <strong class="text-dark">Correction:</strong>
                                            <span class="badge <?= isset($process_header->correction) && $process_header->correction === 'Y' ? 'bg-success' : 'bg-danger' ?>">
                                                <?= isset($process_header->correction) && $process_header->correction === 'Y' ? 'Yes' : 'No' ?>
                                            </span>
                                        </div>
                                        <div id="preview_correction" class="text-muted">
                                            <?= isset($process_header->correction_text) ? htmlspecialchars($process_header->correction_text) : 'N/A' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                        </div>
                        
                        <div class="col-12">
                            <button class="btn btn-info mb-2 " type="submit" style="float:right;margin-right: 20px;"  name="submit" id="submit">Print</button>

                          <a class="btn btn-secondary mb-2" style="float:right;margin-right: 20px;" name="cancel" id="cancel" href="<?= base_url('Master/showAssignProcessForm/'.$salesdata->DocEntry.'/'.$salesdata->LineNum.'/'.($process_header->id))?>">Back</a>


                        </div>
                    </div>
                </form>
                </div>

            </main>
            <?php $this->load->view('include/footer'); ?>
        </div>
              
    </div>

    <!--   </div> -->
