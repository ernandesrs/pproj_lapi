# LAPI - (Laravel Api)
Um projeto para práticas de criação de APIs com Laravel 9.

## Recursos implementados
### Autenticação
Login, Logout, Esqueci a senha, Atualização de senha, Registro, Envio de link de verificação e Reenvio de link de verificação.

### Perfil
Atualização, Upload de foto, Exclusão de foto, Exclusão de conta e Recuperação de conta.

### Gerenciamento de usuários
    CRUD completo além de atribuições de funções.

### Gerenciamento de funções/permissões(administrador, usuários, etc)
    CRUD completo.

### Iniciando o projeto
Execute o seguinte comando para criar um usuário principal juntamente com alguns dados padrões.
> php artisan lapi:start --mail=mail@mail.com --pass=password

Execute o seguinte comando para criar um usuário principal juntamente com alguns dados padrões e também popular as tabelas com dados fakes.
> php artisan lapi:start --mail=mail@mail.com --pass=password --seed