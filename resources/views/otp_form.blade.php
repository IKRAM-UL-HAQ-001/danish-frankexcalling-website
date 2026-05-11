<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enter OTP</title>
    <style>
        /* Reset styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Page container styling */
        body,
        html {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f9;
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            text-align: center;
        }

        /* Header styling */
        .header h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .header p {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 20px;
        }

        /* Alert styling */
        .alert {
            background-color: #e74c3c;
            color: #fff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"] {
            padding: 12px;
            font-size: 1rem;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Button styling */
        .btn-submit {
            padding: 12px;
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }

        /* Optional fade-out effect for alert */
        #error-alert {
            animation: fadeOut 9s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>Enter OTP</h4>
            <p>Please enter the OTP sent to your email to proceed</p>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="alert" id="error-alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form id="otp-form" method="post" action="{{ route('otp.verify') }}">
            @csrf
            <input type="text" id="otp" name="otp" placeholder="Enter OTP" required>
            <button type="submit" class="btn-submit" style="background-color: #acc301" onclick="getLocalIP()">Verify OTP</button>
        </form>
    </div>
</body>

</html>
