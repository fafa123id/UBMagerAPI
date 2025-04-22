<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;">
    <table width="100%" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <tr>
            <td style="padding: 30px; text-align: center;">
                <h2 style="color: #333333;">Verification Code</h2>
                <p style="font-size: 16px; color: #666666;">
                    Hello, we received a request to verify your account. Use the OTP code below:
                </p>
                <p style="font-size: 36px; font-weight: bold; color: #4F46E5; margin: 30px 0;">
                    {{ $otp }}
                </p>
                <p style="font-size: 14px; color: #999999;">
                    This code will expire in 5 minutes. If you didn't request this, you can safely ignore this email.
                </p>
                <p style="margin-top: 30px; font-size: 14px; color: #cccccc;">
                    &mdash; UBMager Team
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
