<h2>Hola {{ $applicant->name }},</h2>

<p>Tu solicitud a la petición <strong>{{ $requestModel->title }}</strong> ha sido <strong>{{ $status === 'accepted' ? 'aceptada' : 'rechazada' }}</strong>.</p>

@if($status === 'accepted')
    <p>Pronto se pondrán en contacto contigo para coordinar la ayuda.</p>
@else
    <p>Agradecemos tu disposición. ¡Te animamos a seguir ayudando en otras solicitudes!</p>
@endif

<p>Un saludo,<br>El equipo de Red de Apoyo Local</p>
