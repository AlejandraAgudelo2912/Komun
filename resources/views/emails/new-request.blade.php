<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nueva solicitud en {{ $category->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: {{ $category->color }};
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: {{ $category->color }};
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Nueva solicitud en {{ $category->name }}</h1>
</div>

<div class="content">
    <p>¡Hola {{ $user->name }}!</p>

    <p>Se ha creado una nueva solicitud en la categoría <strong>{{ $category->name }}</strong> que podría interesarte:</p>

    <h2>{{ $request->title }}</h2>

    <p>{{ $request->description }}</p>

    @if($request->deadline)
        <p><strong>Fecha límite:</strong> {{ $request->deadline->format('d/m/Y H:i') }}</p>
    @endif

    @if($request->budget)
        <p><strong>Presupuesto:</strong> {{ number_format($request->budget, 2) }}€</p>
    @endif

    <a href="{{ url('/requests/' . $request->id) }}" class="button">Ver solicitud</a>
</div>

<div class="footer">
    <p>Este email fue enviado porque sigues la categoría {{ $category->name }} en Komun.</p>
    <p>Si no deseas recibir más notificaciones de esta categoría, puedes desactivarlas en tu perfil.</p>
</div>
</body>
</html>
