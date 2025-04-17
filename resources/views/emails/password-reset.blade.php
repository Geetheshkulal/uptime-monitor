<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - CheckMySite</title>
</head>
<body style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f7fa; margin: 0; padding: 0; color: #333333; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="min-width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="100%" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="text-align: center; background: #3490dc; padding: 30px 20px; border-radius: 12px 12px 0 0;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="vertical-align: middle;">
                                                    <img src="https://i.postimg.cc/d3ctQNDG/mainlogo.png" alt="Logo" style="height: 40px; display: inline-block;">
                                                </td>
                                                <td style="vertical-align: middle; padding-left: 10px;">
                                                    <span style="color: white; font-size: 24px; font-weight: 600; display: inline-block;">CheckMySite</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h1 style="color: white; margin: 20px 0 0 0; font-size: 28px; font-weight: 600;">Reset Your Password</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; font-size: 16px; line-height: 1.6;">
                            <p style="margin-top: 0;">Hello <span style="font-weight: 600;">{{ $user->name ?? 'User' }}</span>,</p>
                            
                            <p>You are receiving this email because we received a password reset request for your account.</p>

                            <div style="text-align: center; margin: 35px 0;">
                                <a href="{{ $url }}" style="background: #3490dc; color: white; text-decoration: none; padding: 14px 30px; border-radius: 6px; font-weight: 600; display: inline-block; box-shadow: 0 3px 6px rgba(0,0,0,0.1); transition: all 0.2s ease;">Reset Password</a>
                            </div>

                            <p>This password reset link will expire in 60 minutes.</p>
                            <p>If you did not request a password reset, no further action is required.</p>

                            <div style="background-color: #f8f9fa; border-left: 4px solid #0066cc; padding: 15px; margin: 30px 0; border-radius: 4px;">
                                <p style="margin: 0 0 10px 0; font-weight: 600;">If you're having trouble clicking the "Reset Password" button,copy and paste the URL given below into your web browser:</p>
                                <p style="margin: 0; word-break: break-all; font-size: 14px;">
                                    <a href="{{ $url }}" style="color: #0066cc; text-decoration: underline;">{{ $url }}</a>
                                </p>
                            </div>

                            <p style="margin-bottom: 0;">If you have any questions about this reset request, please <a href="mailto:checkmysite2025@gmail.com" style="color: #0066cc; text-decoration: none; font-weight: 500;">contact our support team</a>.</p>
                        </td>
                    </tr>
                    
                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="height: 1px; background-color: #e9ecef; margin: 0;"></div>
                        </td>
                    </tr>
                    
                    <!-- Benefits Section -->
                    <tr>
                        <td style="padding: 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td colspan="3" style="text-align: center; padding-bottom: 20px;">
                                        <h3 style="margin: 0; color: #333; font-size: 18px;">What You Can Do With CheckMySite</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" width="33%" style="padding: 10px;">
                                        <div style="font-size: 24px; margin-bottom: 10px;">🔍</div>
                                        <div style="font-weight: 600; margin-bottom: 5px;">Monitor</div>
                                        <div style="font-size: 14px; color: #666;">Track website uptime 24/7</div>
                                    </td>
                                    <td align="center" width="33%" style="padding: 10px;">
                                        <div style="font-size: 24px; margin-bottom: 10px;">📊</div>
                                        <div style="font-weight: 600; margin-bottom: 5px;">Analyze</div>
                                        <div style="font-size: 14px; color: #666;">Get performance insights</div>
                                    </td>
                                    <td align="center" width="33%" style="padding: 10px;">
                                        <div style="font-size: 24px; margin-bottom: 10px;">🔔</div>
                                        <div style="font-weight: 600; margin-bottom: 5px;">Alert</div>
                                        <div style="font-size: 14px; color: #666;">Receive instant notifications</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-radius: 0 0 12px 12px;">
                            <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto;">
                                <tr>
                                    <td style="vertical-align: middle;">
                                        <img src="https://i.postimg.cc/d3ctQNDG/mainlogo.png" alt="Logo" style="height: 30px; display: inline-block;">
                                    </td>
                                    <td style="vertical-align: middle; padding-left: 8px;">
                                        <span style="color: #333; font-size: 18px; font-weight: 600; display: inline-block;">CheckMySite</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 14px; color: #666; margin: 20px 0 15px 0;">
                                Have questions? Contact us at <a href="mailto:checkmysite2025@gmail.com" style="color: #0066cc; text-decoration: none;">checkmysite2025@gmail.com</a>
                            </p>
                            
                            <p style="font-size: 13px; color: #999; margin: 0;">
                                &copy; {{ now()->year }} CheckMySite. All rights reserved.
                            </p>
                            
                            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                                This email was sent to you because a password reset was requested for your CheckMySite account.<br>
                                <a href="#" style="color: #0066cc; text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: #0066cc; text-decoration: none;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <!-- Fallback Message -->
                <table width="100%" style="max-width: 600px; margin: 20px auto 0;">
                    <tr>
                        <td style="text-align: center; font-size: 12px; color: #999;">
                            <p>{{ $fallbackMessage }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>