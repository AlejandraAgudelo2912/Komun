<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido a Komun</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="color: #4F46E5;">¡Bienvenido a Komun!</h1>
        
        <p>Hola {{ $user->name }},</p>
        
        <p>Gracias por registrarte en Komun. Estamos emocionados de tenerte como parte de nuestra comunidad.</p>
        
        <p>Con Komun podrás:</p>
        <ul>
            <li>Crear y gestionar solicitudes de ayuda</li>
            <li>Conectarte con personas que necesitan ayuda</li>
            <li>Formar parte de una comunidad solidaria</li>
        </ul>

        <p>Para comenzar, puedes:</p>
        <ol>
            <li>Completar tu perfil</li>
            <li>Explorar las solicitudes existentes</li>
            <li>Crear tu primera solicitud de ayuda</li>
        </ol>

        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>

        <p>Saludos cordiales,<br>El equipo de Komun</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;">
            <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html> 