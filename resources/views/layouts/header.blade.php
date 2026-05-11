<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Calling Exchange Software
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=2.1.0') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Select all forms
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    // Disable the submit button in the form
                    const submitButton = this.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                    }
                });
            });

            // Re-enable buttons on page load after Laravel response
            window.addEventListener('load', () => {
                document.querySelectorAll('button').forEach(button => {
                    button.disabled = false;
                });
            });
        });
        function validateFollowup() {
            const followupInput = document.getElementById('followup');
            followupInput.addEventListener('input', function() {
                followupInput.value = followupInput.value.replace(/[^0-9]/g, '');
            });

            followupInput.value = followupInput.value.replace(/[^0-9]/g, '');

        }

        // Run on page load
        window.onload = function() {
            validateFollowup();
        };
        document.addEventListener("DOMContentLoaded", function() {
            const activeNavLink = document.querySelector(".nav-link.active");

            if (activeNavLink) {
                activeNavLink.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }
        });

        $(document).ready(function() {
            $('.encrypted-data').each(function() {
                const encryptedData = $(this).text().trim();

                // Only decrypt if the content is encrypted
                if (encryptedData && isEncrypted(encryptedData)) {
                    const decryptedData = decryptData(encryptedData);
                    $(this).text(decryptedData);
                }
            });

            function isEncrypted(data) {
                // Check if the data appears encrypted (you can adjust this condition based on your encryption method)
                return data.length > 16; // Example condition (adjust as per your encryption size)
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const preloader = document.getElementById('preloader');

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(event) {
                    if (link.href && link.href.startsWith(window.location.origin)) {
                        preloader.style.display = 'flex';
                    }
                });
            });

            window.addEventListener('load', function() {
                preloader.style.display = 'none';
            });
        });
    </script>
    <style>
        .pagination {
            display: flex;
            list-style-type: none;
            padding: 0;
            margin: 0;
            justify-content: flex-end; 
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination li a, .pagination li span {
            display: inline-block;
            padding: 8px 16px;
            background-color: #f1f1f1; /* Light background color */
            color: #333; /* Text color */
            border-radius: 4px;
            font-size: 16px; /* Adjust the font size */
            text-decoration: none;
        }

        .pagination li a:hover, .pagination li span:hover {
            background-color: #ddd; /* Change background color on hover */
        }

        .pagination li.active span {
            background-color: #007bff; /* Change background color for the active page */
            color: white; /* Text color for active page */
            font-size: 18px; /* Increase font size for the active page */
            padding: 10px 18px; /* Increase padding to make it bigger */
        }

        .pagination li.disabled span {
            background-color: #e0e0e0; /* Gray color for disabled state */
            color: #2a2a2a; /* Text color for disabled state */
        }

        .pagination li a, .pagination li span {
            font-size: 14px; /* Default font size for pagination */
        }

        #preloader {
            background-color: #2a2a2a;
            /* Dark background */
        }

        .loader {
            border: 16px solid #acc301;
            /* Light grey border */
            border-top: 8px solid #3498db;
            /* Blue color for the spinner */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 1200.98px) {
            #sidenav-main {
                display: none;
                transition: left 1s linear;
                background-color: black;
            }

            .show-sidebar {
                display: block !important;
                position: absolute !important;
                left: 250px !important;

            }
        }

        .dataTables_filter {
            float: right;
        }

        .dataTables_filter label {
            font-weight: bold;
        }

        .dataTables_filter input {
            width: 200px;
            border-radius: 5px;
            padding: 5px;
            margin-left: 5px;
        }

        .dataTables_paginate {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        #userTable_paginate {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dataTables_paginate .paginate_button {
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 50%;
            border: 1px solid #ddd;
            color: #007bff;
            cursor: pointer;
        }

        .dataTables_paginate .paginate_button:hover {
            background-color: #f0f0f0;
        }

        .dataTables_paginate .paginate_button.current {
            background-color: #007bff;
            color: white !important;
            border: 1px solid #007bff;
            border-radius: 50%;
            padding: 3px 10px;
        }

        table tbody {
            color: white !important;
        }

        .bg-gradient-to-white {
            background: linear-gradient(to bottom, #f0f0f0, white);
        }

        .test1 {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.9));
            /* Replace with your desired gradient */
            color: white;
            opacity: 1;
        }

        .form-control {
            color: black;
            border: 1px solid #ced4da;
            /* Default Bootstrap border color */
            border-radius: 0.25rem;
            /* Bootstrap border radius */
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .d-sm-inline,
        .breadcrumb-item {
            font-weight: bold;
            color: black;
        }

        .form-label {
            color: black;
        }

        .text-capitalize {
            font-weight: bold;
            color: white;
        }

        .nav-link-text {
            font-weight: bold;
            color: #ffffff;

        }

        .sidenav {
            background-color: #2a2a2a;
        }

        .nav-link.active {
            background-color: #acc301 !important;
            /* Dark background color */
            color: #ffffff !important;
            /* White text color */
            font-weight: bold;
            border-radius: 5px;
        }

        .nav-link:hover {
            background-color: #acc301 !important;
        }

        .form-control:focus {
            border-color: #80bdff;
            /* Border color on focus */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            /* Shadow on focus */
        }

        input::placeholder {
            padding-left: 10px;
            color: #aaa;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .td-large {
            width: 45%;
        }

        .td-small {
            width: 10%;
            text-align: center;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .table thead tr th {
            color: black !important;
            font-size: 14px !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
        }

        .table tbody tr td {
            color: black !important;
        }

        /* .table tbody tr:nth-child(odd) {
            background-color: #4a6696;
            color: white;
        }

        .table tbody tr:nth-child(odd) td {
            color: white;
        }

        .table tbody tr:nth-child(even) {
            background-color: white !important;
            color: black !important;
        }

        .table tbody tr:nth-child(even) td {
            background-color: white !important;
            color: #4a6696; !important;
        }


        .table tbody tr:nth-child(odd):hover {
            background-color: #4a6696; !important;
            color: white !important;
        }

        .table tbody tr:nth-child(even):hover {
            background-color: #4a6696 !important;
            color: black !important;
        } */
    </style>
    <script> 
        document.addEventListener('contextmenu', function(event) {
            event.preventDefault();
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'F12' || (event.ctrlKey && event.shiftKey && event.key === 'I') || (event.ctrlKey &&
                    event.key === 'U')) {
                event.preventDefault();
            }
        });

        document.addEventListener('keydown', function(event) {
            if ((event.ctrlKey && event.shiftKey && (event.key === 'J' || event.key === 'C')) || (event.ctrlKey &&
                    event.key === 'S')) {
                event.preventDefault();
            }
        });

        document.addEventListener('selectstart', function(event) {
            event.preventDefault();
        });
        document.addEventListener('copy', function(event) {
            event.preventDefault();
        });
    </script>
</head>
