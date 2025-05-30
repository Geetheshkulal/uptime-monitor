<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Comment on Your Ticket - CheckMySite</title>
</head>
<body style="font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f5f7fa; margin: 0; padding: 0; color: #333333; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="min-width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table width="100%" style="max-width: 600px; margin: auto; background: #ffffff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <!-- Header -->
                    <tr>
                        <td style="text-align: center; background: #3490dc; padding: 30px 20px; border-radius: 12px 12px 0 0;">
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <img src="https://i.ibb.co/Fq8LgD9s/mainlogo.png" alt="Logo" style="height: 40px;">
                                        <h1 style="color: white; margin: 10px 0 0 0; font-size: 24px;">New Comment Added</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px; font-size: 16px; line-height: 1.6;">
                            <p style="margin-top: 0;">Hello <strong>{{ $ticket->user->name }}</strong>,</p>

                            <p>A new comment has been added to your ticket <strong>#{{ $ticket->ticket_id }}</strong>:</p>

                            <blockquote style="border-left: 4px solid #3490dc; margin: 20px 0; padding-left: 15px; color: #555;">
                                {!! $ticket->comments->last()->comment_message !!}
                            </blockquote>

                            <p>You can view the full ticket and respond using the link below:</p>

                            <div style="text-align: center; margin: 35px 0;">
                                <a href="{{ route('display.tickets.show' , $ticket->id) }}" style="background: #3490dc; color: white; text-decoration: none; padding: 14px 30px; border-radius: 6px; font-weight: 600; display: inline-block; box-shadow: 0 3px 6px rgba(0,0,0,0.1); transition: all 0.2s ease;">View Ticket</a>
                            </div>

                            <p style="margin-bottom: 0;">If you have any questions, feel free to <a href="mailto:checkmysite2025@gmail.com" style="color: #0066cc; text-decoration: none; font-weight: 500;">contact our support team</a>.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 30px; text-align: center; border-radius: 0 0 12px 12px;">
                            <img src="https://i.ibb.co/Fq8LgD9s/mainlogo.png" alt="Logo" style="height: 30px;">
                            <p style="font-size: 14px; color: #666; margin: 15px 0;">
                                &copy; {{ now()->year }} CheckMySite. All rights reserved.
                            </p>
                            <p style="font-size: 12px; color: #999; margin-top: 10px;">
                                You received this email because you submitted a support ticket on CheckMySite.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
