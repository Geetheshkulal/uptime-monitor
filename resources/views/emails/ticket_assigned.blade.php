
<!DOCTYPE html>
<html>
<head>
    <title>New Ticket Assigned</title>
</head>
<body>
    <h1>Hello {{ $ticket->assignedUser->name }},</h1>
    <p>You have been assigned a new ticket:</p>
    <ul>
        <li><strong>Ticket ID:</strong> {{ $ticket->ticket_id }}</li>
        <li><strong>Title:</strong> {{ $ticket->title }}</li>
        <li><strong>Priority:</strong> {{ $ticket->priority }}</li>
        <li><strong>Status:</strong> {{ $ticket->status }}</li>
    </ul>
    <p>Please log in to the system to view more details.</p>
    <p>Thank you!</p>
</body>
</html>