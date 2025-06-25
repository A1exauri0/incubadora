<x-mail::message>

{{-- Título principal --}}

# ¡Bienvenido a IncubaTec!
{{-- Logo --}}
<div style="text-align: center;">
  <img src="https://edu.ieee.org/mx-ittg/wp-content/uploads/sites/536/image-768x755.png" alt="Logo del TEC" width="100" style="margin-bottom: 20px;">
</div>

{{-- Texto introductorio --}}
Por favor, haz clic en el siguiente botón para verificar tu correo.

{{-- Botón de acción --}}
<x-mail::button :url="$actionUrl" color="primary">
{{ $actionText }}
</x-mail::button>

{{-- Mensaje adicional --}}
Gracias por unirte a nuestra comunidad. Esperamos que disfrutes la experiencia.

{{-- Despedida --}}
Saludos,  
{{ config('app.name') }}

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "Si tienes problemas haciendo clic en el botón \":actionText\", copia y pega la siguiente URL\n".
    'en tu navegador web:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
