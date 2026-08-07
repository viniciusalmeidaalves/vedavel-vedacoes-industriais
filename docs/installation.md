# Instalação e Configuração

## Pré-requisitos

- Servidor web Apache ou Nginx com suporte a PHP
- PHP com extensão mysqli habilitada
- MySQL/MariaDB
- Acesso ao diretório do projeto via terminal ou gerenciador de arquivos

## Passo a Passo

1. Coloque o projeto no diretório público do servidor.
2. Ajuste o arquivo php/db_connect.php com as credenciais corretas do banco.
3. Crie o banco de dados e as tabelas necessárias.
4. Garanta que a pasta uploads/products tenha permissão de escrita.
5. Habilite o módulo de reescrita do Apache para que as regras do .htaccess funcionem corretamente.
6. Acesse o site pelo navegador.

## Configuração do Banco

O arquivo php/db_connect.php define:

- servidor
- usuário
- senha
- nome do banco

A conexão é aberta automaticamente ao incluir o arquivo.

## Configuração de Ambiente

Para produção, recomenda-se:

- mover as credenciais para variáveis de ambiente ou um arquivo separado
- proteger o painel administrativo com HTTPS
- revisar as chaves de reCAPTCHA e os dados de e-mail
- manter backup do banco e das pastas de upload
