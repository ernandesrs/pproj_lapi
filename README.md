# LAPI - (Laravel Api)
Um projeto para práticas de criação de APIs com Laravel 9.

# Recursos implementados
## Autenticação
Login, Logout, Esqueci a senha, Atualização de senha, Registro, Envio de link de verificação e Reenvio de link de verificação.

## Perfil
Atualização, Upload de foto, Exclusão de foto, Exclusão de conta e Recuperação de conta.

## Gerenciamento de usuários
    * CRUD completo além de atribuições de funções.

## Gerenciamento de funções/permissões(administrador, usuários, etc)
    * CRUD completo.

## Divisão Admin/Cliente
    * Implementado divisão entre área administrativa e área do usuário/cliente.
    * Esquema simples de assinatura de pacote com cobrança via Pagar.me.

# Iniciando o projeto
Algumas configurações deverão ser feitas e alguns comandos deverão ser executados para rodar o projeto.

## Configurações do .env
Copie e renomeie o arquivo .env.example para .env e então faça as seguintes configurações:
    * Informações para acesso ao banco de dados,
    * Dados para envio de e-mails,
    * Chave de api da Pagar.me,
    * além de definir o nome e url do projeto.

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
Execute o seguinte comando para criar um usuário principal juntamente com alguns dados padrões.
> php artisan lapi:start --mail=mail@mail.com --pass=password

Execute o seguinte comando para criar um usuário principal juntamente com alguns dados padrões e também popular as tabelas com dados fakes.
> php artisan lapi:start --mail=mail@mail.com --pass=password --seed

## Execute o servidor
> php artisan serve

# A documentação da API
Documentação criada com Postman, acesse em: https://documenter.getpostman.com/view/15369452/2s935oL4Dv