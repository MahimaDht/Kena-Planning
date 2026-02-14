
<?php
$this->load->view('include/header');
?>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
    <div class="wrapper">
        <!-- nav_sidebar -->
        <?php $this->load->view('include/nav_sidebar'); ?>
        <div class="main">
            <!-- nav_header -->
            <?php $this->load->view('include/nav_header'); ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">SO For Planning</h5>
                                </div>
                            
                                <form action="<?php echo base_url('Transaction/save_plannedSO') ?>" method="post">
                                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                <div class="card-body table-responsive">
                                    <table class="table table-bordered  align-middle" >
                                        <thead class="table-primary text-uppercase small">
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Doc NO</th>
                                                <th>Card Name</th>
                                                <th class="text-center">Plan Seq</th>
                                                <th width="10%">Machine</th>
                                                <th >Raw Material</th>
                                                <th class="text-center">UPS</th>
                                                <th>Item Name</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Stock</th>
                                                <th class="text-center">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        // Pre-processing to calculate rowspans
                                        $groupedItems = [];
                                        foreach ($plannedSO as $value) {
                                            $key = $value->planSeq;
                                            if (!isset($groupedItems[$key])) {
                                                $groupedItems[$key] = [];
                                            }
                                            $groupedItems[$key][] = $value;
                                        }
                                        
                                        $x = 1;
                                        foreach ($groupedItems as $groupKey => $items) {
                                            $rowCount = count($items);

                                            foreach ($items as $index => $item) {
                                                $stock = getItemStockFromAPI($item->raw_material_code);
                                               
                                                ?>
                                                <tr>
                                                    <?php if ($index === 0): ?>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="text-center text-primary fw-bold" style="border-right: 2px solid #dee2e6;"><?php echo $x; ?></td>
                                                    <?php endif; ?>

                                                    <td class="text-danger fw-bold">
                                                        <?php echo $item->DocNum; ?>

                                                        <input type="hidden" name="doc_entry[]" value="<?php echo $item->DocEntry ?>">
                                                        <input type="hidden" name="line_num[]" value="<?php echo $item->LineNum ?>">
                                                        <input type="hidden" name="planSeq[]" value="<?php echo $item->planSeq ?>">
                                                        <input type="hidden" name="planned_id[]" value="<?php echo $item->id ?>">
                                                    </td>
                                                    <td class="small text-muted"><?php echo $item->CardName; ?></td>

                                                    <?php if ($index === 0): ?>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="text-center">
                                                            <span class="badge bg-primary px-3"><?php echo $item->planSeq; ?></span>
                                                        </td>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="fw-bold" >
                                                            <select name="machine_id[<?php echo trim($item->planSeq); ?>]" style="width: 150px;" class="form-select form-select-sm">
                                                                <option value="">Select Machine</option>
                                                                <?php foreach ($printing_machines as $machine): ?>
                                                                    <option value="<?php echo $machine->code; ?>" <?php if($item->machine==$machine->code){ echo "selected"; } ?>><?php echo $machine->machine_name; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td rowspan="<?php echo $rowCount; ?>"><?php echo $item->raw_material; ?></td>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="text-center"><?php echo $item->UPS; ?></td>
                                                        
                                                        <td rowspan="<?php echo $rowCount; ?>"><?php echo $item->Dscription; ?></td>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="text-center fw-bold text-primary"><?php echo $item->OpenQty; ?></td>
                                                        <td rowspan="<?php echo $rowCount; ?>" class="text-center">
                                                            <span class="badge <?php echo $stock > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                                <?php echo $stock; ?>
                                                            </span>
                                                        </td>
                                                        <td rowspan="<?php echo $rowCount; ?>">

                                                        <input type="date" class="form-control form-control-sm" name="plan_date[<?php echo trim($item->planSeq); ?>]" value="">

                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                                <?php
                                            }
                                            $x++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer text-end p-3">
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Planning</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
<?php $this->load->view('include/footer'); ?>

