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
                            <form class="for-group" method="post">
                                  <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Doc Entry</th>
                                            <th>Item Code</th>
                                            <th>Item Name</th>
                                            <th>Product Code</th>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($items as $item): ?>
                                        <tr>
                                        <td><input type="checkbox" name="item_code" value=""></td>
                                            <td><?= $item->doc_entry ?></td>
                                            <td><?= $item->item_code ?></td>
                                            <td><?= $item->item_name ?></td>
                                            <td><?= $item->product_no ?></td>
                                            <td><?= $item->product_name ?></td>
                                            <td><?= $item->planned_qty ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                 <button class=" mt-3 btn  btn-info" style="float:right;" type="button">Plan</button>
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





</script>