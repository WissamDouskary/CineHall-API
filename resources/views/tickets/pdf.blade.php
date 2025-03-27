<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .ticket-container {
            border: 2px dashed #000;
            padding: 20px;
            width: 80%;
            margin: 0 auto;
        }
        h2 {
            color: #333;
        }
        .details {
            margin-top: 20px;
            font-size: 18px;
        }
        .barcode {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="ticket-container">

    <h2>Your Ticket</h2>
    <p><strong>ticket id :</strong> {{ $ticket->id }}</p>
    <p><strong>Film :</strong> {{ $ticket->film }}</p>
    <p><strong>Date :</strong> {{ $ticket->start_time }} a {{$ticket->end_time}}</p>
    <p><strong>Seat :</strong> {{ $ticket->seat }}</p>
    <p><strong>User id :</strong> {{ $ticket->user_id }}</p>

    @if (!empty($ticket->qr_code))
        <div class="barcode">
            <img src="data:image/png;base64,{{ $ticket->qr_code}}" alt="QR Code">
        </div>
    @endif
</div>
</body>
</html>
