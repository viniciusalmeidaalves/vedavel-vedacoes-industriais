<?php
// =========================================================
// --- admin/produtos.php ---
// Página para gerenciamento (cadastro, listagem, edição, exclusão) de produtos.
// =========================================================

session_start(); // Inicia a sessão

// Inclui o arquivo de conexão com o banco de dados
// O caminho '../php/db_connect.php' significa:
// Voltar uma pasta (de 'admin' para 'projeto-vedavel') e então entrar na pasta 'php'.
require_once '../php/db_connect.php';

// Verifica se o usuário NÃO está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    $_SESSION['login_message'] = "Você precisa fazer login para acessar esta página.";
    header('Location: login.php');
    exit;
}

// --- Variáveis para Edição ---
$edit_product_id = $_GET['edit_id'] ?? null;
$product_to_edit = null;

// Se um ID de edição foi passado, busca os dados do produto
if ($edit_product_id) {
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $edit_product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $product_to_edit = $result->fetch_assoc();
    } else {
        $_SESSION['admin_message'] = ['text' => "Produto não encontrado para edição.", 'type' => 'error'];
        header('Location: produtos.php');
        exit;
    }
    $stmt->close();
}


// Função para buscar categorias (para o dropdown de seleção e filtro)
function getCategorias($conn) {
    $categorias = [];
    $sql = "SELECT id, nome FROM categorias ORDER BY nome ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
    return $categorias;
}

// --- Função para buscar produtos com filtros (Modificada para aceitar filtros e usar prepared statements) ---
function getProdutos($conn, $filter_category_id = null, $filter_subcategory_id = null) {
    $produtos = [];
    $sql = "SELECT p.*, c.nome AS categoria_nome, s.nome AS subcategoria_nome
            FROM produtos p
            JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN subcategorias s ON p.subcategoria_id = s.id";

    $where_clauses = [];
    $params = [];
    $param_types = '';

    if ($filter_category_id) {
        $where_clauses[] = "p.categoria_id = ?";
        $params[] = $filter_category_id;
        $param_types .= 'i';
    }

    if ($filter_subcategory_id) {
        $where_clauses[] = "p.subcategoria_id = ?";
        $params[] = $filter_subcategory_id;
        $param_types .= 'i';
    }

    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }

    $sql .= " ORDER BY p.nome ASC";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Failed to prepare statement for getProdutos: " . $conn->error);
        return [];
    }

    if (!empty($params)) {
        $stmt->bind_param($param_types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
    }
    $stmt->close();
    return $produtos;
}

// Buscar categorias para o formulário e filtro
$categorias_para_form = getCategorias($conn);

// --- Lógica de Filtro para a Listagem ---
$filter_category_id_list = $_GET['filter_category_id'] ?? null;
$filter_subcategory_id_list = $_GET['filter_subcategory_id'] ?? null;

// Buscar produtos para a listagem (agora com filtros)
$produtos_para_listagem = getProdutos($conn, $filter_category_id_list, $filter_subcategory_id_list);

// Lógica para exibir e limpar mensagens de status da sessão
$status_message_text = null;
$status_message_type = null;
if (isset($_SESSION['admin_message'])) {
    $status_message_text = $_SESSION['admin_message']['text'];
    $status_message_type = $_SESSION['admin_message']['type'];
    unset($_SESSION['admin_message']); // Limpa a mensagem após a exibição
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos | Admin Vedavel</title>
    <link rel="icon" href="../midias/icones/favicon-vedavel.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    </head>
<body>
    <?php include 'includes/admin_header.php'; ?>

    <div class="admin-container">
        <?php if ($status_message_text): ?>
            <div class="message <?php echo $status_message_type; ?>">
                <?php echo htmlspecialchars($status_message_text); ?>
            </div>
        <?php endif; ?>

        <div class="form-section">
            <h3><?php echo $product_to_edit ? 'Editar Produto' : 'Cadastrar Novo Produto'; ?></h3>
            <form action="../php/admin_api.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $product_to_edit ? 'update_product' : 'add_product'; ?>">
                <?php if ($product_to_edit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product_to_edit['id']); ?>">
                    <input type="hidden" name="current_image_path" value="<?php echo htmlspecialchars($product_to_edit['link_imagem_principal'] ?? ''); ?>">
                <?php endif; ?>
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="product_name">Nome do Produto:</label>
                            <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product_to_edit['nome'] ?? ''); ?>" required>
                            <small>Recomendado: conciso e descritivo (até 255 caracteres).</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="product_slug">URL Amigável (Slug):</label>
                            <input type="text" id="product_slug" name="product_slug" maxlength="255" value="<?php echo htmlspecialchars($product_to_edit['slug'] ?? ''); ?>">
                            <small>Ex: `nome-do-produto-aqui` (letras minúsculas, sem espaços ou caracteres especiais, use hífens).</small>
                        </div>
                        <div class="form-group">
                            <label for="product_short_description">Descrição Curta:</label>
                            <textarea id="product_short_description" name="product_short_description" rows="3"><?php echo htmlspecialchars($product_to_edit['descricao_curta'] ?? ''); ?></textarea>
                            <small>Recomendado: até 200 caracteres para resumos.</small>
                        </div>
                        <div class="form-group">
                            <label for="product_full_description">Descrição Completa:</label>
                            <textarea id="product_full_description" name="product_full_description" rows="5"><?php echo htmlspecialchars($product_to_edit['descricao_completa'] ?? ''); ?></textarea>
                            <small>Recomendado: até 500 caracteres para detalhes completos.</small>
                        </div>

                        <div class="form-group">
                            <label for="product_price">Preço:</label>
                            <input type="number" id="product_price" name="product_price" step="0.01" min="0" value="<?php echo (isset($product_to_edit['preco']) && $product_to_edit['preco'] !== null) ? htmlspecialchars($product_to_edit['preco']) : ''; ?>" <?php echo (isset($product_to_edit['preco']) && $product_to_edit['preco'] === null) ? 'disabled' : ''; ?>>
                            <label class="checkbox-label" style="margin-top: 10px;">
                                <input type="checkbox" id="price_consult_checkbox" name="price_consult" value="1" <?php echo (isset($product_to_edit['preco']) && $product_to_edit['preco'] === null) ? 'checked' : ''; ?>>
                                Sob Consulta
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="product_stock">Quantidade em Estoque:</label>
                            <input type="number" id="product_stock" name="product_stock" min="0" value="<?php echo (isset($product_to_edit['quantidade_estoque']) && $product_to_edit['quantidade_estoque'] !== null) ? htmlspecialchars($product_to_edit['quantidade_estoque']) : ''; ?>" <?php echo (isset($product_to_edit['quantidade_estoque']) && $product_to_edit['quantidade_estoque'] === null) ? 'disabled' : ''; ?>>
                            <label class="checkbox-label" style="margin-top: 10px;">
                                <input type="checkbox" id="stock_consult_checkbox" name="stock_consult" value="1" <?php echo (isset($product_to_edit['quantidade_estoque']) && $product_to_edit['quantidade_estoque'] === null) ? 'checked' : ''; ?>>
                                Sob Consulta
                            </label>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="product_measures">Medidas (Ex: 10x5x3 cm):</label>
                            <input type="text" id="product_measures" name="product_measures" value="<?php echo htmlspecialchars($product_to_edit['medidas'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_weight">Peso (KG):</label>
                            <input type="number" id="product_weight" name="product_weight" step="0.01" min="0" value="<?php echo htmlspecialchars($product_to_edit['peso'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_sku">SKU (Código Interno):</label>
                            <input type="text" id="product_sku" name="product_sku" maxlength="100" value="<?php echo htmlspecialchars($product_to_edit['sku'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_gtin">GTIN (EAN/UPC):</label>
                            <input type="text" id="product_gtin" name="product_gtin" maxlength="100" value="<?php echo htmlspecialchars($product_to_edit['gtin'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_brand">Marca:</label>
                            <input type="text" id="product_brand" name="product_brand" maxlength="255" value="<?php echo htmlspecialchars($product_to_edit['marca'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="product_mpn">MPN (Nº Peça Fabricante):</label>
                            <input type="text" id="product_mpn" name="product_mpn" maxlength="255" value="<?php echo htmlspecialchars($product_to_edit['mpn'] ?? ''); ?>">
                        </div>
                        </div>
                </div>
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="product_category">Categoria:</label>
                            <select id="product_category" name="product_category_id" required>
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias_para_form as $category): ?>
                                    <option value="<?php echo htmlspecialchars($category['id']); ?>"
                                        <?php echo (isset($product_to_edit['categoria_id']) && $product_to_edit['categoria_id'] == $category['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="product_subcategory">Subcategoria (Opcional):</label>
                            <select id="product_subcategory" name="product_subcategory_id">
                                <option value="">Selecione uma subcategoria</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="product_main_image">Imagem Principal:</label>
                            <input type="file" id="product_main_image" name="product_main_image" accept="image/*">
                            <small>Deixe em branco para manter a imagem atual.</small>
                            <?php if (!empty($product_to_edit['link_imagem_principal'])): ?>
                                <div class="current-image-preview">
                                    Imagem Atual:<br>
                                    <img src="../<?php echo htmlspecialchars($product_to_edit['link_imagem_principal']); ?>" alt="Imagem de <?php echo htmlspecialchars($product_to_edit['nome']); ?>" class="product-img-thumb">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="product_seo_title">SEO Título (Meta Title):</label>
                            <input type="text" id="product_seo_title" name="product_seo_title" value="<?php echo htmlspecialchars($product_to_edit['seo_titulo'] ?? ''); ?>">
                            <small>Recomendado: 50-60 caracteres.</small>
                        </div>
                        <div class="form-group">
                            <label for="product_seo_description">SEO Descrição (Meta Description):</label>
                            <textarea id="product_seo_description" name="product_seo_description" rows="3"><?php echo htmlspecialchars($product_to_edit['seo_descricao'] ?? ''); ?></textarea>
                            <small>Recomendado: 150-160 caracteres.</small>
                        </div>
                        <div class="form-group">
                            <label for="product_seo_keywords">SEO Palavras-Chave:</label>
                            <input type="text" id="product_seo_keywords" name="product_seo_keywords" value="<?php echo htmlspecialchars($product_to_edit['seo_palavras_chave'] ?? ''); ?>">
                            <small>Recomendado: até 255 caracteres.</small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn-submit"><?php echo $product_to_edit ? 'Atualizar Produto' : 'Adicionar Produto'; ?></button>
                <?php if ($product_to_edit): ?>
                    <a href="produtos.php" class="btn btn-secondary" style="margin-left: 10px;">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="list-section">
            <h3>Produtos Existentes</h3>

            <div class="filter-section">
                <form action="produtos.php" method="GET" class="filter-form">
                    <div class="form-group">
                        <label for="filter_category">Filtrar por Categoria:</label>
                        <select id="filter_category" name="filter_category_id">
                            <option value="">Todas as Categorias</option>
                            <?php foreach ($categorias_para_form as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>"
                                    <?php echo (isset($filter_category_id_list) && $filter_category_id_list == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filter_subcategory">Filtrar por Subcategoria:</label>
                        <select id="filter_subcategory" name="filter_subcategory_id">
                            <option value="">Todas as Subcategorias</option>
                            </select>
                    </div>
                    <button type="submit" class="btn-submit">Filtrar</button>
                    <a href="produtos.php" class="btn btn-secondary">Limpar Filtros</a>
                </form>
            </div>
            <?php if (!empty($produtos_para_listagem)): ?>
                <div class="table-container-responsive"> <table>
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Slug</th> <th>Preço</th>
                                <th>Estoque</th>
                                <th>Categoria</th>
                                <th>Subcategoria</th>
                                <th>SKU</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos_para_listagem as $product): ?>
                                <tr>
                                    <td data-label="Imagem">
                                        <?php if ($product['link_imagem_principal']): ?>
                                            <img src="../<?php echo htmlspecialchars($product['link_imagem_principal']); ?>" alt="Imagem de <?php echo htmlspecialchars($product['nome']); ?>" class="product-img-thumb">
                                        <?php else: ?>
                                            <img src="../img/placeholder-product.png" alt="Sem Imagem" class="product-img-thumb">
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Nome"><?php echo htmlspecialchars($product['nome']); ?></td>
                                    <td data-label="Slug"><?php echo htmlspecialchars($product['slug'] ?? 'N/A'); ?></td> <td data-label="Preço">
                                        <?php echo ($product['preco'] === null) ? 'Sob Consulta' : 'R$ ' . number_format($product['preco'], 2, ',', '.') . ''; ?>
                                    </td>
                                    <td data-label="Estoque">
                                        <?php echo ($product['quantidade_estoque'] === null) ? 'Sob Consulta' : htmlspecialchars($product['quantidade_estoque']) . ' unidades'; ?>
                                    </td>
                                    <td data-label="Categoria"><?php echo htmlspecialchars($product['categoria_nome']); ?></td>
                                    <td data-label="Subcategoria"><?php echo htmlspecialchars($product['subcategoria_nome'] ?? 'N/A'); ?></td>
                                    <td data-label="SKU"><?php echo htmlspecialchars($product['sku']); ?></td>
                                    <td data-label="Ações" class="actions">
                                        <a href="produtos.php?edit_id=<?php echo htmlspecialchars($product['id']); ?>" class="btn edit-btn">Editar</a>
                                        <form action="../php/admin_api.php" method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product['id']); ?>">
                                            <button type="submit" class="btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div> <?php else: ?>
                <p>Nenhum produto cadastrado ainda.</p>
            <?php endif; ?>
        </div>

    </div>
    <script>
        // JavaScript para carregar subcategorias dinamicamente
        document.addEventListener('DOMContentLoaded', function() {
            const productCategorySelect = document.getElementById('product_category'); // Select do formulário de cadastro/edição
            const productSubcategorySelect = document.getElementById('product_subcategory'); // Select do formulário de cadastro/edição

            const filterCategorySelect = document.getElementById('filter_category'); // NOVO: Select do filtro de categoria
            const filterSubcategorySelect = document.getElementById('filter_subcategory'); // NOVO: Select do filtro de subcategoria

            // Elementos para "Sob Consulta" (do formulário de cadastro/edição)
            const priceInput = document.getElementById('product_price');
            const priceConsultCheckbox = document.getElementById('price_consult_checkbox');
            const stockInput = document.getElementById('product_stock');
            const stockConsultCheckbox = document.getElementById('stock_consult_checkbox');

            // Função para alternar estado dos campos de preço e estoque
            function toggleConsultInput(checkbox, input) {
                if (checkbox.checked) {
                    input.value = ''; // Limpa o valor quando "Sob Consulta" é marcado
                    input.setAttribute('disabled', 'disabled');
                    input.removeAttribute('required'); // Remove required se estiver desabilitado
                } else {
                    input.removeAttribute('disabled');
                    // input.setAttribute('required', 'required'); // Opcional: Re-adiciona required se desejar
                }
            }

            // Adiciona listeners aos checkboxes do formulário de cadastro/edição
            if (priceConsultCheckbox && priceInput) {
                priceConsultCheckbox.addEventListener('change', function() {
                    toggleConsultInput(this, priceInput);
                });
                // Garante o estado inicial correto ao carregar a página
                toggleConsultInput(priceConsultCheckbox, priceInput);
            }

            if (stockConsultCheckbox && stockInput) {
                stockConsultCheckbox.addEventListener('change', function() {
                    toggleConsultInput(this, stockInput);
                });
                // Garante o estado inicial correto ao carregar a página
                toggleConsultInput(stockConsultCheckbox, stockInput);
            }


            // Função para carregar subcategorias dinamicamente (reutilizada para formulário e filtro)
            async function loadSubcategories(categoryId, targetSubcategorySelect, selectedSubcategoryId = null) {
                targetSubcategorySelect.innerHTML = '<option value="">Carregando...</option>';
                if (categoryId) {
                    try {
                        const response = await fetch(`../php/admin_api.php?action=get_subcategories_by_category&category_id=${categoryId}`);
                        const data = await response.json();

                        targetSubcategorySelect.innerHTML = '<option value="">Todas as Subcategorias</option>'; // Opção padrão para filtro
                        if (data.length > 0) {
                            data.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.nome;
                                if (selectedSubcategoryId && sub.id == selectedSubcategoryId) {
                                    option.selected = true; // Seleciona a subcategoria se for a que está sendo editada/filtrada
                                }
                                targetSubcategorySelect.appendChild(option);
                            });
                        } else {
                            targetSubcategorySelect.innerHTML = '<option value="">Nenhuma subcategoria para esta categoria</option>';
                        }

                    } catch (error) {
                        console.error('Erro ao carregar subcategorias:', error);
                        targetSubcategorySelect.innerHTML = '<option value="">Erro ao carregar subcategorias</option>';
                    }
                } else {
                    targetSubcategorySelect.innerHTML = '<option value="">Todas as Subcategorias</option>'; // Opção padrão para filtro
                }
            }

            // --- Lógica para o Formulário de Cadastro/Edição ---
            if (productCategorySelect && productSubcategorySelect) {
                // Event listener para mudança de categoria no formulário de cadastro/edição
                productCategorySelect.addEventListener('change', function() {
                    loadSubcategories(this.value, productSubcategorySelect);
                });

                // Carrega subcategorias na primeira carga da página se estiver editando um produto
                const initialCategoryId = productCategorySelect.value;
                const initialSubcategoryId = <?php echo json_encode($product_to_edit['subcategoria_id'] ?? null); ?>;
                if (initialCategoryId) {
                    loadSubcategories(initialCategoryId, productSubcategorySelect, initialSubcategoryId);
                }
            }

            // --- NOVO: Lógica para o Formulário de Filtro ---
            if (filterCategorySelect && filterSubcategorySelect) {
                // Event listener para mudança de categoria no filtro
                filterCategorySelect.addEventListener('change', function() {
                    loadSubcategories(this.value, filterSubcategorySelect);
                });

                // Carrega subcategorias na primeira carga da página se um filtro de categoria já estiver ativo
                const currentFilterCategoryId = <?php echo json_encode($filter_category_id_list); ?>;
                const currentFilterSubcategoryId = <?php echo json_encode($filter_subcategory_id_list); ?>;
                
                if (currentFilterCategoryId) {
                    loadSubcategories(currentFilterCategoryId, filterSubcategorySelect, currentFilterSubcategoryId);
                } else {
                    // Se nenhuma categoria estiver filtrada, garante que o dropdown de subcategoria esteja vazio ou com opção padrão
                    filterSubcategorySelect.innerHTML = '<option value="">Todas as Subcategorias</option>';
                }
            }
        });
    </script>
</body>
</html>
<?php
// Fechar a conexão com o banco de dados no final do script principal
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>