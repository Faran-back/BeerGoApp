<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 40px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
            text-align: center;
            letter-spacing: 4px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->full_name ?? $user->email }},</h2>
        <p>We received a password reset request from your email.</p>
        <p>Please verify your account using the OTP below:</p>

        <div class="otp">
            {{ $user->otp }}
        </div>

        <p>This OTP is valid for a limited time only. Do not share it with anyone.</p>

        <p>Regards,<br><strong>BeerGoApp Team</strong></p>

        <div class="footer">
            © {{ date('Y') }} BeerGoApp. All rights reserved.
        </div>
    </div>
</body>
</html>
