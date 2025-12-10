
<?php date_default_timezone_set("Asia/Calcutta"); ?> 
<?php $settingsvalue = $this->settings_model->GetSettingsValue();

$id = $this->session->userdata('user_login_id');
$basicinfo = $this->employee_model->GetBasic($id);

		$user_type = $this->session->userdata('user_type');
 ?>

<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class='sidebar-brand' href='<?=base_url();?>'>
					<span class="sidebar-brand-text align-middle">
						<small>Kena </small>
						<!-- <sup><small class="badge bg-primary text-uppercase">Supply Chain Portal</small></sup> -->
					</span>
					<svg class="sidebar-brand-icon align-middle" width="32px" height="32px" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
						stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF" style="margin-left: -3px">
						<path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
						<path d="M20 12L12 16L4 12"></path>
						<path d="M20 16L12 20L4 16"></path>
					</svg>
				</a>

				<div class="sidebar-user">
					<div class="d-flex justify-content-center">
						<div class="flex-shrink-0">
							<!-- <img src="<?php echo base_url();?>assets/ui/img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="DHT Solutions" /> -->
						</div>
						<div class="flex-grow-1 ps-2">
							<a class="sidebar-user-title dropdown-toggle" href="#" data-bs-toggle="dropdown">
								<?= $basicinfo->first_name.' '.$basicinfo->last_name; ?>
							</a>
							<div class="dropdown-menu dropdown-menu-start">
								<?php if($this->session->userdata('user_type') != 'Temp Supplier'){ ?>
								<a class='dropdown-item' <?=$this->session->userdata('user_type') != 'Supplier' ? 'hidden' : '' ;?>  href='#'><i class="align-middle me-1" data-feather="user"></i> Profile</a>


								<a class="dropdown-item" href="<?= base_url(ANALYTICS_PATH); ?>"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
								<div class="dropdown-divider"></div>
								<a class='dropdown-item' href='<?php echo base_url(SETTING_AND_PRIVACY);?>'><i class="align-middle me-1" data-feather="settings"></i> Settings &
									Privacy</a>
								<?php }

if ($user_type === 'Temp Supplier') {
			$help_link = HELP_CENTER_TEMP_SUPPLIER;
		} elseif ($user_type === 'Supplier') {
			$help_link = HELP_CENTER_SUPPLIER;
		} else {
			$help_link = HELP_CENTER_INTEGRA_USER;
		}

								?>


								<a class="dropdown-item" target="_blank"  href="<?= base_url($help_link); ?>"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>


								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo base_url('login/logout');?>">Log out</a>
							</div>

							<div class="sidebar-user-subtitle"><?= $this->session->userdata('user_type'); ?></div>
						</div>
					</div>
				</div>



				   <ul class="sidebar-nav">

				<?php if (in_array("SUPER ADMIN", $this->session->userdata('user_role'))) { ?>

				   <li class="sidebar-header">
                Access Control
            </li>
          
              

			<li class="sidebar-item">
						<a data-bs-target="#pages" data-bs-toggle="collapse" class="sidebar-link collapsed">
							<i class="align-middle" data-feather="layout"></i> <span class="align-middle">Admin Control</span>
						</a>
						<ul id="pages" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
						<?php 
            $menuItems = [
                ['url' => 'Admin_master/role', 'icon' => 'divide-square', 'title' => 'Role'],
                ['url' => 'Admin_master/menus', 'icon' => 'plus-square', 'title' => 'Menus'],
                ['url' => 'Admin_master/sub_menus', 'icon' => 'plus-square', 'title' => 'Sub Menus'],
                ['url' => 'Admin_master/roleAccess', 'icon' => 'plus-square', 'title' => 'Role Permission']
            ];
            foreach ($menuItems as $item) { ?>
						<li class="sidebar-item"><a class='sidebar-link' href='<?php echo base_url($item['url']); ?>'><?php echo $item['title']; ?></a></li>
							<?php } ?>
						</ul>
					</li>

    
					
<?php } ?>


            <li class="sidebar-header">
                General
            </li>

            <?php 
$sidebar_menu = $this->Access_model->get_sidebar_menu();
?>

<ul class="sidebar-nav" id="sidebar">

<?php foreach ($sidebar_menu as $menu): ?>

    <?php if (empty($menu['children'])): ?>
        <!-- Top-level menu item without children -->
        <li class="sidebar-item">
            <a class="sidebar-link" href="<?php echo base_url($menu['url']); ?>">
                <i class="align-middle" data-feather="<?php echo $menu['icon']; ?>"></i>
                <span class="align-middle"><?php echo $menu['menu_name']; ?></span>
            </a>
        </li>

    <?php else: ?>
        <!-- Top-level menu item with children -->
        <li class="sidebar-item">
            <a data-bs-toggle="collapse"
               href="#menu_<?php echo $menu['menu_id']; ?>"
               class="sidebar-link collapsed"
               aria-expanded="false">
                <i class="align-middle" data-feather="<?php echo $menu['icon']; ?>"></i>
                <span class="align-middle"><?php echo $menu['menu_name']; ?></span>
            </a>

            <ul id="menu_<?php echo $menu['menu_id']; ?>"
                class="sidebar-dropdown list-unstyled collapse"
                data-bs-parent="#sidebar">

                <?php foreach ($menu['children'] as $child): ?>
                    <?php if (!empty($child['children'])): ?>
                        <!-- Second-level item with third-level submenu -->
                        <li class="sidebar-item">
                            <a data-bs-toggle="collapse"
                               href="#submenu_<?php echo $child['menu_id']; ?>"
                               class="sidebar-link collapsed"
                               aria-expanded="false">
                                <?php echo $child['menu_name']; ?>
                            </a>

                            <ul id="submenu_<?php echo $child['menu_id']; ?>"
                                class="sidebar-dropdown list-unstyled collapse">
                                <?php foreach ($child['children'] as $subchild): ?>
                                    <li class="sidebar-item">
                                        <a class="sidebar-link" href="<?php echo base_url($subchild['url']); ?>">
                                            <?php echo $subchild['menu_name']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>

                    <?php else: ?>
                        <!-- Second-level item without submenu -->
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="<?php echo base_url($child['url']); ?>">
                                <?php echo $child['menu_name']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endif; ?>

<?php endforeach; ?>

</ul>


       
			
			</div>
		</nav>