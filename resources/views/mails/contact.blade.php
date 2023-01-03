<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nebolus - Contacto</title>
</head>
<body>
    <div class="container">
        <div>
            <strong>¡Hola, tienes un nuevo mensaje de un contacto!</strong>
            <p>
                <strong>Fecha:</strong> {{ date_format(date_create($date), 'd/m/Y') }}
                <br><strong>Usuario/Correo electrónico:</strong> {{ $email_sender }}
                <br><strong>Mensaje:</strong> <br>"{{ $comment }}"
            </p>
        <div>

        <div>
            <p>
                <strong>Equipo Nebolus</strong>
            </p>
        </div>
    </div>
</body>
</html>