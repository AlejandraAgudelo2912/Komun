<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de tu Solicitud</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9fafb; color: #333;">
<div style="max-width: 600px; margin: 40px auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
    <h2 style="color: #4F46E5;">Hola {{ $applicant->name }},</h2>

    <p style="font-size: 16px;">
        Tu solicitud a la petición <strong>"{{ $requestModel->title }}"</strong> ha sido
        <strong style="color: {{ $status === 'accepted' ? '#10B981' : '#EF4444' }};">
            {{ $status === 'accepted' ? 'aceptada' : 'rechazada' }}
        </strong>.
    </p>

    @if($status === 'accepted')
        <p style="font-size: 16px;">
            🎉 ¡Enhorabuena! Pronto se pondrán en contacto contigo para coordinar la ayuda.
        </p>
    @else
        <p style="font-size: 16px;">
            Agradecemos sinceramente tu disposición. ❤️ ¡Te animamos a seguir participando en otras solicitudes!
        </p>
    @endif

    <div style="margin-top: 30px; text-align: center;">
        <a href="{{ url('/requests') }}" style="background-color: #4F46E5; color: #ffffff; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-size: 16px;">
            Ver otras solicitudes
        </a>
    </div>

    <p style="font-size: 16px; margin-top: 30px;">Un saludo cordial,<br><strong>El equipo de Red de Apoyo Local</strong></p>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280;">
        <p>Este es un correo automático. Por favor, no respondas directamente a este mensaje.</p>
    </div>
</div>
</body>
</html>
