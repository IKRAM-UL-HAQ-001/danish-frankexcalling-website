<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Frank Calling Management</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const preloader = document.getElementById('preloader');

        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function (event) {
                if (link.href && link.href.startsWith(window.location.origin)) {
                    preloader.style.display = 'flex';
                }
            });
        });

        window.addEventListener('load', function () {
            preloader.style.display = 'none';
        });
    });</script>

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
    <style>
    #preloader {
    background-color: #2a2a2a; /* Dark background */
}

.loader {
    border: 16px solid #acc301; /* Light grey border */
    border-top: 8px solid #3498db; /* Blue color for the spinner */
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
}</style>
</head>

<body>
    <div class="page-header min-vh-100"
        style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('../assets/img/frankcalling.jpg');
    background-size: cover;
    background-position: center;
    z-index: -1;
    /* opacity: 0.9; */
">
    </div>
    <div id="preloader" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
">
    <div class="loader"></div>
</div>
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100" style="background-image: url('../assets/img/walaper.jpg');">
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-xl-6 centercol-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto "
                            style="background: #acc301; border-radius: 10px; ">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start"
                                    style="background-color: transparent; color:#2a2a2a">
                                    <h4 class="font-weight-bolder display-4"
                                        style="color: #2a2a2a; text-align:center;">Call Center Management</h4>
                                    {{-- <p class="mb-0 display-6" style=" font-size:18px;"">Enter your User Name and password to sign in</p> --}}
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger text-white" id="error-alert">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <script>
                                        setTimeout(function() {
                                            var errorAlert = document.getElementById('error-alert');
                                            if (errorAlert) {
                                                errorAlert.style.display = 'none';
                                            }
                                        }, 3000);
                                    </script>

                                    <form id="form" method="post" action="{{ route('login.post') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <select class="form-control form-control-lg" name="role" id="role"
                                                onchange="toggleExchangeDropdown()">
                                                <option value="" disabled selected>Select your Role</option>
                                                <option value="admin">Admin</option>
                                                <option value="exchange">Exchange</option>
                                                <option value="assistant">Assistant</option>
                                                <option value="customercare">Customer Care</option>
                                            </select>
                                        </div>
                                        <div id="userFields">
                                            <div class="mb-3">
                                                <input type="text" class="form-control form-control-lg"
                                                    id="name" name="name" placeholder="Enter User Name"
                                                    required>
                                            </div>
                                            <div class="mb-3">
                                                <input type="password" class="form-control form-control-lg"
                                                    id="password" name="password" placeholder="Enter Password"
                                                    required>
                                            </div>
                                        </div>
                                        <div id="ExchangeDropdown" style="display: none;">
                                            <div class="mb-3">
                                                <select class="form-control form-control-lg" id="exchange"
                                                    name="exchange">
                                                    <option value="" disabled selected>Select Your Exchange
                                                    </option>
                                                    @foreach ($exchangeRecords as $exchange)
                                                        <option value="{{ $exchange->id ?? 'N/A' }}"
                                                            class="encrypted-data">{{ $exchange->name ?? 'N/A' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white "
                                                style=" font-size:18px; background-color:#2a2a2a">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
   <script>
    $(document).ready(function() {
        const secretKey = CryptoJS.enc.Utf8.parse('{{ config('app.aes_encrypt_key') }}'); // 16-byte key for AES
        const iv = CryptoJS.enc.Hex.parse('00000000000000000000000000000000'); // 16-byte fixed IV

        function encryptData(data) {
            return CryptoJS.AES.encrypt(data, secretKey, {
                iv: iv
            }).toString();
        }

        $('#form').on('submit', function() {
            // Encrypt the form fields
            $('#name').val(encryptData($('#name').val()));
            $('#password').val(encryptData($('#password').val()));

            // Show the preloader before form submission
            $('#preloader').css('display', 'flex');
        });

        function decryptData(encryptedData) {
            const decrypted = CryptoJS.AES.decrypt(encryptedData, secretKey, {
                iv: iv
            });
            return decrypted.toString(CryptoJS.enc.Utf8);
        }

        $('.encrypted-data').each(function() {
            const encryptedData = $(this).text().trim();
            const decryptedData = decryptData(encryptedData);
            if (decryptedData) {
                $(this).text(decryptedData);
            }
        });
    });

    // Define the toggleExchangeDropdown function
    function toggleExchangeDropdown() {
        const role = document.getElementById('role').value;
        const exchangeDropdown = document.getElementById('ExchangeDropdown');

        if (role === 'exchange' || role === 'customercare') {
            exchangeDropdown.style.display = 'block';
        } else {
            exchangeDropdown.style.display = 'none';
        }
    }
</script>

</body>
</html>
