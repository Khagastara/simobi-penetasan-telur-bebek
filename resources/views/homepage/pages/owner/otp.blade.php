<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
            letter-spacing: 5px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Password Akun SIMOBI</h2>
        <p>Anda telah meminta untuk mereset password akun Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>

        <div class="otp-code">{{ $otp }}</div>

        <p>Kode OTP ini akan kadaluarsa dalam 10 menit. Jika Anda tidak meminta reset password, silakan abaikan email ini.</p>

        <div class="footer">
            <p>Hormat kami,<br>Tim SIMOBI Penetasan Telur Bebek</p>
        </div>
    </div>
</body>
</html>
