@component('mail::message')
# ¡Tu solicitud fue aprobada!

Hola **{{ $solicitud->nombre }}**,

Tu solicitud para registrar la empresa fue aprobada. Para activar tu cuenta y configurar una contraseña, haz clic en el siguiente botón:

@component('mail::button', ['url' => $link])
Activar mi cuenta
@endcomponent

Si no pediste esto, ignora este correo.

Gracias,  
{{ config('app.name') }}
@endcomponent
