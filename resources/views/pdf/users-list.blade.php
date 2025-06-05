<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Listado de Usuarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1a202c;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #4a5568;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f7fafc;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .filters {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
        }
        .filters p {
            margin: 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Listado de Usuarios</h1>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if($filters['search'] || $filters['role'] || $filters['status'])
        <div class="filters">
            <h3>Filtros aplicados:</h3>
            @if($filters['search'])
                <p><strong>Búsqueda:</strong> {{ $filters['search'] }}</p>
            @endif
            @if($filters['role'])
                <p><strong>Rol:</strong> {{ ucfirst($filters['role']) }}</p>
            @endif
            @if($filters['status'])
                <p><strong>Estado:</strong> {{ $filters['status'] === 'verified' ? 'Verificado' : 'No verificado' }}</p>
            @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                @if($role === 'god')
                    <th>Permisos</th>
                @endif
                <th>Estado Asistente</th>
                <th>Verificado</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    @if($role === 'god')
                        <td>{{ $user->permissions->pluck('name')->join(', ') }}</td>
                    @endif
                    <td>
                        @if($user->assistant)
                            {{ ucfirst($user->assistant->status) }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($user->assistant)
                            {{ $user->assistant->is_verified ? 'Sí' : 'No' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Documento generado automáticamente por el sistema Komun</p>
        <p>Página 1 de 1</p>
    </div>
</body>
</html> 