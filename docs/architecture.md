# Arquitetura do Projeto

## Visão Geral

O projeto é uma aplicação web PHP orientada a páginas, com uma camada pública para apresentação e um painel administrativo para gestão de conteúdo. A organização atual prioriza simplicidade e manutenção manual por parte da equipe responsável.

## Componentes Principais

### Camada Pública

- index.php: página inicial institucional
- produtos.php: catálogo público com filtros
- produto_detalhe.php: página de detalhe do produto
- catalogo.php: apresentação de catálogos e materiais
- contato.php: formulário de contato
- politica_cookies.php: política de uso de cookies

### Camada Administrativa

- admin/login.php: tela de autenticação
- admin/index.php: painel do administrador
- admin/categorias.php: cadastro e listagem de categorias e subcategorias
- admin/produtos.php: cadastro, edição e listagem de produtos

### Camada de Serviços e Persistência

- php/db_connect.php: conexão com o banco de dados MySQLi
- php/auth.php: autenticação de usuários admin
- php/admin_api.php: processamento das operações de CRUD
- php/processa_contato.php: validação do formulário de contato

## Fluxo de Dados

1. O usuário acessa as páginas públicas.
2. O PHP inclui arquivos compartilhados como includes/header.php.
3. As páginas consultam o banco para montar menus, filtros e listagens.
4. O painel administrativo usa sessão PHP para proteger o acesso.
5. As ações de cadastro e edição são processadas por php/admin_api.php.

## Persistência

O banco de dados é acessado diretamente via MySQLi, com consultas preparadas. A estrutura inclui, no mínimo, entidades para:

- categorias
- subcategorias
- produtos
- admin_users

## Pontos de Atenção

- As credenciais de conexão estão hardcoded no arquivo de conexão.
- O envio de e-mail depende da configuração do servidor PHP.
- O upload de imagens depende das permissões da pasta uploads/products.
- As regras de reescrita de URL são controladas por .htaccess.
