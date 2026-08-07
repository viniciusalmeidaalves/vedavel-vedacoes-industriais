# Workflow do Projeto

## Fluxo de Desenvolvimento

Este projeto segue um fluxo simples de manutenção manual de páginas PHP, CSS e conteúdo. A atualização de produtos e categorias é realizada principalmente pelo painel administrativo.

## Workflow Público

1. O visitante acessa a home ou as páginas de conteúdo.
2. O cabeçalho e o rodapé são incluídos por templates compartilhados.
3. Os produtos e categorias são carregados dinamicamente a partir do banco.
4. O formulário de contato valida dados e envia e-mail.

## Workflow Administrativo

1. O administrador acessa admin/login.php.
2. O login é processado por php/auth.php.
3. Após autenticação, o usuário entra no painel em admin/index.php.
4. A gestão de categorias e produtos é feita nas páginas admin/categorias.php e admin/produtos.php.
5. As operações são enviadas para php/admin_api.php para persistência.

## Processo de Atualização de Conteúdo

- Para adicionar ou editar categorias: usar o módulo de categorias no painel administrativo.
- Para adicionar ou editar produtos: usar o módulo de produtos no painel administrativo.
- Para atualizar imagens: fazer upload via formulário de cadastro/edição de produto.
- Para ajustar navegação e layout: editar os arquivos PHP e CSS correspondentes.

## Boas Práticas Recomendadas

- Testar mudanças nas páginas públicas e no painel administrativo após cada alteração.
- Validar uploads de imagem e permissões da pasta uploads/products.
- Revisar mensagens de erro e logs do PHP em ambientes de homologação.
- Evitar alterações no fluxo de autenticação sem revisar a segurança do sistema.
