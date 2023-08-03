# LAPI - (Laravel Api)
Um projeto para práticas de criação de APIs com Laravel 9.

# RECURSOS IMPLEMENTADOS
## Autenticação
Login, Logout, Esqueci a senha, Atualização de senha, Registro, Envio de link de verificação e Reenvio de link de verificação.

## Perfil
Atualização, Upload de foto, Exclusão de foto, Exclusão de conta e Recuperação de conta.

## Gerenciamento de usuários
    * CRUD completo além de atribuição de funções.

## Gerenciamento de funções
    * CRUD completo.

## Divisão Admin/Cliente
    * Implementado divisão entre área administrativa e área do usuário/cliente.

# CONFIGURAÇÃO
Algumas configurações deverão ser feitas e alguns comandos deverão ser executados para rodar o projeto.

## Configurações do .env
Copie e renomeie o arquivo .env.example para .env e então faça as seguintes alterações:

    * Informações para acesso ao banco de dados<i>(Necessário)</i>;
    * Dados para envio de e-mails<i>(Necessário)</i>,
    * além de definir o nome e url do projeto<i>(Opcional, o padrão Laravel está definido)</i>.

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
Execute o seguinte comando para criar um usuário super(para o email e senha fornecido), além de criar mais dois usuários(Admin e Visitor) e criar também duas funções iniciais(Admin, Visitor).
> php artisan lapi:start --mail=mail@mail.com --pass=password

Execute o seguinte comando para realizar as ações citadas acima e também popular a o banco de dados com informações fake para testes.
> php artisan lapi:start --mail=mail@mail.com --pass=password --seed

Execute o seguinte comando para realizar as ações citadas acima, mas antes executar o comando 'migrate:fresh' para resetar a base de dados e popular com novos dados.
> php artisan lapi:start --mail=mail@mail.com --pass=password --seed

## Execute o servidor
> php artisan serve

# A documentação da API
Documentação criada da API, acesse em: https://documenter.getpostman.com/view/15369452/2s935oL4Dv