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
                    <h1 class="h3 mb-3">Items To Planned</h1>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-responsive">
                            <form class="for-group" method="post" action="<?php echo base_url('Transaction/savePlanItems'); ?>">
                               <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <?php 
                                $groupedItems = [];
                                if (isset($items)) {
                                    foreach ($items as $item) {
                                        $groupedItems[$item->doc_entry][] = $item;
                                    }
                                }
                                ?>

                                <?php foreach ($groupedItems as $docEntry => $docItems): ?>
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Doc Entry: <?php echo $docEntry; ?></h5>
                                            <table class="table table-striped datatable-item">
                                                <thead>
                                                     <tr>
                                                        <th><input type="checkbox" class="select-all" data-docentry="<?php echo $docEntry; ?>" onclick="toggleSelectAll(this)"></th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Product Code</th>
                                                        <th>Product Name</th>
                                                        <th>Qty</th>
                                                        <th>Item UOM</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($docItems as $item): ?>
                                                        <tr>
                                                            <td><input type="checkbox" name="item[]" value="<?php echo $docEntry . '|' . $item->item_code . '|' . $item->product_no . '|' . $item->item_planned_qty; ?>" data-docentry="<?php echo $docEntry; ?>">    
                                                        
                                                        </td>

                                                            <td><?php echo $item->item_code; ?></td>
                                                            <td><?php echo $item->item_name; ?></td>
                                                            <td><?php echo $item->product_no; ?></td>
                                                            <td><?php echo $item->product_name; ?></td>
                                                            <td><?php echo $item->item_planned_qty; ?></td>
                                                              <td><?php echo $item->itemuomname; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                 <button class=" mt-3 btn  btn-info" style="float:right;" type="submit" onclick="return planItems()">Plan</button>
                              </form>
                           </div>
                       </div>
                   </div>


               </div>

           </div>
       </main>
       <?php 
    $this->load->view('include/footer');
?>


<script>

var docEntries = <?php echo json_encode($docEntries); ?>;
csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
csrfHash = '<?= $this->security->get_csrf_hash(); ?>';

   
function planItems() {
    var selectedItems = $('input[name="item[]"]:checked');
    if (selectedItems.length === 0) {
        alert("Please select at least one item.");
        return false;
    }

    // var firstValue = selectedItems.first().val();
    // var allSame = true;

    // selectedItems.each(function() {
    //     if ($(this).val() !== firstValue) {
    //         allSame = false;
    //         return false;
    //     }
    // });

    // if (!allSame) {
    //     alert("both item different");
    //     return false;
    // }

    return true;
   
    
}
</script>
<script>
 if (typeof toggleSelectAll !== 'function') {
   function toggleSelectAll(source) {
        const docEntry = $(source).data('docentry');
        $(`input[name="item[]"][data-docentry="${docEntry}"]`).prop('checked', source.checked);
    }
}
</script>