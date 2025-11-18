<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Your OTP Code</title>

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
        .otp-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px dashed #0ea5e9;
            border-radius: 16px;
            padding: 32px 24px;
            margin: 32px 0;
            text-align: center;
        }
        .otp-label {
            font-size: 14px;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            color: #1E40AF;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            margin: 0;
            line-height: 1.2;
        }
        .otp-expiry {
            font-size: 14px;
            color: #dc2626;
            margin-top: 16px;
            font-weight: 500;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 16px;
            margin: 24px 0;
            text-align: left;
            border-radius: 0 8px 8px 0;
            font-size: 14px;
            color: #92400e;
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
            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
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
                <h1>Your OTP Code</h1>
                <p>Verify your email address</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hi {{ $user->name ?? 'there' }},</p>

                <p class="message">
                    We received a request to verify your email address for your Credify Bank account.
                </p>

                <!-- OTP Code Box -->
                <div class="otp-box">
                    <p class="otp-label">Your One-Time Code</p>
                    <p class="otp-code">{{ $otp_code }}</p>
                    <p class="otp-expiry">Expires in 20 minutes</p>
                </div>

                <p class="message">
                    Please enter this code in the verification field to complete your email verification.
                </p>

                <!-- Security Warning -->
                <div class="warning-box">
                    <strong>Important:</strong> This code is confidential. 
                    <strong>Never share it with anyone</strong>, even if they claim to be from Credify Bank.
                </div>

                <p class="message" style="margin-top: 32px; font-size: 14px; color: #6b7280;">
                    If you didn’t request this code, please ignore this email or contact support immediately.
                </p>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p style="margin: 0 0 12px; font-weight: 600; color: #1f2937;">
                    Credify Bank
                </p>
                <div class="footer-links">
                    <a href="#">Support</a>
                    <a href="#">Security Center</a>
                    <a href="#">Privacy Policy</a>
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