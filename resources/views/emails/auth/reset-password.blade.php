@component('mail::message')
# {{$username}}, aqui está seu link de recuperação de senha!

Você está recebendo este email pois você solicitou um link de recuperação de senha! Clique sobre o link abaixo e inicie o processo de atualização da sua senha e obter o acesso a sua conta novamente.

@component('mail::button', ['url' => $reset_url])
Recuperar senha
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
