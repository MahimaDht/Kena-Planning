<?php

        $user_type = $this->session->userdata('user_type');

        
if ($user_type === 'Temp Supplier') {
            $help_link = HELP_CENTER_TEMP_SUPPLIER;
        } elseif ($user_type === 'Supplier') {
            $help_link = HELP_CENTER_SUPPLIER;
        } else {
            $help_link = HELP_CENTER_INTEGRA_USER;
        }
?>
<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a href="https://dht.net.in/" target="_blank" class="text-muted"><strong>DHT Solutions</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="<?php base_url(SUPPORT_PATH); ?>">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" target="_blank" href="<?= base_url($help_link); ?>">Help Center</a>
								</li>
							<!-- 	<li class="list-inline-item">
									<a class="text-muted" href="#">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="#">Terms</a>
								</li> -->
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

<!-- Notyf JS -->
<script src="<?php echo base_url();?>assets/ui/notyf/notyf.min.js"></script>

    <script src="<?php echo base_url();?>assets/ui/js/app.js"></script>
	<script src="<?php echo base_url();?>assets/ui/js/jquery-3.7.1.js"  crossorigin="anonymous"></script>
    <script src="<?php echo base_url();?>assets/ui/js/jquery.dataTables.min.js"></script>

    <script src="<?php echo base_url();?>assets/ui/js/datatables.js"></script>

     <script src="<?php echo base_url(); ?>assets/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url();?>assets/ui/plugins/tinymce/tinymce.min.js"></script>
       
    <script src="<?php echo base_url();?>assets/ui/js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url();?>assets/ui/select2/select2.min.js"></script>

    
	<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get the select element
    var multiSelect = document.querySelector(".choices-multiple");

    // Store Choices instance globally
    window.choicesInstance = null;

    // Initialize Choices.js only if element exists
    if (multiSelect) {
        window.choicesInstance = new Choices(multiSelect, { removeItemButton: true,
            allowHTML: true });
    }

    // Flatpickr initializations
    flatpickr(".flatpickr-minimum");
    flatpickr(".flatpickr-datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
    });
    flatpickr(".flatpickr-human", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
    });
    flatpickr(".flatpickr-multiple", {
        mode: "multiple",
        dateFormat: "Y-m-d"
    });
    flatpickr(".flatpickr-range", {
        mode: "range",
        dateFormat: "Y-m-d"
    });
    flatpickr(".flatpickr-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
});

	</script>    

<script>
      tinymce.init({
      selector: '.tinyeditor',
      plugins: 'lists',
      toolbar: 'numlist | bullist | outdent indent',
      lists_indent_on_tab: false,
      height : "200",
  });


</script>


<?php
// Define an array of session keys to check for
$session_keys = array('success', 'error', 'default', 'warning');
$type = '';
// Loop through the session keys and check if they are set
foreach ($session_keys as $key) {


    if ($this->session->flashdata($key)) {

        // Define the notification type and message based on the session key
        switch ($key) {
            case 'success':
                $type = 'success';
                break;
            case 'error':
                $type = 'error';
                break;
            case 'default':
                $type = 'default';
                break;
            case 'warning':
                $type = 'warning';
                break;
        }
    }

}
        ?>


<?php

    if ($this->session->flashdata($type)) { ?>
      <script type="text/javascript">

    window.onload = function () {
        var notyf = new Notyf(); // Initialize Notyf

        var message = '<?=$this->session->flashdata($type);?>';
        var type = '<?=$type?>';
        var duration = 5000;
        var ripple = 1;
        var dismissible = 1;
        var positionX = 'right';
        var positionY = 'bottom';

       
        notyf.open({
            type,
            message,
            duration,
            ripple,
            dismissible,
            position: {
                x: positionX,
                y: positionY
            }
        });
    };
</script>


        <?php
 
}
?>


<script type="text/javascript">
    
    const alerts = new Notyf({
        duration: 5000,
        position: {
            x: 'right',
            y: 'bottom'
        }
    });

    function confirmfun() {
    if (confirm("Are You Sure To Submit Data") == false) {
        return false; 
    } else {
        blockUIWithLogo(); 
        return true; 
    }
    }

    function blockUIWithLogo(message = 'We are processing your request. Please wait.') {

    $.blockUI({

        message: `
            <div style="text-align: center;">
                <img src="<?= base_url() ?>assets/integra_logo.svg" alt="Company Logo" style="width: 200px; margin-bottom: 10px;">
                <h5><i class="fa fa-spinner fa-spin"></i> ${message}</h5>
            </div>
        `,
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: 'rgb(255 255 255)',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: 0.8,
            color: '#fff',
            width: '300px',
            left: 'calc(50% - 150px)',
        }
    });
    }


</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Datatables Fixed Header with Sorting Disabled
        $("#datatable_index").DataTable({
            fixedHeader: true,
            pageLength: 25,
         
            ordering: false ,
             dom: 'Bfrtip', // Disable sorting
             buttons: [
             'csv', 'excel'
                    ]
        });
    });
function getgstdetails() { 
    var gstnum = $("#gst_no").val(); 
    if (gstnum.length==15) { 
 
        blockUIWithLogo("We are processing your request. Please wait.");
      $.ajax({ 
        url: '<?= GST_API_URL ?>', 
        type: "GET", 
        data: { gstnum: gstnum }, 
        cache: false, 
        dataType:'json', 
        success: function(result) 
        { 
          if (result.status=='error') { 
              alert("Error: " + result.message); 
                $.unblockUI();
          } else { 
            var data = result.data; 
            // console.log(data);
            let companyName = data.TradeName && data.TradeName.trim() !== '' ? data.TradeName : data.LegalName;
            $("#company_name").val(companyName);
            $("#address_1").val(data.address_1+', '+data.address_2); 
            $("#place").val(data.place); 
            $("#pin_code").val(data.pin_code); 
            $("#gst_portal_status").val(data.sts); 
            // alert("Data Successfully Fatched"); 
            $.unblockUI();
          } 
        } 
      }); 
    } 
    else{ 
      alert("Invalid GST No."); 
                $.unblockUI();
      return false; 
    } 
  } 
function formatNumber(value) {
    let num = parseFloat(value); // convert string or number to float
    if (isNaN(num)) return value; // if not a valid number, return original

    if (num === 0) return '0';
    if (Number.isInteger(num)) return num.toFixed(2); // 1 -> 1.00

    let rounded = num.toFixed(3).replace(/\.?0+$/, ''); // trim trailing zeros

    // Ensure at least two decimal digits (e.g., 1.1 -> 1.10)
    if (/^\d+\.\d$/.test(rounded)) {
        return rounded + '0';
    }

    return rounded;
}

</script>

    <script>
    <?php if(isset($urldata)){
    if($urldata->parent_id!=''){ ?>
        $("#link_sibar_<?php echo $urldata->parent_id ?>").addClass('active');
        $("#ul_sibar_<?php echo $urldata->parent_id ?>").css("display", "block");
        $("#li_sibar_<?php echo $urldata->menu_id ?>").addClass('active');
    <?php } else{ ?>
        $("#link_sibar_<?php echo $urldata->menu_id ?>").addClass('active');
        $("#ul_sibar_<?php echo $urldata->menu_id ?>").css("display", "block");
        $("#li_sibar_<?php echo $urldata->menu_id ?>").addClass('active');
    <?php } }?>
    </script>
    
</body>


</html>