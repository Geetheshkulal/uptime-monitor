<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Up Alert - CheckMySite</title>
</head>
<body style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f7fa; margin: 0; padding: 0; color: #333333; -webkit-font-smoothing: antialiased;">
    <!-- Tracking Pixel -->
    <div style="display:none; font-size:0; line-height:0;">
        <img src="{{ url('/track/' . $token . '.png') }}" 
             alt="" 
             width="1" 
             height="1" 
             style="display:block;width:1px;height:1px;border:0;">
    </div>
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
                                                    <img src="https://i.ibb.co/Fq8LgD9s/mainlogo.png" alt="Logo" style="height: 40px; display: inline-block;">
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
                                        <h1 style="color: white; margin: 20px 0 0 0; font-size: 28px; font-weight: 600;">🚀 Monitor Up Alert</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px; font-size: 16px; line-height: 1.6;">
                            <p style="margin-top: 0;">Hello,</p>
                            <p><strong style="color:#38c172;">{{ $monitor->url }}</strong> is currently <strong>UP</strong> as of <strong>{{ now() }}</strong>.</p>
                                <p>The service is operating normally. No action is required at this time.</p>
                            </div>
                        </td>
                    </tr>

                    <!-- Benefits Section -->
                    <tr>
                        <td style="padding: 0 30px 30px;">
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
                                        <img src="https://i.ibb.co/Fq8LgD9s/mainlogo.png" alt="Logo" style="height: 30px; display: inline-block;">
                                    </td>
                                    <td style="vertical-align: middle; padding-left: 8px;">
                                        <span style="color: #333; font-size: 18px; font-weight: 600; display: inline-block;">CheckMySite</span>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="font-size: 14px; color: #666; margin: 20px 0 15px 0;">
                                Need help? Contact us at <a href="mailto:checkmysite2025@gmail.com" style="color: #0066cc; text-decoration: none;">checkmysite2025@gmail.com</a>
                            </p>

                            <p style="font-size: 13px; color: #999; margin: 0;">
                                &copy; {{ now()->year }} CheckMySite. All rights reserved.
                            </p>

                            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                                This email was sent to you because you signed up for a CheckMySite account.<br>
                                <a href="#" style="color: #0066cc; text-decoration: none;">Privacy Policy</a> | <a href="#" style="color: #0066cc; text-decoration: none;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>
                </table>

                
                
            </td>
        </tr>
    </table>
</body>
</html>
