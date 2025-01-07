<?php

namespace App\Livewire\Templates;

use Livewire\Component;

class Sidebar extends Component
{
    public function render()
    {
        return <<<'HTML'
        <div>
            <!-- Sidebar -->
            <div id="kt_aside" class="aside bg-primary" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_toggle">
                <div class="aside-logo d-none d-lg-flex flex-column align-items-center flex-column-auto py-8" id="kt_aside_logo">
                    <a href="../../demo4/dist/index.html">
                        <img alt="Logo" src="assets/media/logos/logo-demo4.svg" class="h-55px" />
                    </a>
                </div>

                <div class="aside-nav d-flex flex-column align-lg-center flex-column-fluid w-100 pt-5 pt-lg-0" id="kt_aside_nav">
                    <!--begin::Primary menu-->
                    <div id="kt_aside_menu" class="menu menu-column menu-title-gray-600 menu-state-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500 fw-bold fs-6" data-kt-menu="true">
                        <div class="menu-item py-3">
                            <a class="menu-link active menu-center" href="../../demo4/dist/index.html" title="Dashboard" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-house fs-2"></i>
                                </span>
                            </a>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="Authentication Pages" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-file-earmark-lock fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Authentication</span>
                                    </div>
                                </div>
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Basic</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion">
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/flows/basic/sign-in.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Sign-in</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/flows/basic/sign-up.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Sign-up</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/flows/basic/two-steps.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Two-steps</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/flows/basic/password-reset.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Password Reset</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/flows/basic/new-password.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">New Password</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Extended</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion">
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/extended/multi-steps-sign-up.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Multi-steps Sign-up</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/extended/free-trial-sign-up.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Free Trial Sign-up</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/extended/coming-soon.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Coming Soon</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/verify-email.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Verify Email</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/password-confirmation.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Password Confirmation</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start" data-kt-menu-flip="false" class="menu-item menu-accordion">
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">General</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion menu-sub-lg-dropdown w-lg-225px px-lg-1 py-lg-4">
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/welcome.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Welcome</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/verify-email.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Verify Email</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/password-confirmation.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Password Confirmation</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/close-account.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Close Account</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/error-404.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Error 404</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/general/error-500.html">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Error 500</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="right-start" data-kt-menu-flip="false" class="menu-item menu-accordion">
                                    <span class="menu-link">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Email</span>
                                        <span class="menu-arrow"></span>
                                    </span>
                                    <div class="menu-sub menu-sub-accordion menu-sub-lg-dropdown w-lg-225px px-lg-1 py-lg-4">
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/email/verify-email.html" target="blank">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Verify Email</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/email/password-reset.html" target="blank">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Password Reset</span>
                                            </a>
                                        </div>
                                        <div class="menu-item">
                                            <a class="menu-link" href="../../demo4/dist/authentication/email/password-change.html" target="blank">
                                                <span class="menu-bullet">
                                                    <span class="bullet bullet-dot"></span>
                                                </span>
                                                <span class="menu-title">Password Change</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="Account Pages" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-shield-check fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Account</span>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/overview.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Overview</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/settings.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Settings</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/security.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Security</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/billing.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Billing</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/statements.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Statements</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/referrals.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Referrals</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/account/api-keys.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">API Keys</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="Chat App" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-chat-square-text fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Chat</span>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/chat/private.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Private Chat</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/chat/group.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Group Chat</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/chat/drawer.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Drawer Chat</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="Customers App" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-people fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Customers</span>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/customers/getting-started.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Getting Started</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/customers/list.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Listing</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/apps/customers/view.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Customer Details</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="General Pages" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-file-text fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">General</span>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/about.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">About Us</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/pricing.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Pricing</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/contact.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Contact Us</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/team.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Our Team</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/licenses.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Licenses</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/pages/company/sitemap.html">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sitemap</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" data-kt-menu-placement="right-start" class="menu-item py-3">
                            <span class="menu-link menu-center" title="Resources" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                <span class="menu-icon me-0">
                                    <i class="bi bi-gear fs-2"></i>
                                </span>
                            </span>
                            <div class="menu-sub menu-sub-dropdown w-225px px-1 py-4">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">Resources</span>
                                    </div>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/documentation/base/utilities.html" title="Check out over 200 in-house components, plugins and ready for use solutions" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                        <span class="menu-icon">
                                            <i class="bi bi-grid fs-3"></i>
                                        </span>
                                        <span class="menu-title">Components</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/documentation/getting-started.html" title="Check out the complete documentation" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                        <span class="menu-icon">
                                            <i class="bi bi-box fs-3"></i>
                                        </span>
                                        <span class="menu-title">Documentation</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="https://preview.keenthemes.com/metronic8/demo4/layout-builder.html" title="Build your layout, preview and export HTML for server side integration" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                        <span class="menu-icon">
                                            <i class="bi bi-layers fs-3"></i>
                                        </span>
                                        <span class="menu-title">Layout Builder</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="../../demo4/dist/documentation/getting-started/changelog.html">
                                        <span class="menu-icon">
                                            <i class="bi bi-card-text fs-3"></i>
                                        </span>
                                        <span class="menu-title">Changelog v8.0.25</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Primary menu-->
                </div>
                <!--end::Nav-->
                <!--begin::Footer-->
                <div class="aside-footer d-flex flex-column align-items-center flex-column-auto" id="kt_aside_footer">
                    <!--begin::Menu-->
                    <div class="mb-7">
                        <button type="button" class="btn btm-sm btn-icon btn-color-white btn-active-color-primary btn-active-light" data-kt-menu-trigger="click" data-kt-menu-overflow="true" data-kt-menu-placement="top-start" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="click" title="Quick actions">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen008.svg-->
                            <span class="svg-icon svg-icon-2 svg-icon-lg-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M3 2H10C10.6 2 11 2.4 11 3V10C11 10.6 10.6 11 10 11H3C2.4 11 2 10.6 2 10V3C2 2.4 2.4 2 3 2Z" fill="black" />
                                    <path opacity="0.3" d="M14 2H21C21.6 2 22 2.4 22 3V10C22 10.6 21.6 11 21 11H14C13.4 11 13 10.6 13 10V3C13 2.4 13.4 2 14 2Z" fill="black" />
                                    <path opacity="0.3" d="M3 13H10C10.6 13 11 13.4 11 14V21C11 21.6 10.6 22 10 22H3C2.4 22 2 21.6 2 21V14C2 13.4 2.4 13 3 13Z" fill="black" />
                                    <path opacity="0.3" d="M14 13H21C21.6 13 22 13.4 22 14V21C22 21.6 21.6 22 21 22H14C13.4 22 13 21.6 13 21V14C13 13.4 13.4 13 14 13Z" fill="black" />
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </button>
                        <!--begin::Menu 2-->
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px" data-kt-menu="true">
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content fs-6 text-dark fw-bolder px-3 py-4">Quick Actions</div>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu separator-->
                            <div class="separator mb-3 opacity-75"></div>
                            <!--end::Menu separator-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">New Ticket</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">New Customer</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                                <!--begin::Menu item-->
                                <a href="#" class="menu-link px-3">
                                    <span class="menu-title">New Group</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <!--end::Menu item-->
                                <!--begin::Menu sub-->
                                <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Admin Group</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Staff Group</a>
                                    </div>
                                    <!--end::Menu item-->
                                    <!--begin::Menu item-->
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3">Member Group</a>
                                    </div>
                                    <!--end::Menu item-->
                                </div>
                                <!--end::Menu sub-->
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <a href="#" class="menu-link px-3">New Contact</a>
                            </div>
                            <!--end::Menu item-->
                            <!--begin::Menu separator-->
                            <div class="separator mt-3 opacity-75"></div>
                            <!--end::Menu separator-->
                            <!--begin::Menu item-->
                            <div class="menu-item px-3">
                                <div class="menu-content px-3 py-3">
                                    <a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
                                </div>
                            </div>
                            <!--end::Menu item-->
                        </div>
                        <!--end::Menu 2-->
                    </div>
                    <!--end::Menu-->
                </div>
                <!--end::Footer-->
            </div>
        </div>
        HTML;
    }
}
