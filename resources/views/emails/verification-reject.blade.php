<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación Rechazada</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; color: #333;">
<div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
    <h1 style="color: #DC2626; font-size: 24px; margin-bottom: 20px;">❌ Verificación rechazada</h1>

    <p style="font-size: 16px;">Hola <strong>{{ $assistant->user->name }}</strong>,</p>

    <p style="font-size: 16px; margin: 20px 0;">
        Lamentamos informarte que tu verificación como asistente en <strong>Komun</strong> ha sido <span style="color: #DC2626; font-weight: bold;">rechazada</span>.
    </p>

    <p style="font-size: 16px; margin: 20px 0;">
        <strong>Motivo del rechazo:</strong><br>
        <em>{{ $rejectionReason }}</em>
    </p>

    <p style="font-size: 16px; margin: 20px 0;">
        Te animamos a revisar tus documentos y volver a intentarlo. Si tienes alguna duda, puedes escribirnos directamente y estaremos encantados de ayudarte.
    </p>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ config('app.url') }}/verificaciones" style="background-color: #4F46E5; color: white; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">
            Reintentar verificación
        </a>
    </div>

    <p style="font-size: 12px; color: #777; margin-top: 40px; text-align: center;">
        Este es un mensaje automático, por favor no respondas a este correo.
    </p>
</div>
</body>
</html>
