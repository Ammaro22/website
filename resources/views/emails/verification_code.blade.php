<!DOCTYPE html>
<html>
<head>
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            color: #555555;
            font-size: 16px;
            line-height: 1.6;
        }
        .code {
            font-size: 28px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
            display: inline-block;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>Your Verification Code</h1>
    <p>Your verification code is:</p>
    <div class="code">{{ $code }}</div>
    <p>This code will expire in <strong>10 minutes</strong>.</p>
    <p class="footer">If you did not request this code, please ignore this email.</p>
</div>
</body>
</html>
