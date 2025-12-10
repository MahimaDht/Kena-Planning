<?php 
            $this->load->view('include/header');


        $user_login_id = $this->session->userdata('user_login_id');
        $employee_data = $this->employee_model->getallusers($user_login_id);
        $user_type = $this->session->userdata('user_type');


          $ref_id = '';
        if (!empty($employee_data->ref_id)) {
            $ref_id = $employee_data->ref_id;
        }
$sql = "SELECT 
            SUM(BasicValue) AS totBasicValue, 
            SUM(TotalValue) AS totalValue, 
            Currency
        FROM OPOR 
        WHERE Vendor = ? 
          AND DocStatus != 'X' 
          AND AuthBy <> '' 
          AND ChangeLog = (
              SELECT MAX(ChangeLog) 
              FROM OPOR b 
              WHERE b.Vendor = ? 
                AND b.DocStatus != 'X' 
                AND b.AuthBy <> '' 
                AND CONCAT(b.DocYear, b.Doc, b.DocNo) = CONCAT(OPOR.DocYear, OPOR.Doc, OPOR.DocNo)
          )
        GROUP BY Currency";

$query = $this->db->query($sql, [$ref_id, $ref_id]);
$poValue = $query->row();
// print_r($poValue);

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

					<h1 class="h3 mb-3"><?=$title;?></h1>

					<div class="row">
						<div class="col-xl-6 col-xxl-5 d-flex">
							<div class="w-100">
								<div class="row">
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Total PO Value (<?= $poValue->Currency ?>)</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="truck"></i>
														</div>
													</div>
												</div>
<?php if (!empty($poValue)): ?>
    <h1 class="mt-1 mb-3">
        <?= $poValue->Currency ?> <?= number_format($poValue->totalValue, 2) ?>
    </h1>
<?php endif; ?>

												<div class="mb-0">
													<!-- <span class="badge badge-primary-light">-3.65%</span> -->
													<!-- <span class="text-muted">Since last week</span> -->
												</div>
											</div>
										</div>
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">NA</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="users"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">14.212</h1>
												<div class="mb-0">
													<!-- <span class="badge badge-success-light">5.25%</span> -->
													<!-- <span class="text-muted">Since last week</span> -->
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Earnings</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															₹
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">₹21.300</h1>
												<div class="mb-0">
													<!-- <span class="badge badge-success-light">6.65%</span> -->
													<!-- <span class="text-muted">Since last week</span> -->
												</div>
											</div>
										</div>
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Orders</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="shopping-cart"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3">64</h1>
												<div class="mb-0">
													<!-- <span class="badge badge-danger-light">-2.25%</span> -->
													<!-- <span class="text-muted">Since last week</span> -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-6 col-xxl-7">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<div class="float-end">
								<!-- 		<form class="row g-2">
											<div class="col-auto">
												<select class="form-select form-select-sm bg-light border-0">
													<option>Jan</option>
													<option value="1">Feb</option>
													<option value="2">Mar</option>
													<option value="3">Apr</option>
												</select>
											</div>
											<div class="col-auto">
												<input type="text" class="form-control form-control-sm bg-light rounded-2 border-0" style="width: 100px;"
													placeholder="Search..">
											</div>
										</form> -->
									</div>
									<h5 class="card-title mb-0">Recent Movement</h5>
								</div>
								<div class="card-body pt-2 pb-3">
									<div class="chart chart-sm">
										<canvas id="chartjs-dashboard-line"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>

					

					<div class="row">
						
						<div class="col-12 col-lg-5 col-xxl-5 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">
								 <div class="card-actions float-end">
										<div class="dropdown position-relative">
											
<select id="financialYearSelect" class="form-select">
    <?php 
        $finYear = getFinancialYear();
        foreach($finYear as $v) {
            $from = date('Y-m-d', strtotime($v->FinFromDt));
            $to = date('Y-m-d', strtotime($v->FinToDt));
            $label = $v->FinYrFormat;
            $value = $from . '|' . $to;
            echo "<option value='$value'>$label</option>";
        }
    ?>
</select>

										</div>
									</div> 
									<h5 class="card-title mb-0">Monthly Sales</h5>
								</div>
								<div class="card-body d-flex w-100">
									<div class="align-self-center chart chart-lg">
										<canvas id="chartjs-dashboard-bar"></canvas>
									</div>
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
		document.addEventListener("DOMContentLoaded", function() {
			var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
			var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
			gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
			gradientLight.addColorStop(1, "rgba(215, 227, 244, 0)");
			var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
			gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
			gradientDark.addColorStop(1, "rgba(51, 66, 84, 0)");
			// Line chart
			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Sales (₹)",
						fill: true,
						backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
						borderColor: window.theme.primary,
						data: [
							2115,
							1562,
							1584,
							1892,
							1587,
							1923,
							2566,
							2448,
							2805,
							3438,
							2917,
							3327
						]
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					tooltips: {
						intersect: false
					},
					hover: {
						intersect: true
					},
					plugins: {
						filler: {
							propagate: false
						}
					},
					scales: {
						xAxes: [{
							reverse: true,
							gridLines: {
								color: "rgba(0,0,0,0.0)"
							}
						}],
						yAxes: [{
							ticks: {
								stepSize: 1000
							},
							display: true,
							borderDash: [3, 3],
							gridLines: {
								color: "rgba(0,0,0,0.0)",
								fontColor: "#fff"
							}
						}]
					}
				}
			});
		});
	</script>

<script>
	let chart;

	function loadVendorChart() {
		const selected = document.getElementById('financialYearSelect').value;
		if (!selected) return;

		blockUIWithLogo("We are processing your request. Please wait.");

		const [from, to] = selected.split('|');
		const supplier_code = '<?= $ref_id ?>';

		fetch(`<?= base_url('chart/get_vendor_po_chart_data') ?>?from=${from}&to=${to}&vendor=${supplier_code}`)
			.then(response => response.json())
			.then(data => {
				if (!data.status) {
					console.error('No data received');
					$.unblockUI();
					return;
				}

				const ctx = document.getElementById("chartjs-dashboard-bar").getContext('2d');

				const chartData = {
					labels: data.labels,
					datasets: [{
						label: "This Year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: data.values,
						barPercentage: 0.75,
						categoryPercentage: 0.5
					}]
				};

const options = {
	maintainAspectRatio: false,
	legend: { display: false },
	scales: {
		yAxes: [{
			gridLines: { display: false },
			stacked: false,
			ticks: {
				stepSize: 20000,
				callback: function(value) {
					return '₹' + value.toLocaleString(); // Axis format
				}
			}
		}],
		xAxes: [{
			stacked: false,
			gridLines: { color: "transparent" }
		}]
	},
	tooltips: {
		callbacks: {
			label: function(tooltipItem, data) {
				let value = tooltipItem.yLabel;
				return '₹' + value.toLocaleString(); // Tooltip format
			}
		}
	}
};


				if (chart) chart.destroy();

				chart = new Chart(ctx, {
					type: "bar",
					data: chartData,
					options: options
				});

				$.unblockUI();
			})
			.catch(error => {
				console.error('Error loading chart data:', error);
				$.unblockUI();
			});
	}

	// Call on dropdown change
	document.getElementById('financialYearSelect').addEventListener('change', loadVendorChart);

	// Call on page load
	document.addEventListener("DOMContentLoaded", loadVendorChart);
</script>
