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
                                                Notification Settings
                                            </div>
                                            <div>
                                                <div role="alert" class="alert relative border rounded-md px-5 py-4 border-primary text-primary dark:border-primary mb-2 flex items-center border-primary/20 bg-primary/5 px-4">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="alert-circle" class="lucide lucide-alert-circle mr-3 h-4 w-4 stroke-[1.3] md:mr-2"><circle cx="12" cy="12" r="10"></circle><line x1="12" x2="12" y1="8" y2="12"></line><line x1="12" x2="12.01" y1="16" y2="16"></line></svg>
                                                    </div>
                                                    <div class="mr-5 leading-relaxed">
                                                        We'd like to request your browser's permission to
                                                        display notifications.
                                                        <a class="ml-1 font-medium underline decoration-warning/50 decoration-dotted underline-offset-[3px]" href="#">
                                                            Request permission
                                                        </a>
                                                        <button data-tw-dismiss="alert" type="button" aria-label="Close" class="text-slate-800 py-2 px-3 absolute right-0 my-auto mr-2 btn-close inset-y-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x" class="lucide lucide-x stroke-[1] h-4 w-4"><path d="M18 6 6 18"></path><path d="m6 6 12 12"></path></svg></button>
                                                    </div>
                                                </div>
                                                <div class="mt-5 rounded-lg border border-slate-200/80">
                                                    <div class="overflow-auto xl:overflow-visible">
                                                        <table class="w-full text-left">
                                                            <thead class="">
                                                                <tr class="">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 font-medium text-slate-500 first:rounded-tl-lg last:rounded-tr-lg">
                                                                        Type
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 font-medium text-slate-500 first:rounded-tl-lg last:rounded-tr-lg">
                                                                        <div class="flex flex-col items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="mail-check" class="lucide lucide-mail-check stroke-[1] h-6 w-6"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h8"></path><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path><path d="m16 19 2 2 4-4"></path></svg>
                                                                            <div class="mt-1.5">Email</div>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-slate-200/80 bg-slate-50 py-4 font-medium text-slate-500 first:rounded-tl-lg last:rounded-tr-lg">
                                                                        <div class="flex flex-col items-center">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="globe" class="lucide lucide-globe stroke-[1] h-6 w-6"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                                                                            <div class="mt-1.5">Browser</div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr class="[&amp;_td]:last:border-b-0">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="whitespace-nowrap">
                                                                            Unusual login activity detected
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-3" value="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-4" value="">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="[&amp;_td]:last:border-b-0">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="flex items-center whitespace-nowrap">
                                                                            Password change request
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="info" class="lucide lucide-info ml-1.5 h-4 w-4 stroke-[1.3] text-slate-400"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-6" value="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-7" value="">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="[&amp;_td]:last:border-b-0">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="whitespace-nowrap">
                                                                            New message received
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-9" value="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-10" value="">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="[&amp;_td]:last:border-b-0">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="whitespace-nowrap">
                                                                            Account activity summary
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-12" value="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-13" value="">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="[&amp;_td]:last:border-b-0">
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="flex items-center whitespace-nowrap">
                                                                            Security alert: Unrecognized device
                                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="info" class="lucide lucide-info ml-1.5 h-4 w-4 stroke-[1.3] text-slate-400"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-15" value="">
                                                                        </div>
                                                                    </td>
                                                                    <td class="px-5 border-b dark:border-darkmode-300 border-dashed border-slate-300/70 py-4 dark:bg-darkmode-600">
                                                                        <div class="text-center">
                                                                            <input type="checkbox" class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;[type='radio']]:checked:bg-primary [&amp;[type='radio']]:checked:border-primary [&amp;[type='radio']]:checked:border-opacity-10 [&amp;[type='checkbox']]:checked:bg-primary [&amp;[type='checkbox']]:checked:border-primary [&amp;[type='checkbox']]:checked:border-opacity-10 [&amp;:disabled:not(:checked)]:bg-slate-100 [&amp;:disabled:not(:checked)]:cursor-not-allowed [&amp;:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&amp;:disabled:checked]:opacity-70 [&amp;:disabled:checked]:cursor-not-allowed [&amp;:disabled:checked]:dark:bg-darkmode-800/50" id="checkbox-switch-16" value="">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="mt-3 block flex-col pt-5 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center">
                                                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-1/2">
                                                        <div class="text-left">
                                                            <div class="flex items-center">
                                                                <div class="font-medium">
                                                                    When would you prefer to receive notifications?
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 w-full flex-1 xl:mt-0">
                                                        <select class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1">
                                                            <option value="Immediately">Immediately</option>
                                                            <option value="In the morning">In the morning</option>
                                                            <option value="At noon">At noon</option>
                                                            <option value="In the afternoon">
                                                                In the afternoon
                                                            </option>
                                                            <option value="In the evening">In the evening</option>
                                                            <option value="At night">At night</option>
                                                            <option value="Once a day">Once a day</option>
                                                            <option value="Twice a day">Twice a day</option>
                                                            <option value="Custom schedule">Custom schedule</option>
                                                            <option value="Don't send notifications">
                                                                Don't send notifications
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mt-3 block flex-col pt-5 first:mt-0 first:pt-0 sm:flex xl:flex-row xl:items-center">
                                                    <div class="mb-2 inline-block sm:mb-0 sm:mr-5 sm:text-right xl:mr-14 xl:w-1/2">
                                                        <div class="text-left">
                                                            <div class="flex items-center">
                                                                <div class="font-medium">
                                                                    Receive a daily overview ('Daily Digest') of your
                                                                    task activity.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 w-full flex-1 xl:mt-0">
                                                        <div class="flex flex-col items-center md:flex-row">
                                                            <select class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&amp;:not(:first-child):not(:last-child)]:-mt-px [&amp;:not(:first-child):not(:last-child)]:rounded-none [&amp;:not(:first-child):not(:last-child)]:md:-ml-px [&amp;:not(:first-child):not(:last-child)]:md:mt-0">
                                                                <option value="Every day">Every day</option>
                                                                <option value="Once a day">Once a day</option>
                                                                <option value="Twice a day">Twice a day</option>
                                                                <option value="No daily overview (disable Daily Digest)">
                                                                    No daily overview (disable Daily Digest)
                                                                </option>
                                                            </select>
                                                            <select class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 first:rounded-b-none last:-mt-px last:rounded-t-none focus:z-10 first:md:rounded-r-none first:md:rounded-bl-md last:md:-ml-px last:md:mt-0 last:md:rounded-l-none last:md:rounded-tr-md [&amp;:not(:first-child):not(:last-child)]:-mt-px [&amp;:not(:first-child):not(:last-child)]:rounded-none [&amp;:not(:first-child):not(:last-child)]:md:-ml-px [&amp;:not(:first-child):not(:last-child)]:md:mt-0">
                                                                <option value="at 8:00 AM">at 8:00 AM</option>
                                                                <option value="at 12:00 PM">at 12:00 PM</option>
                                                                <option value="at 4:00 PM">at 4:00 PM</option>
                                                                <option value="at 8:00 PM">at 8:00 PM</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-7 text-slate-500">
                                                    To reduce interruptions, email notifications are bundled and
                                                    delivered when you're not actively using your device.
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
