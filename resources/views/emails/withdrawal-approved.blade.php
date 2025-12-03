<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawal Approved - Earn Quest</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px; text-align: center;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 30px 20px; text-align: center; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border-radius: 10px 10px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Earn Quest</h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">Withdrawal Approved</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #333333; font-size: 16px; line-height: 1.6;">
                                Hello <strong>{{ $userName }}</strong>,
                            </p>
                            
                            <p style="margin: 0 0 30px; color: #333333; font-size: 16px; line-height: 1.6;">
                                Great news! Your withdrawal request has been approved and processed successfully.
                            </p>
                            
                            <!-- Success Box -->
                            <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; border-radius: 10px; text-align: center; margin: 30px 0;">
                                <div style="background-color: #ffffff; padding: 20px; border-radius: 8px; display: inline-block; margin-bottom: 15px;">
                                    <i class="fas fa-check-circle" style="font-size: 48px; color: #28a745;"></i>
                                </div>
                                <p style="margin: 0 0 10px; color: #ffffff; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Withdrawal Processed</p>
                                <p style="margin: 0; color: #ffffff; font-size: 32px; font-weight: bold;">${{ number_format($netAmount, 2) }}</p>
                            </div>
                            
                            <!-- Details Box -->
                            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; border-left: 4px solid #28a745; margin: 20px 0;">
                                <h3 style="margin: 0 0 15px; color: #333333; font-size: 18px; font-weight: bold;">Withdrawal Summary</h3>
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Requested Amount:</td>
                                        <td style="padding: 8px 0; text-align: right; color: #333333; font-weight: bold;">${{ number_format($amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Processing Fee:</td>
                                        <td style="padding: 8px 0; text-align: right; color: #dc3545;">-${{ number_format($feeAmount, 2) }}</td>
                                    </tr>
                                    <tr style="border-top: 2px solid #dee2e6;">
                                        <td style="padding: 12px 0; color: #333333; font-weight: bold; font-size: 16px;">Amount Received:</td>
                                        <td style="padding: 12px 0; text-align: right; font-size: 22px; font-weight: bold; color: #28a745;">${{ number_format($netAmount, 2) }}</td>
                                    </tr>
                                    @if($walletAddress)
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Sent To:</td>
                                        <td style="padding: 8px 0; text-align: right; font-family: monospace; font-size: 11px; color: #333333; word-break: break-all;">{{ $walletAddress }}</td>
                                    </tr>
                                    @endif
                                    @if($transactionId)
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Transaction ID:</td>
                                        <td style="padding: 8px 0; text-align: right; font-family: monospace; font-size: 11px; color: #333333;">{{ $transactionId }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            
                            <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #155724; font-size: 14px; line-height: 1.6;">
                                    <strong>Payment Status:</strong> Your withdrawal has been processed and the funds have been sent to your registered BEP20 wallet address. Please check your wallet for the transaction.
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('withdrawal.history') }}" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                    View Withdrawal History
                                </a>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 30px; background-color: #f8f9fa; border-radius: 0 0 10px 10px; text-align: center;">
                            <p style="margin: 0; color: #999999; font-size: 12px;">
                                © {{ date('Y') }} Earn Quest. All rights reserved.
                            </p>
                            <p style="margin: 10px 0 0; color: #999999; font-size: 12px;">
                                This is an automated email, please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

