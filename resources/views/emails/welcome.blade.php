<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a Komun</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f9; color: #333;">
<div style="max-width: 600px; margin: 40px auto; background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="color: #4F46E5; font-size: 28px; margin-bottom: 20px;">¡Bienvenido a <span style="color: #111827;">Komun</span>!</h1>

    <p style="font-size: 16px;">Hola <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 16px;">Gracias por registrarte en <strong>Komun</strong>. Estamos muy felices de tenerte en nuestra comunidad.</p>

    <p style="font-size: 16px;">Con Komun podrás:</p>
    <ul style="font-size: 16px; padding-left: 20px;">
        <li>🤝 Crear y gestionar solicitudes de ayuda</li>
        <li>🧭 Conectarte con personas que necesitan apoyo</li>
        <li>🌍 Formar parte de una comunidad solidaria</li>
    </ul>

    <p style="font-size: 16px;">¿Por dónde empezar?</p>
    <ol style="font-size: 16px; padding-left: 20px;">
        <li>📝 Completa tu perfil</li>
        <li>🔍 Explora solicitudes existentes</li>
        <li>➕ Crea tu primera solicitud</li>
    </ol>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ url('/') }}" style="display: inline-block; background-color: #4F46E5; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 16px;">
            Ir a Komun
        </a>
    </div>

    <p style="font-size: 16px;">Si tienes alguna duda, no dudes en escribirnos. Estamos para ayudarte.</p>

    <p style="font-size: 16px;">Un abrazo,<br><strong>El equipo de Komun</strong></p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 12px; color: #777;">
        <p>Este es un correo automático. Por favor, no respondas a este mensaje.</p>
    </div>
</div>
</body>
</html>
