{{-- resources/views/emails/loan-approved.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credify Bank - Loan Approved</title>

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
        .amount-box {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 2px solid #0ea5e9;
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            text-align: center;
        }
        .amount-label {
            font-size: 14px;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .amount-value {
           font-size: 28px;
            font-weight: 700;
            color: #1E40AF;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
            margin: 0;
        }
        .loan-details-box {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin: 32px 0;
            text-align: left;
        }
        .loan-details-header {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
            text-align: center;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #6b7280;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 600;
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
            .amount-value {
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
                <h1>Loan Approved Successfully</h1>
                <p>Congratulations on your loan approval</p>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p class="greeting">Hi {{ $user->name }},</p>

                <p class="message">
                    Great news! Your loan application has been successfully approved. The funds have been credited to your Credify Bank account and are now available for use.
                </p>

                <!-- Amount Box -->
                <div class="amount-box">
                    <p class="amount-label">Amount Credited</p>
                    <p class="amount-value">₦{{ number_format($loan->amount, 2) }}</p>
                </div>

                <p class="message">
                    Please review your loan details below and keep this information for your records.
                </p>

                <!-- Loan Details -->
                <div class="loan-details-box">
                    <div class="loan-details-header">Loan Details</div>
                    
                    
                    <div class="detail-row">
                        <span class="detail-label">Loan Amount:</span>
                        <span class="detail-value">₦{{ number_format($loan->amount, 2) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Interest Rate:</span>
                        <span class="detail-value">{{ $loan->interest_rate }}% per annum</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Loan Term:</span>
                        <span class="detail-value">{{ $loan->term_months }} months</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Monthly Payment:</span>
                        <span class="detail-value">₦{{ number_format($loan->monthly_payment, 2) }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Approved Date:</span>
                        <span class="detail-value">{{ $loan->approved_at->format('M d, Y h:i A') }}</span>
                    </div>
                    
                    <div class="detail-row">
                        <span class="detail-label">Repayment Due:</span>
                        <span class="detail-value">{{ $loan->disbursed_at->addMonths($loan->term_months)->format('M d, Y') }}</span>
                    </div>
                </div>

                <p class="message">
                    Your <span class="highlight">monthly payment of ₦{{ number_format($loan->monthly_payment, 2) }}</span> is due on the same date each month. Please ensure timely payments to maintain a good credit standing.
                </p>

                <p class="message">
                    You can now log in to your dashboard to view your loan details and track your repayment schedule.
                </p>

                <a href="{{ route('dashboard') }}" class="cta-button">
                    View Loan Details
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