<!DOCTYPE html>
<html>
<head>
    <title>Verificación Rechazada</title>
</head>
<body>
<h1>Hola {{ $assistant->user->name }}</h1>
<p>Lamentamos informarte que tu verificación como asistente ha sido <strong>rechazada</strong>.</p>
<p><strong>Motivo:</strong> {{ $rejectionReason }}</p>
<p>Por favor revisa los documentos enviados y vuelve a intentarlo. Si tienes dudas, puedes contactarnos directamente.</p>
</body>
</html>
