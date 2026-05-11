{{ $subject }}
====================================

{!! \Illuminate\Support\Str::of($bodyMarkdown)
    ->replaceMatches('/\*\*(.*?)\*\*/u', '$1')
    ->replaceMatches('/\*(.*?)\*/u', '$1')
    ->replaceMatches('/\[(.*?)\]\((.*?)\)/u', '$1 ($2)')
    ->replaceMatches('/^#{1,6}\s+/m', '')
!!}

---
Recibes este correo porque tienes una cuenta en Citora ({{ config('app.url') }}).
Cancelar suscripción: {{ $unsubscribeUrl }}
