<?php $this->load->view('partials/header'); ?>
<?php $this->load->view('partials/sidebar'); ?>
<?php $this->load->view('partials/profile_header'); ?>

<div class="content transition-[margin,width] duration-100 xl:pl-3.5 pt-[54px] pb-16 relative z-10 group mode content--compact xl:ml-[275px] mode--light [&.content--compact]:xl:ml-[91px]">
                <div class="mt-16 px-5">
            <div class="content transition-[margin,width] duration-100 px-5 mt-[65px] pt-[31px] pb-16 relative z-10 content--compact xl:ml-[275px] [&.content--compact]:xl:ml-[91px]">
                <div class="container">
                    <div class="grid grid-cols-12 gap-x-6 gap-y-10">
                        <div class="col-span-12">
                            <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
                                <div class="relative col-span-12 xl:col-span-3">
                                    <div class="sticky top-[104px]">
                                        <div class="box box--stacked flex flex-col px-5 pb-6 pt-5">
                                            <a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($basic->em_id); ?>" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary active">
                                                <i data-tw-merge="" data-lucide="app-window" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Profile Info
                                            </a>
                                            <a href="dagger-settings-email-settings.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="mail-check" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Email Settings
                                            </a>
                                            <a href="<?php echo base_url(); ?>employee/reset_password_page?I=<?php echo base64_encode($basic->em_id); ?>" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="key-round" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Security
                                            </a>
                                            <a href="<?php echo base_url(); ?>employee/twoFactorAuth?I=<?php echo base64_encode($basic->em_id); ?>" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="shield-check" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Two-factor Authentication
                                            </a>
                                            <a href="dagger-settings-device-history.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="smartphone" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Device History
                                            </a>
                                            <a href="dagger-settings-notification-settings.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="bell-dot" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Notification Settings
                                            </a>
                                            <!-- <a href="dagger-settings-connected-services.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="workflow" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Connected Services
                                            </a> -->
                                            <!-- <a href="dagger-settings-social-media-links.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="podcast" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Social Media Links
                                            </a> -->
                                            <!-- <a href="dagger-settings-account-deactivation.html" class="flex items-center py-3 first:-mt-3 last:-mb-3 [&.active]:text-primary [&.active]:font-medium hover:text-primary">
                                                <i data-tw-merge="" data-lucide="trash2" class="mr-3 h-4 w-4 stroke-[1.3]"></i>
                                                Account Deactivation
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 flex flex-col gap-y-7 xl:col-span-9">
                                    <div class="box box--stacked flex flex-col p-1.5">
                                        <div class="relative h-60 w-full rounded-[0.6rem] bg-gradient-to-b from-theme-1/95 to-theme-2/95">
                                            <div class="w-full h-full relative overflow-hidden before:content-[''] before:absolute before:inset-0 before:bg-texture-white before:-mt-[50rem] after:content-[''] after:absolute after:inset-0 after:bg-texture-white after:-mt-[50rem]"></div>
                                            <div class="absolute inset-x-0 top-0 mx-auto mt-36 h-32 w-32">
                                                <div class="box image-fit h-full w-full overflow-hidden rounded-full border-[6px] border-white">
                                                     <?php $profileUrl = $basic->em_image!=''?$basic->em_image:'assets/images/users/user.png'; ?>
                                                    <img src="<?= base_url().$profileUrl; ?>" alt="">
                                                </div>
                                                <div class="box absolute bottom-0 right-0 mb-2.5 mr-2.5 h-5 w-5 rounded-full border-2 border-white bg-success">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-y-3 rounded-[0.6rem] bg-slate-50 p-5 pt-12 sm:flex-row sm:items-end">
                                        </div>
                                    </div>
                                    <div class="box box--stacked flex flex-col p-5">
                                        <form class="needs-validation" method="post" action="<?php echo base_url('employee/saveAuthentication') ?>" enctype="multipart/form-data" novalidate>  
                                            <div class="mb-6 flex items-center border-b border-dashed border-slate-300/70 pb-5 text-[0.94rem] font-medium">
                                                Two-Factor Authentication (2FA)
                                                <div class="ml-3 flex items-center rounded-md border border-success/10 bg-success/10 px-1.5 py-px text-xs font-medium text-success">
                                                    <span class="-mt-px">Enabled</span>
                                                </div>
                                                <input type="text" hidden name="emid" value="<?php echo $basic->em_id; ?>">
                                                <input type="checkbox" name="is_two_fact_auth" id="is_two_fact_auth" value="1" class="ml-3 transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white">
                                            </div>
                                            <div>
                                                <div class="text-slate-500">
                                                    Enhance your account security by enabling Two-Factor
                                                    Authentication in the settings.
                                                </div>
                                                <div class="mt-2 block flex-col pt-5 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center">
                                                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-64">
                                                        <div class="text-left">
                                                            <div class="flex items-center">
                                                                <div class="font-medium">Account Password</div>
                                                                <div class="ml-2.5 rounded-md border border-slate-200 bg-slate-100 px-2 py-0.5 text-xs text-slate-500 dark:bg-darkmode-300 dark:text-slate-400">
                                                                    Required
                                                                </div>
                                                            </div>
                                                            <div class="mt-1.5 text-xs leading-relaxed text-slate-500/80 xl:mt-3">
                                                                Enter your current password to verify your identity.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 w-full flex-1 xl:mt-0">
                                                        <input type="text" placeholder="P**********d" name="auth_password" id="auth_password" required class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&amp;[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10">
                                                        <div class="text-xs text-slate-500 mt-2">
                                                            This is the password you use to log in to your account.
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-6 flex border-t border-dashed border-slate-300/70 pt-5 md:justify-end">
                                                <button class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;:hover:not(:disabled)]:bg-opacity-90 [&amp;:hover:not(:disabled)]:border-opacity-90 [&amp;:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed text-primary dark:border-primary [&amp;:hover:not(:disabled)]:bg-primary/10 w-full border-primary/50 px-4 md:w-auto">Save Changes</button>
                                            </div>
                                        </form>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->load->view('partials/footer'); ?>
<script>
    $(document).ready(function() {
    // Initially hide the div
    $('.mt-2.block.flex-col.pt-5').hide();
    $('#auth_password').removeAttr('required');

    // Listen for changes on the checkbox
    $('#is_two_fact_auth').change(function() {
        if ($(this).is(':checked')) {
            // Show the div if checkbox is checked
            $('.mt-2.block.flex-col.pt-5').show();
            $('#auth_password').attr('required', true);
        } else {
            // Hide the div if checkbox is unchecked
            $('.mt-2.block.flex-col.pt-5').hide();
            $('#auth_password').removeAttr('required');
        }
    });
});


</script>
