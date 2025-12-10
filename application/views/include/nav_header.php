<?php 
$id = $this->session->userdata('user_login_id');
$basicinfo = $this->employee_model->GetBasic($id);
?>
<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<!-- <li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator">1</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									1 New Notifications
								</div>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2">
												<i class="text-danger" data-feather="alert-circle"></i>
											</div>
											<div class="col-10">
												<div class="text-dark">Dummy Notification</div>
												<div class="text-muted small mt-1">Dummy.</div>
												<div class="text-muted small mt-1">30m ago</div>
											</div>
										</div>
									</a>
								</div>
								<div class="dropdown-menu-footer">
									<a href="#" class="text-muted">Show all notifications</a>
								</div>
							</div>
						</li> -->
					
		
						<li class="nav-item">
							<a class="nav-icon js-fullscreen d-none d-lg-block" href="#">
								<div class="position-relative">
									<i class="align-middle" data-feather="maximize"></i>
								</div>
							</a>
						</li>
						<li class="nav-item dropdown">
	<a class="nav-icon pe-md-0 dropdown-toggle" href="#" data-bs-toggle="dropdown">
		<?php
		$image_path = !empty($basicinfo->em_image) 
			? base_url(htmlspecialchars($basicinfo->em_image)) 
			: base_url('assets/ui/img/photos/no_user.png');

		$full_name = htmlspecialchars($basicinfo->first_name . ' ' . $basicinfo->last_name);
		?>
		<img src="<?= $image_path; ?>" class="avatar img-fluid rounded" alt="<?= $full_name; ?>" />
	</a>

	<div class="dropdown-menu dropdown-menu-end">
		<?php
		$user_type = $this->session->userdata('user_type');

		// Show Profile, Analytics, and Settings only if not Temp Supplier
		if ($user_type !== 'Temp Supplier') {
			// Show Profile only for Supplier
			if ($user_type === 'Supplier') {
				echo '<a class="dropdown-item" href="' . base_url(PROFILE_PATH) . '">
						<i class="align-middle me-1" data-feather="user"></i> Profile
					</a>';
			}
			echo '<a class="dropdown-item" href="' . base_url(ANALYTICS_PATH) . '">
					<i class="align-middle me-1" data-feather="pie-chart"></i> Analytics
				</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="' . base_url(SETTING_AND_PRIVACY) . '">
					<i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy
				</a>';
		}

		// Help Center links by user type
		echo '<div class="dropdown-divider"></div>';
		if ($user_type === 'Temp Supplier') {
			$help_link = HELP_CENTER_TEMP_SUPPLIER;
		} elseif ($user_type === 'Supplier') {
			$help_link = HELP_CENTER_SUPPLIER;
		} else {
			$help_link = HELP_CENTER_INTEGRA_USER;
		}
		?>
		<a class="dropdown-item" target="_blank" href="<?= base_url($help_link); ?>">
			<i class="align-middle me-1" data-feather="help-circle"></i> Help Center
		</a>

		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="<?= base_url('login/logout'); ?>">
			Log out
		</a>
	</div>
</li>

					</ul>
				</div>
				
			</nav>
