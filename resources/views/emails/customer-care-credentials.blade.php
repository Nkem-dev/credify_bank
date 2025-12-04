<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Credify Bank - Customer Care</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #1E40AF 0%, #1e3a8a 100%);
            padding: 30px 40px;
            text-align: center;
            color: white;
        }
        .logo {
            width: 64px;
            height: 64px;
            background-color: #ffffff;
            border-radius: 12px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 28px;
            color: #1E40AF;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
        }
        .intro-text {
            font-size: 15px;
            line-height: 1.6;
            color: #4b5563;
            margin-bottom: 24px;
        }
        .credentials-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .credential-item {
            margin-bottom: 16px;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .credential-value {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            font-family: 'Courier New', monospace;
            background-color: #ffffff;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            word-break: break-all;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .login-button {
            display: inline-block;
            background-color: #1E40AF;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            transition: background-color 0.2s;
        }
        .login-button:hover {
            background-color: #1e3a8a;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 24px 0;
            border-radius: 6px;
        }
        .warning-box p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
            line-height: 1.5;
        }
        .info-section {
            background-color: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        .info-section h3 {
            color: #1e40af;
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .info-section ul {
            margin: 0;
            padding-left: 20px;
            color: #1e40af;
        }
        .info-section li {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.5;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-text {
            font-size: 13px;
            color: #6b7280;
            margin: 8px 0;
            line-height: 1.5;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 24px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .email-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">CB</div>
            <h1>Welcome to Credify Bank</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p class="greeting">Hello {{ $name }},</p>
            
            <p class="intro-text">
                We're excited to welcome you to the Credify Bank Customer Care team! Your account has been successfully created, and you now have access to our customer care portal.
            </p>

            <p class="intro-text">
                Below are your login credentials. Please keep this information secure and do not share it with anyone.
            </p>

            <!-- Credentials Box -->
            <div class="credentials-box">
                <div class="credential-item">
                    <div class="credential-label">Email Address</div>
                    <div class="credential-value">{{ $email }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">Temporary Password</div>
                    <div class="credential-value">{{ $password }}</div>
                </div>
            </div>

            <!-- Login Button -->
            <div class="button-container">
                <a href="{{ $loginUrl }}" class="login-button">Login to Your Account</a>
            </div>

            <!-- Security Warning -->
            <div class="warning-box">
                <p>
                    <strong>⚠️ Important Security Notice:</strong> For your security, please change your password immediately after your first login. This temporary password should not be used long-term.
                </p>
            </div>

            <!-- Getting Started Info -->
            <div class="info-section">
                <h3>Getting Started</h3>
                <ul>
                    <li>Log in using the credentials provided above</li>
                    <li>Change your password in the account settings</li>
                    <li>Complete your profile information</li>
                    <li>Familiarize yourself with the customer care portal</li>
                    <li>Review our customer service guidelines and policies</li>
                </ul>
            </div>

            <div class="divider"></div>

            <p class="intro-text">
                If you have any questions or need assistance, please contact your supervisor or the IT department.
            </p>

            <p class="intro-text" style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The Credify Bank Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="footer-text">
                <strong>Credify Bank</strong><br>
                Trusted Banking Solutions
            </p>
            <p class="footer-text">
                © {{ date('Y') }} Credify Bank. All rights reserved.
            </p>
            <p class="footer-text">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>