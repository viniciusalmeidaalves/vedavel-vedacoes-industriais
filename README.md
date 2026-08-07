# Vedavel - Vedações Industriais

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![Apache](https://img.shields.io/badge/Apache-D22128?style=for-the-badge&logo=apache&logoColor=white)
![Documentation](https://img.shields.io/badge/Docs-Repository%20Standardized-34A853?style=for-the-badge)

## Visão Geral

Este repositório contém o site institucional e o portal administrativo da Vedavel, empresa especializada em soluções de vedação industrial. O projeto é composto por páginas públicas para apresentação da marca, catálogo de produtos, formulário de contato e uma área administrativa para gestão de categorias, subcategorias e produtos.

## Objetivo do Projeto

O objetivo principal é disponibilizar um site profissional para exibir o portfólio da Vedavel, facilitar o contato com clientes e permitir que a equipe administrativa gerencie o conteúdo do catálogo de forma simples.

## Tecnologias Utilizadas

- PHP 7+ / 8+ para a lógica do site e do painel administrativo
- MySQL/MariaDB com conexão MySQLi
- HTML5, CSS3 e JavaScript para a interface pública
- Apache com suporte a reescrita de URLs via .htaccess
- Font Awesome para ícones
- Google reCAPTCHA e Google Analytics/Tag Manager no site público

## Principais Funcionalidades

- Página inicial institucional com destaque para produtos e depoimentos
- Catálogo público de produtos
- Páginas de detalhe para produtos com URL amigável baseada em slug
- Formulário de contato com validação e envio por e-mail
- Área administrativa com autenticação para:
  - gerenciamento de categorias
  - gerenciamento de subcategorias
  - gerenciamento de produtos
- Upload de imagens para produtos

## Estrutura do Repositório

```text
admin/                  # páginas e templates do painel administrativo
css/                    # estilos do site
includes/               # templates compartilhados, como o cabeçalho
js/                     # scripts JavaScript do front-end
midias/                 # imagens, vídeos e ícones
php/                    # conexão com banco de dados, autenticação e APIs administrativas
uploads/products/       # imagens enviadas para produtos
docs/                   # documentação técnica e operacional
CHANGELOG.md            # histórico de alterações relevantes
.gitignore              # arquivos locais e temporários ignorados pelo Git
```

## Requisitos

Para executar este projeto localmente ou em um servidor web, são necessários:

- Servidor web com suporte a PHP
- Extensão MySQLi habilitada
- Banco de dados MySQL/MariaDB
- Apache com mod_rewrite habilitado para as regras do arquivo .htaccess
- Permissões de escrita na pasta uploads/products para uploads de imagens

## Instalação

1. Copie o conteúdo deste projeto para o diretório raiz do seu servidor web.
2. Ajuste as credenciais de conexão no arquivo php/db_connect.php.
3. Crie o banco de dados e as tabelas necessárias para os módulos de categorias, subcategorias, produtos e administração.
4. Acesse o projeto pelo navegador.
5. Para o painel administrativo, use a rota admin/login.php.

> Observação: as credenciais de banco de dados atuais estão definidas diretamente no arquivo de conexão. Para ambientes de produção, recomenda-se mover essas configurações para variáveis de ambiente ou um arquivo de configuração externo.

## Uso

- A navegação pública é feita pelas páginas principais do site, como index.php, produtos.php, catalogo.php e contato.php.
- O conteúdo administrativo pode ser gerenciado pelo painel em admin/.
- O menu público é montado dinamicamente a partir de categorias e subcategorias cadastradas no banco.

## Documentação

A documentação complementar encontra-se na pasta docs/:

- docs/architecture.md
- docs/database.md
- docs/workflow.md
- docs/installation.md
- docs/requirements.md
- docs/roadmap.md
- docs/technologies.md

O histórico de alterações do repositório está em [CHANGELOG.md](CHANGELOG.md).

## Capturas e Mídias

As mídias do projeto estão organizadas em midias/ e incluem imagens de produtos, banners, ícones e vídeos do site.

## Licença

A licença deste projeto não foi definida no repositório. Recomendamos adicionar um arquivo de licença antes de publicar ou compartilhar o código publicamente.
