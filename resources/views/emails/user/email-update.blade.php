@component('mail::message')
# {{$name}}, confirme a atualização do email da sua conta!

Você recebeu este email pois solicitou a atualização do email da sua conta de <b>{{$old_email}}</b> para <b>{{$new_email}}</b>.<br><br>
Agora precisamos apenas que você confirme a atualização clicando no botão 'Confirmar' abaixo.

@component('mail::button', ['url' => $verification_url])
Confirmar
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
