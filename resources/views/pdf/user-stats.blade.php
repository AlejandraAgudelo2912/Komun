<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estadísticas de Usuario - {{ $user->name }}</title>
    <style type="text/css">
        @page {
            margin: 20px;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #2d3748;
            font-size: 12px;
            line-height: 1.5;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
        }

        .title {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 20px;
        }

        .section {
            margin: 20px 0;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            color: #2d3748;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .stats-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .stats-row {
            display: table-row;
        }

        .stat-box {
            display: table-cell;
            width: 50%;
            padding: 15px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            vertical-align: top;
        }

        .stat-title {
            font-weight: bold;
            color: #4a5568;
            margin-bottom: 5px;
            font-size: 11px;
        }

        .stat-value {
            font-size: 18px;
            color: #2d3748;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding: 10px 0;
        }

        .page-number {
            text-align: center;
            font-size: 10px;
            color: #718096;
        }

        .generated-at {
            text-align: center;
            font-size: 10px;
            color: #718096;
            margin-top: 20px;
        }

        /* Estilos específicos para cada tipo de estadística */
        .request-stats {
            background: #ebf8ff !important;
            border-color: #bee3f8 !important;
        }

        .applied-stats {
            background: #f0fff4 !important;
            border-color: #c6f6d5 !important;
        }

        .message-stats {
            background: #faf5ff !important;
            border-color: #e9d8fd !important;
        }

        .comment-stats {
            background: #fff5f5 !important;
            border-color: #fed7d7 !important;
        }

        .review-stats {
            background: #fefcbf !important;
            border-color: #faf089 !important;
        }

        .activity-stats {
            background: #e2e8f0 !important;
            border-color: #cbd5e0 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <img src="data:image/svg+xml;base64,{{ base64_encode('
                <svg viewBox="0 0 64.00 64.00" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 100px; height: 100px;" preserveAspectRatio="xMidYMid meet" fill="#46a09a" stroke="#46a09a" stroke-width="1.28">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.64"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M32 2C15.432 2 2 15.432 2 32s13.432 30 30 30s30-13.432 30-30S48.568 2 32 2m6.016 44.508l-8.939-12.666l-2.922 2.961v9.705h-5.963V17.492h5.963v11.955l11.211-11.955h7.836L33.293 29.426l12.518 17.082h-7.795" fill="#b8edff"></path>
                    </g>
                </svg>
            ') }}" class="logo" />
            <h1 class="title">Estadísticas de Usuario</h1>
            <h2 class="subtitle">{{ $user->name }}</h2>
        </div>

        <!-- Contenido -->
        <div class="content">
            <!-- Estadísticas de Solicitudes -->
            <div class="section">
                <h3 class="section-title">Estadísticas de Solicitudes</h3>
                <div class="stats-grid">
                    <div class="stats-row">
                        <div class="stat-box request-stats">
                            <div class="stat-title">Total de Solicitudes</div>
                            <div class="stat-value">{{ $stats['request_stats']['total_requests'] }}</div>
                        </div>
                        <div class="stat-box request-stats">
                            <div class="stat-title">Solicitudes Pendientes</div>
                            <div class="stat-value">{{ $stats['request_stats']['active_requests'] }}</div>
                        </div>
                    </div>
                    <div class="stats-row">
                        <div class="stat-box request-stats">
                            <div class="stat-title">En Progreso</div>
                            <div class="stat-value">{{ $stats['request_stats']['in_progress_requests'] }}</div>
                        </div>
                        <div class="stat-box request-stats">
                            <div class="stat-title">Completadas</div>
                            <div class="stat-value">{{ $stats['request_stats']['completed_requests'] }}</div>
                        </div>
                    </div>
                    <div class="stats-row">
                        <div class="stat-box request-stats">
                            <div class="stat-title">Canceladas</div>
                            <div class="stat-value">{{ $stats['request_stats']['cancelled_requests'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Solicitudes Aplicadas -->
            <div class="section">
                <h3 class="section-title">Solicitudes Aplicadas</h3>
                <div class="stats-grid">
                    <div class="stat-box applied-stats">
                        <div class="stat-title">Total de Aplicaciones</div>
                        <div class="stat-value">{{ $stats['applied_stats']['total_applied'] }}</div>
                    </div>
                    <div class="stat-box applied-stats">
                        <div class="stat-title">Pendientes</div>
                        <div class="stat-value">{{ $stats['applied_stats']['pending_applied'] }}</div>
                    </div>
                    <div class="stat-box applied-stats">
                        <div class="stat-title">Aceptadas</div>
                        <div class="stat-value">{{ $stats['applied_stats']['accepted_applied'] }}</div>
                    </div>
                    <div class="stat-box applied-stats">
                        <div class="stat-title">Rechazadas</div>
                        <div class="stat-value">{{ $stats['applied_stats']['rejected_applied'] }}</div>
                    </div>
                    <div class="stat-box applied-stats">
                        <div class="stat-title">Canceladas</div>
                        <div class="stat-value">{{ $stats['applied_stats']['cancelled_applied'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Mensajes -->
            <div class="section">
                <h3 class="section-title">Estadísticas de Mensajes</h3>
                <div class="stats-grid">
                    <div class="stat-box message-stats">
                        <div class="stat-title">Total de Mensajes</div>
                        <div class="stat-value">{{ $stats['message_stats']['total_messages'] }}</div>
                    </div>
                    <div class="stat-box message-stats">
                        <div class="stat-title">Mensajes Enviados</div>
                        <div class="stat-value">{{ $stats['message_stats']['sent_messages'] }}</div>
                    </div>
                    <div class="stat-box message-stats">
                        <div class="stat-title">Mensajes Recibidos</div>
                        <div class="stat-value">{{ $stats['message_stats']['received_messages'] }}</div>
                    </div>
                    <div class="stat-box message-stats">
                        <div class="stat-title">Último Mensaje</div>
                        <div class="stat-value">{{ $stats['message_stats']['last_message_date'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Comentarios -->
            <div class="section">
                <h3 class="section-title">Estadísticas de Comentarios</h3>
                <div class="stats-grid">
                    <div class="stat-box comment-stats">
                        <div class="stat-title">Total de Comentarios</div>
                        <div class="stat-value">{{ $stats['comment_stats']['total_comments'] }}</div>
                    </div>
                    <div class="stat-box comment-stats">
                        <div class="stat-title">Comentarios Recientes (30 días)</div>
                        <div class="stat-value">{{ $stats['comment_stats']['recent_comments'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Reseñas -->
            <div class="section">
                <h3 class="section-title">Estadísticas de Reseñas</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-title">Total de Reseñas</div>
                        <div class="stat-value">{{ $stats['review_stats']['total_reviews'] }}</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Valoración Promedio</div>
                        <div class="stat-value">
                            <span class="rating-value">{{ $stats['review_stats']['average_rating'] }}</span>
                            <span class="rating-label">/5</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Reseñas Recientes (30 días)</div>
                        <div class="stat-value">{{ $stats['review_stats']['recent_reviews'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas de Actividad -->
            <div class="section">
                <h3 class="section-title">Actividad en la Plataforma</h3>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-title">Fecha de Registro</div>
                        <div class="stat-value">{{ $stats['activity_stats']['join_date'] }}</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-title">Último Acceso</div>
                        <div class="stat-value">{{ $stats['activity_stats']['last_login'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>Documento generado automáticamente por Komun</p>
            <p>Página <span class="page-number"></span></p>
            <p>© {{ date('Y') }} Komun - Plataforma de Ayuda Comunitaria</p>
            <p class="generated-at">Generado el {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>
