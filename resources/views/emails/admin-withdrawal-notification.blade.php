<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Withdrawal Request - Earn Quest</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 40px 20px; text-align: center;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 30px 20px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px 10px 0 0;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Earn Quest</h1>
                            <p style="margin: 10px 0 0; color: #ffffff; font-size: 16px; opacity: 0.9;">New Withdrawal Request</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; color: #333333; font-size: 16px; line-height: 1.6;">
                                Hello Admin,
                            </p>
                            
                            <p style="margin: 0 0 30px; color: #333333; font-size: 16px; line-height: 1.6;">
                                A new withdrawal request has been submitted and requires your review.
                            </p>
                            
                            <!-- User Information Box -->
                            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 30px; border-radius: 10px; margin: 30px 0;">
                                <h2 style="margin: 0 0 20px; color: #ffffff; font-size: 20px; font-weight: bold;">User Information</h2>
                                <table role="presentation" style="width: 100%; color: #ffffff;">
                                    <tr>
                                        <td style="padding: 8px 0; font-weight: 600;">Name:</td>
                                        <td style="padding: 8px 0; text-align: right;">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-weight: 600;">Email:</td>
                                        <td style="padding: 8px 0; text-align: right; word-break: break-all;">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; font-weight: 600;">User ID:</td>
                                        <td style="padding: 8px 0; text-align: right;">#{{ $user->id }}</td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Withdrawal Details Box -->
                            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; border-left: 4px solid #667eea; margin: 20px 0;">
                                <h3 style="margin: 0 0 15px; color: #333333; font-size: 18px; font-weight: bold;">Withdrawal Details</h3>
                                <table role="presentation" style="width: 100%;">
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Requested Amount:</td>
                                        <td style="padding: 8px 0; text-align: right; font-size: 20px; font-weight: bold; color: #667eea;">${{ number_format($amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Processing Fee:</td>
                                        <td style="padding: 8px 0; text-align: right; color: #dc3545;">-${{ number_format($feeAmount, 2) }}</td>
                                    </tr>
                                    <tr style="border-top: 2px solid #dee2e6;">
                                        <td style="padding: 12px 0; color: #333333; font-weight: bold; font-size: 16px;">Net Amount:</td>
                                        <td style="padding: 12px 0; text-align: right; font-size: 22px; font-weight: bold; color: #28a745;">${{ number_format($netAmount, 2) }}</td>
                                    </tr>
                                    @if($walletAddress)
                                    <tr>
                                        <td style="padding: 8px 0; color: #666666; font-weight: 600;">Wallet Address:</td>
                                        <td style="padding: 8px 0; text-align: right; font-family: monospace; font-size: 11px; color: #333333; word-break: break-all;">{{ $walletAddress }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                            
                            <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                    <strong>Action Required:</strong> Please review this withdrawal request in the admin panel and process the payment to the user's wallet address.
                                </p>
                            </div>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('admin.withdrawals') }}" style="display: inline-block; padding: 12px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;">
                                    Review Withdrawal Request
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
                                This is an automated notification email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

