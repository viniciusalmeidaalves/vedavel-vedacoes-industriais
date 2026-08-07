# Documentação do Banco de Dados

## Visão Geral

O projeto utiliza MySQL/MariaDB acessado por meio da extensão MySQLi do PHP. A conexão é configurada em `php/db_connect.php` e reutilizada pelas páginas públicas e administrativas.

## Entidades Identificadas

As consultas presentes no código referenciam as seguintes tabelas:

| Tabela | Finalidade identificada |
|---|---|
| `admin_users` | Usuários da autenticação administrativa |
| `categorias` | Categorias de produtos |
| `subcategorias` | Subcategorias vinculadas a categorias |
| `produtos` | Dados, classificação e metadados dos produtos |

## Relacionamentos Identificados

- `subcategorias.categoria_id` referencia `categorias.id`.
- `produtos.categoria_id` referencia `categorias.id`.
- `produtos.subcategoria_id` referencia `subcategorias.id` quando aplicável.
- O fluxo de autenticação consulta `admin_users` por `username`.

## Operações Identificadas

- Leitura de categorias e subcategorias para o menu público.
- Filtragem e paginação de produtos no catálogo.
- Inclusão, edição e exclusão de categorias.
- Inclusão e exclusão de subcategorias.
- Inclusão, edição e exclusão de produtos.
- Consulta de usuários para autenticação administrativa.

## Fonte e Limitações

Não foi encontrado neste repositório um script SQL, migration ou dump com o schema completo. Por isso, este documento registra apenas tabelas, campos e relacionamentos comprovados pelas consultas PHP. Tipos, índices, constraints e regras de exclusão devem ser documentados quando o schema oficial estiver disponível.

## Configuração e Segurança

As credenciais atuais estão definidas diretamente em `php/db_connect.php`. Para ambientes de produção, recomenda-se usar configuração externa ou variáveis de ambiente e manter backups do banco fora do repositório.
