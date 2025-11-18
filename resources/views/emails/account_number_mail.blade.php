<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Email Verified</title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            font-family: 'Inter', Arial, sans-serif;
            color: #1f2937;
            line-height: 1.6;
        }
        .email-wrapper {
            background-color: #f9fafb;
            padding: 20px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e5e7eb;
        }
        .email-header {
            background: linear-gradient(135deg, #1E40AF 0%, #1e3a8a 100%);
            padding: 30px 40px;
            text-align: center;
            color: white;
        }
        .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .logo span {
            font-weight: 800;
            font-size: 28px;
            color: #1E40AF;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .email-header p {
            margin: 8px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px;
            text-align: center;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 16px;
            color: #111827;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 24px;
        }
        .account-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            text-align: center;
        }
        .account-label {
            font-size: 14px;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .account-number {
            font-size: 28px;
            font-weight: 700;
            color: #1E40AF;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }
        .highlight {
            background-color: #dbeafe;
            color: #1d4ed8;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        .cta-button {
            display: inline-block;
            background-color: #1E40AF;
            color: white;
            font-weight: 600;
            font-size: 16px;
            padding: 14px 32px;
            border-radius: 12px;
            text-decoration: none;
            margin: 24px 0;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            transition: all 0.2s;
        }
        .cta-button:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 64, 175, 0.4);
        }
        .email-footer {
            background-color: #f8fafc;
            padding: 30px 40px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer-links a {
            color: #1E40AF;
            text-decoration: none;
            margin: 0 12px;
            font-weight: 500;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 12px;
            }
            .email-header, .email-body, .email-footer {
                padding: 24px !important;
            }
            .account-number {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">

            <!-- Header -->
            <div class="email-header">
                <div class="logo">
                    <span>CB</span>
                </div>
                <h1>Email Verified Successfully</h1>
                <p>Welcome to Credify Bank</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hi {{ $user->name }},</p>

                <p class="message">
                    Congratulations! Your email has been successfully verified. Your Credify Bank account is now active and ready to use.
                </p>

                <!-- Account Number Box -->
                <div class="account-box">
                    <p class="account-label">Your Account Number</p>
                    <p class="account-number">{{ $user->account_number }}</p>
                </div>

                <p class="message">
                    Please keep this <span class="highlight">account number</span> safe. You’ll need it for all banking transactions, transfers, and deposits.
                </p>

                <p class="message">
                    You can now log in to your dashboard and explore all our secure banking features.
                </p>

                <a href="{{ route('login') }}" class="cta-button">
                    Log In to Dashboard
                </a>

                <p class="message" style="margin-top: 32px; font-size: 14px; color: #6b7280;">
                    Need help? Our support team is here 24/7.
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p style="margin: 0 0 12px; font-weight: 600; color: #1f2937;">
                    Credify Bank
                </p>
                <div class="footer-links">
                    <a href="#">Support</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
                <p style="margin: 16px 0 0; font-size: 12px; color: #94a3b8;">
                    © {{ date('Y') }} Credify Bank. All rights reserved.<br>
                    This is an automated message — please do not reply.
                </p>
            </div>

        </div>
    </div>
</body>
</html>