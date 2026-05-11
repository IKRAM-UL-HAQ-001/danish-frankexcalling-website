<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 8px;
            padding: 20px;
        }
        .header {
            text-align: center;
            background-color: #acc301;
            color: black;
            padding: 10px 0;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            text-align: center;
        }
        .content p {
            font-size: 18px;
            line-height: 1.6;
        }
        .otp-code {
            font-size: 22px;
            font-weight: bold;
            color: black;
            background-color: #acc301;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            margin: 20px 0;
        }
        .link {
            display: inline-block;
            text-decoration: none;
            color: #ffffff;
            background-color: #acc301;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 18px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>One-Time Password (OTP)</h1>
        </div>
        <div class="content">
            <p>Your OTP for today is:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Please use this OTP to access your dashboard.</p>
            <a href="{{ url('/enter-otp') }}" class="link">Go to Verification</a>
        </div>
        <div class="footer">
            <p>If you did not request this OTP, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
