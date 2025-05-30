<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/cdo-seal.png') }}">
</head>

<head>
    <base href="">

    <title>{{ $title ?? 'Page Title' }}</title>

    <meta name="description" content="CLENRO DTS" />
    <meta name="keywords" content="Local Government Unit of Cagayan de Oro, City Management Information Systems and Innovation Department" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="CLENRO DTS" />
    <meta property="og:url" content="https://services.cagayandeoro.gov.ph:8087/clenro-dts/" />
    <meta property="og:site_name" content="CLENRO DTS" />

    <link rel="shortcut icon" href="{{ asset('images/cdo-seal.png') }}" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!-- begin::Plugins -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">

    <link href="{{ asset('plugins/jquery-filepond-master/filepond.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/jquery-filepond-master/filepond-plugin-image-preview.css') }}">
    <link href="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('plugins/virtual-select/virtual-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/virtual-select/tooltip.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/daterangepicker-master/daterangepicker.css') }}" />
    <!-- end::Plugins -->

    <style>
        /* Random Profile Picture */
        .profile-picture {
            width: 50px;
            /* Adjust the size as needed */
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            /* Adjust the font size as needed */
            font-weight: bold;
            color: white;
            background-color: #3498db;
            /* Default color if dynamic color is not set */
        }

        /* Optional: Dynamic color classes */
        /* Teal */
        .bg-color-1 {
            background-color: #1abc9c;
        }

        /* Red */
        .bg-color-2 {
            background-color: #e74c3c;
        }

        /* Yellow */
        .bg-color-3 {
            background-color: rgb(182, 165, 89);
        }

        /* Orange */
        .bg-color-4 {
            background-color: #f39c12;
        }

        /* Green */
        .bg-color-5 {
            background-color: #2ecc71;
        }

        /* End Random Profile Picture */

        /* Buttons */
        .btn.btn-primary {
            color: rgb(255, 255, 255);
            border-color: #639a56;
            background-color: #639a56;
        }

        /* End Buttons */

        .bg-primary2 {
            --bs-bg-opacity: 1;
            background-color: #639a56 !important;
        }

        .aside .menu>.menu-item>.menu-link.active .menu-icon .svg-icon svg [fill]:not(.permanent):not(g) {
            transition: fill .3s ease;
            fill: #639a56 !important;
        }

        .menu-state-primary .menu-item .menu-link:hover:not(.disabled):not(.active) .menu-title,
        .menu-state-primary .menu-item.hover>.menu-link:not(.disabled):not(.active) .menu-title {
            color: #639a56;
        }

        .menu-state-bullet-primary .menu-item .menu-link:hover:not(.disabled):not(.active) .menu-bullet .bullet,
        .menu-state-bullet-primary .menu-item.hover>.menu-link:not(.disabled):not(.active) .menu-bullet .bullet {
            background-color: #639a56;
        }

        .menu-state-primary .menu-item .menu-link.active .menu-title {
            color: #639a56;
        }

        .menu-state-bullet-primary .menu-item .menu-link.active .menu-bullet .bullet {
            background-color: #639a56;
        }

        .btn-check:active+.btn.btn-primary,
        .btn-check:checked+.btn.btn-primary,
        .btn.btn-primary.active,
        .btn.btn-primary.show,
        .btn.btn-primary:active:not(.btn-active),
        .btn.btn-primary:focus:not(.btn-active),
        .btn.btn-primary:hover:not(.btn-active),
        .show>.btn.btn-primary {
            color: #fff;
            border-color: rgb(63, 99, 55);
            background-color: rgb(63, 99, 55) !important;
        }

        /* begin::Separator */
        .separator {
            display: flex;
            align-items: center;
            text-align: center;
        }

        .separator::before,
        .separator::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #a5a8ba;
        }

        .separator:not(:empty)::before {
            margin-right: .25em;
        }

        .separator:not(:empty)::after {
            margin-left: .25em;
        }

        /* end::Separator */

        /* begin::disable anchor */
        .disabled-link {
            pointer-events: none;
            cursor: default;
            /* Optional: Change cursor to indicate it's disabled */
            opacity: 0.6;
            /* Optional: Make it look disabled */
        }

        /* end::disable anchor */

        /* begin::custom virtual select */
        .vscomp-wrapper {
            height: 45px;
        }

        /* end::customem virtual select */
    </style>
</head>

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-disabled">
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <livewire:templates.sidebar />

            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <livewire:templates.topbar :title="$title" />

                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Container-->
                    <div class="container-xxl" id="kt_content_container">
                        {{ $slot }}
                    </div>
                    <!--end::Container-->
                </div>

                <livewire:templates.footer />
            </div>
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
            </svg>
        </span>
    </div>

    <script src="{{ asset('plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>

    <!-- begin::Plugins -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond-plugin-file-validate-size.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('plugins/jquery-filepond-master/filepond.jquery.js') }}"></script>
    <script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('plugins/virtual-select/virtual-select.min.js') }}"></script>
    <script src="{{ asset('plugins/virtual-select/tooltip.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker-master/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker-master/daterangepicker.js') }}"></script>
    <!-- end::Plugins -->
</body>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('success', (message) => {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message.message
            });
        });

        Livewire.on('error', () => {
            Swal.fire({
                title: "Error",
                text: "Something went wrong.",
                icon: "error"
            });
        });
    });
</script>

<!-- Check if jquery exists -->
<!-- <script type="text/javascript">
    window.onload = function() {
        if (window.jQuery) {
            alert('jQuery is loaded');
        } else {
            alert('jQuery is not loaded');
        }
    }
</script> -->

</html>