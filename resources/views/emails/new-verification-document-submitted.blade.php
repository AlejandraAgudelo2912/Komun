<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{__('New verification document submitted')}}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 20px; color: #333;">
<div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 2px 6px rgba(0,0,0,0.05);">
    <h1 style="color: #4F46E5; font-size: 24px; margin-bottom: 20px;">📄 Nuevo documento de verificación</h1>

    <p style="font-size: 16px; margin-bottom: 10px;">
        {{__('The assistant ')}} <strong>{{ $assistantVerificationDocument->assistant->user->name }}</strong> {{__('has submitted a new verification document')}}.
    </p>

    <p style="font-size: 16px; margin-bottom: 30px;">
        {{__('Please access the platform to review them and take a decision.')}}
    </p>

    <p style="font-size: 12px; color: #777; margin-top: 40px; text-align: center;">
        {{__('This is an automated message, please do not reply to this email.')}}
    </p>
</div>
</body>
</html>
