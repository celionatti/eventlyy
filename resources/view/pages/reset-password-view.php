<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Password Reset Request</title>
    <style>
        /* Inline CSS for email compatibility */
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 20px auto; padding: 20px; }
        .header { font-size: 24px; color: #2563eb; margin-bottom: 20px; }
        .content { margin: 20px 0; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .footer { margin-top: 30px; font-size: 14px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Password Reset Request</div>

        <div class="content">
            <p>Hello <?= $user['name'] ?? 'User' ?>,</p>

            <p>We received a request to reset your password. Click the button below to reset it:</p>

            <p>
                <a href="<?= $reset_url ?>" class="button">Reset Password</a>
            </p>

            <p>If you didn't request this password reset, you can safely ignore this email.</p>

            <p>This password reset link will expire in <?= $expiration ?> minutes.</p>
        </div>

        <div class="footer">
            Best regards,<br>
            <?= $app_name ?> Team
        </div>
    </div>
</body>
</html>