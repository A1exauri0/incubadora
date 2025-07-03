{{-- resources/views/emails/proposals/new_proposal_alert.blade.php --}}
<x-mail::message>
# ¡Nueva Propuesta de Proyecto Pendiente!

<div style="text-align: center;">
    <img src="https://edu.ieee.org/mx-ittg/wp-content/uploads/sites/536/image-768x755.png" alt="Logo del TEC" width="100" style="margin-bottom: 20px;">
</div>

Hola Administrador,

Se ha enviado una nueva propuesta de proyecto que requiere su revisión.

**Detalles de la Propuesta:**
* **Nombre del Proyecto:** {{ $projectName }}
* **Clave del Proyecto:** {{ $projectClave }}
* **Enviado por:** {{ $submitterName }}

Por favor, haga clic en el siguiente botón para ir a la sección de revisión de propuestas:

<x-mail::button :url="$proposalLink" color="primary">
Revisar Propuestas
</x-mail::button>

Gracias,
{{ config('app.name') }}

{{-- Si quieres que el subcopy con el enlace directo aparezca, puedes incluirlo aquí --}}
<x-slot:subcopy>
Si tienes problemas haciendo clic en el botón "Revisar Propuestas", copia y pega la siguiente URL en tu navegador web: [{{ $proposalLink }}]({{ $proposalLink }})
</x-slot:subcopy>
</x-mail::message>
