<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Page</title>
    <style>
        /* Style for the container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        /* Style for the title */
        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Style for the description */
        .description {
            font-size: 18px;
            margin-bottom: 20px;
        }

        /* Style for the QR code image */
        .qr-code {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="title">QR Code for Complaints</div>
    <div class="description">Scan the QR code below:</div>

    <img class="qr-code" src="{{ $img_src }}" alt="QR Code">
</div>
</body>
</html>
