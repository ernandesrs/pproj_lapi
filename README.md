# LAPI - (Laravel Api)
Um projeto para práticas de criação de APIs com Laravel 9.

# RECURSOS IMPLEMENTADOS
## Autenticação
Login, Logout, Esqueci a senha, Atualização de senha, Registro, Envio de link de verificação e Reenvio de link de verificação.

## Divisão Admin/Cliente
    * Implementado divisão entre área administrativa e área do usuário/cliente.

### [Cliente] Perfil
Atualização, Upload de foto, Exclusão de foto, Exclusão de conta e Recuperação de conta.

### [Admin] Gerenciamento de usuários
    * CRUD completo além de atribuição de funções.

### [Admin] Gerenciamento de funções
    * CRUD completo.

# CONFIGURAÇÃO
Algumas configurações deverão ser feitas e alguns comandos deverão ser executados para rodar o projeto.

## Configurações do .env
Copie e renomeie o arquivo .env.example para .env e então faça as alterações, de acordo com o nível de importância mostrada na tabela abaixo:

| Variável | Nível de importância | Descrição |
| --- | --- | --- |
| APP_URL_FRONT | Alta | Url do frontend. Necessário, pois apenas requisições desta URL será aceita. |
| DAYS_TO_DELETE_UNVERIFIED_USER | Baixo | Dias para excluir usuários não verificados. Se nulo, os usuários não serão excluídos. |
| OAUTH2_GOOGLE_CLIENT_ID | Baixo | ID da aplicação no Google. Quando nulo, o login via Google será desabilitada. |
| OAUTH2_GOOGLE_CLIENT_SECRET | Baixo | Chave secreta da aplicação no Google. Quando nulo, o login via Google será desabilitada. |
| DB_* | Alta | Variáveis de banco de dados devem ser configurados. |
| MAIL_* | Alta | Variáveis de email devem ser configurados. |

## Configurações do config/lapi.php
Algumas variáveis do arquivo de configuração em <b>/config/lapi.php</b> podem ser configuradas, veja:

| Variável | Nível de importância | Descrição |
| --- | --- | --- |
| url_front_password_reset | Alta | Esta URL será enviada por e-mail quando o usuário solicitar um link de atualização de senha e possuirá o parâmetro <b>token</b> contendo o token de atualização. |
| url_front_user_verify | Alta | Esta URL será enviada por e-mail quando um usuário se registrar, será um link verificação e possuirá o parâmetro <b>token</b> contendo o token de verificação. |
| url_front_user_email_update | Alta | Será enviada quando o usuário solicitar atualização de e-mail da conta. O link possuirá um parâmetro <b>token</b> contendo o token de atualização. |
| url_front_social_login_callback | Baixa | Obrigatório quando um login via rede social estiver habilitada. Esta url será chamada(em caso de sucesso ou falha) em login com rede social. O link possuirá os parâmetros <b>token</b>, <b>type</b>, <b>full</b> e <b>expire_in_minutes</b>. |

## Instalação dos pacotes
> composer install

## Gerar chave secreta do Laravel
> php artisan key:generate

## Gerar link para pasta pública
> php artisan storage:link

## Gerar chave secreva do JWT-AUTH
> php artisan jwt:secret

## Gerar as tabelas
> php artisan migrate

## Gerar dados para a aplicação
Execute o seguinte comando para criar um super usuário(para o email e senha fornecido), além de criar mais dois usuários(Admin e Visitor) e também criar duas funções iniciais(Admin, Visitor).
> php artisan lapi:start --mail=mail@mail.com --pass=password

Execute o seguinte comando para realizar as ações citadas acima e também popular o banco de dados com informações(usuários) fake para testes.
> php artisan lapi:start --mail=mail@mail.com --pass=password --seed

Execute o seguinte comando para realizar as ações citadas acima, mas antes executar o comando 'migrate:fresh' para resetar a base de dados e popular com novos dados.
> php artisan lapi:start --mail=mail@mail.com --pass=password --fresh --seed

## Execute o servidor
> php artisan serve

# A documentação da API
Documentação criada da API, acesse em: https://documenter.getpostman.com/view/15369452/2s935oL4Dv