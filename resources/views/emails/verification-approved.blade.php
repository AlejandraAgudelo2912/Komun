<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación Aprobada</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333;">
<div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="color: #4F46E5; font-size: 26px; margin-bottom: 20px;">¡Hola <span style="color: #111827;">{{ $assistant->user->name }}</span>! 🎉</h1>

    <p style="font-size: 16px;">Nos alegra informarte que tu verificación como <strong>asistente en Komun</strong> ha sido <span style="color: #10B981;"><strong>aprobada</strong></span> correctamente.</p>

    <p style="font-size: 16px;">Desde ahora, puedes comenzar a ayudar a personas que necesitan apoyo en tu comunidad. 💜</p>

    <p style="font-size: 16px;">Gracias por tu generosidad, compromiso y por querer hacer del mundo un lugar mejor.</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/requests') }}" style="display: inline-block; background-color: #4F46E5; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 16px;">
            Empezar a ayudar
        </a>
    </div>

    <p style="font-size: 16px;">Si tienes alguna duda o necesitas orientación, no dudes en escribirnos.</p>

    <p style="font-size: 16px;">Con cariño,<br><strong>El equipo de Komun</strong></p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #777;">
        <p>Este es un correo automático. Por favor, no respondas a este mensaje.</p>
    </div>
</div>
</body>
</html>
