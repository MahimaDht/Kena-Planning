<?php $this->load->view('include/header'); ?>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">
        <?php $this->load->view('include/nav_sidebar'); ?>
        <div class="main">
            <?php $this->load->view('include/nav_header'); ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <h1 class="h3 mb-3">Planning Entry</h1>
                     <div class="row">
                        <div class="col-12">
                             <div class="card">
                                <div class="card-body">
                                     <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>docEntry</th>
                                                <th>Item Code</th>
                                                <th>Item Name</th>
                                                <th>Product Code</th>
                                                <th>Product Name</th>
                                                <th>Qty</th>
                                                 <th>Item UOM</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(!empty($selectedItems)): ?>
                                                <?php foreach($selectedItems as $item): ?>
                                                    <tr>
                                                        <td><?= $item->doc_entry ?></td>
                                                        <td><?= $item->item_code ?></td>
                                                        <td><?= $item->item_name ?></td>
                                                        <td><?= $item->product_no ?></td>
                                                        <td><?= $item->product_name ?></td>
                                                        <td><?= $item->item_planned_qty ?></td> 
                                                         <td><?= $item->itemuomname ?></td>
                                                         <td>
                                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planningModal" data-id="<?= $item->doc_entry ?>">Planning</button>
                                                           
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                     </table>
                                </div>
                             </div>
                        </div>
                     </div>
                </div>
            </main>
            <?php $this->load->view('include/footer'); ?>
        </div>
    </div>
</body>

<!-- Planning Modal -->
<div class="modal fade" id="planningModal" tabindex="-1" aria-labelledby="planningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="planningModalLabel">Planning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="planningForm">
                    <input type="hidden" name="doc_entry" id="modal_doc_entry">
                    <div class="mb-3">
                        <label for="machine_id" class="form-label">Machine</label>
                        <select class="form-select" name="machine_id" id="machine_id" required>
                            <option value="">Select Machine</option>
                            <?php if (isset($machines)): ?>
                                <?php foreach ($machines as $machine): ?>
                                    <option value="<?= $machine->id ?>"><?= $machine->machine_name ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="planning_datetime" class="form-label">Date & Time</label>
                        <input type="datetime-local" class="form-control" name="planning_datetime" id="planning_datetime" required>
                    </div>
                    <div class="mb-3">
                        <label for="planning_qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="planning_qty" id="planning_qty" required>
                    </div>  
                </form>
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Plan</button>
            </div>
        </div>
    </div>
</div>
