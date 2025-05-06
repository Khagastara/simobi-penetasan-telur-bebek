<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password SIMOBI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a76a8;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #eaeaea;
            border-radius: 5px;
            letter-spacing: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SIMOBI</h1>
        </div>
        <div class="content">
            <h2>Kode OTP Reset Password</h2>
            <p>Halo,</p>
            <p>Anda telah meminta untuk mengatur ulang kata sandi Anda. Gunakan kode OTP berikut untuk melanjutkan proses reset password:</p>

            <div class="otp-code">{{ $otp }}</div>

            <p>Kode OTP ini hanya berlaku selama 10 menit. Jika Anda tidak merasa meminta pengaturan ulang kata sandi, Anda dapat mengabaikan email ini.</p>

            <p>Terima kasih,<br>Tim SIMOBI</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} SIMOBI. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
