
<?php date_default_timezone_set("Asia/Calcutta"); 
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Integra SCM">
	<meta name="author" content="DHTSOL">
	<meta name="keywords" content="DHT Solutions">

	<link rel="preconnect" href="https://fonts.gstatic.com/">
	<link rel="shortcut icon" href="<?php echo base_url();?>assets/ui/img/icons/favicon.ico" />
<!-- Notyf CSS -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/ui/notyf/notyf.min.css">

	<link rel="canonical" href="https://dht.net.in" />

	<title><?= !empty($title) ? $title : 'kena' ?></title>

	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/ui/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/ui/select2/select2.min.css">

	<!-- Choose your prefered color scheme -->
	 <link href="<?php echo base_url();?>assets/ui/css/light.css" rel="stylesheet">

	<style>
		body {
			opacity: 0;
		}
		.table-responsive {
						    overflow-x: auto;
						    width: 100%;
						}
		/* Smaller font on mobile */
@media (max-width: 768px) {
  table.dataTable thead th,
  table.dataTable tbody td {
    font-size: 12px;
    white-space: nowrap;
  }
}
	</style>
	<!-- END SETTINGS -->
</head>