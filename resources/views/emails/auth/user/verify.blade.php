@component('mail::message')
# {{$username}}, confirme a criação da sua conta!

Você recebeu este email pois se cadastrou no site {{config('app.name')}}. Agora precisamos apenas que você confirme a criação da sua conta clicando no botão 'Verificar' abaixo.

@component('mail::button', ['url' => $verification_url])
Verificar
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
