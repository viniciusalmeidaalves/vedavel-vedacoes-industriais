<?php
// =========================================================
// --- php/admin_api.php ---
// Script de API para processar operações de CRUD (Criar, Ler, Atualizar, Excluir)
// para Categorias, Subcategorias e Produtos.
// =========================================================

session_start(); // Inicia a sessão PHP para usar $_SESSION para mensagens

require_once 'db_connect.php'; // Inclui a conexão com o banco de dados

// Função auxiliar para processar upload de imagem
function uploadImage($file_input_name, $upload_dir = '../uploads/products/') {
    // Adiciona log: Verifica se o arquivo foi enviado e se houve algum erro inicial de upload
    if (!isset($_FILES[$file_input_name])) {
        error_log("UPLOAD_DEBUG: Nao foi enviado nenhum arquivo para o input '{$file_input_name}'.");
        return null;
    }
    
    // UPLOAD_ERR_OK = 0, nenhum erro
    if ($_FILES[$file_input_name]['error'] !== UPLOAD_ERR_OK) {
        $php_upload_errors = array(
            UPLOAD_ERR_INI_SIZE   => 'O arquivo excede o limite de upload_max_filesize em php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'O arquivo excede o limite MAX_FILE_SIZE especificado no formulário HTML.',
            UPLOAD_ERR_PARTIAL    => 'O upload do arquivo foi feito apenas parcialmente.',
            UPLOAD_ERR_NO_FILE    => 'Nenhum arquivo foi enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Faltando uma pasta temporária para upload.',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo em disco.',
            UPLOAD_ERR_EXTENSION  => 'Uma extensão PHP interrompeu o upload do arquivo.'
        );
        $error_message = $php_upload_errors[$_FILES[$file_input_name]['error']] ?? 'Erro de upload desconhecido.';
        error_log("UPLOAD_DEBUG: Erro no upload de '{$file_input_name}': " . $error_message . " (Codigo: " . $_FILES[$file_input_name]['error'] . ")");
        return null;
    }

    $file_tmp_name = $_FILES[$file_input_name]['tmp_name'];
    $file_name = basename($_FILES[$file_input_name]['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    // CORREÇÃO: Adicionada 'webp' à lista de extensões permitidas
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp']; 

    // Adiciona log: Verifica a extensão do arquivo
    if (!in_array($file_ext, $allowed_exts)) {
        error_log("UPLOAD_DEBUG: Extensao de arquivo nao permitida para '{$file_name}'. Extensao: '{$file_ext}'. Permitidas: " . implode(', ', $allowed_exts));
        return null;
    }

    // Adiciona log: Tenta criar o diretório de upload se não existir
    if (!is_dir($upload_dir)) {
        error_log("UPLOAD_DEBUG: Diretorio '{$upload_dir}' nao existe. Tentando criar...");
        if (!mkdir($upload_dir, 0777, true)) {
            error_log("UPLOAD_DEBUG: FALHA ao criar diretorio '{$upload_dir}'. Verifique permissoes da pasta pai.");
            return null;
        }
        error_log("UPLOAD_DEBUG: Diretorio '{$upload_dir}' criado com sucesso.");
    }

    $new_file_name = uniqid('prod_') . '.' . $file_ext;
    $upload_path = $upload_dir . $new_file_name;

    // Adiciona log: Tenta mover o arquivo
    if (move_uploaded_file($file_tmp_name, $upload_path)) {
        error_log("UPLOAD_DEBUG: Arquivo '{$file_name}' movido com sucesso para '{$upload_path}'.");
        return str_replace('../', '', $upload_path); // Retorna o caminho relativo para o DB
    } else {
        // Adiciona log: Falha ao mover o arquivo
        error_log("UPLOAD_DEBUG: FALHA ao mover o arquivo de upload. Origem: '{$file_tmp_name}', Destino: '{$upload_path}'. Verifique permissoes de escrita no diretorio e espaco em disco.");
        return null;
    }
}

// Variáveis para armazenar a mensagem de status e seu tipo
$message_text = '';
$message_type = ''; // 'success' ou 'error'

// Lógica de processamento de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        // --- Ações para Categorias (sem alterações, mas mantido para contexto) ---
        case 'add_category':
            $name = trim($_POST['category_name'] ?? '');
            $description = trim($_POST['category_description'] ?? '');

            if (!empty($name)) {
                $stmt = $conn->prepare("INSERT INTO categorias (nome, descricao) VALUES (?, ?)");
                $stmt->bind_param("ss", $name, $description);

                if ($stmt->execute()) {
                    $message_text = "Categoria '$name' adicionada com sucesso!";
                    $message_type = 'success';
                } else {
                    if ($conn->errno == 1062) {
                        $message_text = "Erro: Categoria '$name' já existe.";
                        $message_type = 'error';
                    } else {
                        $message_text = "Erro ao adicionar categoria: " . $stmt->error;
                        $message_type = 'error';
                    }
                }
                $stmt->close();
            } else {
                $message_text = "Erro: Nome da categoria não pode ser vazio.";
                $message_type = 'error';
            }
            break;

        case 'update_category':
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['category_name'] ?? '');
            $description = trim($_POST['category_description'] ?? '');

            if ($id > 0 && !empty($name)) {
                $stmt = $conn->prepare("UPDATE categorias SET nome = ?, descricao = ? WHERE id = ?");
                $stmt->bind_param("ssi", $name, $description, $id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message_text = "Categoria '$name' atualizada com sucesso!";
                        $message_type = 'success';
                    } else {
                        $message_text = "Nenhuma alteração feita na categoria '$name' (ou já estava atualizada).";
                        $message_type = 'info';
                    }
                } else {
                    if ($conn->errno == 1062) {
                        $message_text = "Erro: Categoria '$name' já existe.";
                        $message_type = 'error';
                    } else {
                        $message_text = "Erro ao atualizar categoria: " . $stmt->error;
                        $message_type = 'error';
                    }
                }
                $stmt->close();
            } else {
                $message_text = "Erro: ID da categoria ou nome inválido para atualização.";
                $message_type = 'error';
            }
            break;

        case 'delete_category':
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message_text = "Categoria e seus itens associados excluídos com sucesso!";
                        $message_type = 'success';
                    } else {
                        $message_text = "Nenhuma categoria encontrada com o ID: $id.";
                        $message_type = 'error';
                    }
                } else {
                    $message_text = "Erro ao excluir categoria: " . $stmt->error;
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message_text = "Erro: ID da categoria inválido para exclusão.";
                $message_type = 'error';
            }
            break;

        // --- Ações para Subcategorias (sem alterações, mas mantido para contexto) ---
        case 'add_subcategory':
            $category_id = (int)($_POST['parent_category_id'] ?? 0);
            $name = trim($_POST['subcategory_name'] ?? '');
            $description = trim($_POST['subcategory_description'] ?? '');

            if ($category_id > 0 && !empty($name)) {
                $stmt = $conn->prepare("INSERT INTO subcategorias (categoria_id, nome, descricao) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $category_id, $name, $description);

                if ($stmt->execute()) {
                    $message_text = "Subcategoria '$name' adicionada com sucesso!";
                    $message_type = 'success';
                } else {
                    if ($conn->errno == 1062) {
                        $message_text = "Erro: Subcategoria '$name' já existe para esta categoria.";
                        $message_type = 'error';
                    } else {
                        $message_text = "Erro ao adicionar subcategoria: " . $stmt->error;
                        $message_type = 'error';
                    }
                }
                $stmt->close();
            } else {
                $message_text = "Erro: Categoria principal ou nome da subcategoria inválidos.";
                $message_type = 'error';
            }
            break;

        case 'delete_subcategory':
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $stmt = $conn->prepare("DELETE FROM subcategorias WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message_text = "Subcategoria excluída com sucesso!";
                        $message_type = 'success';
                    } else {
                        $message_text = "Nenhuma subcategoria encontrada com o ID: $id.";
                        $message_type = 'error';
                    }
                } else {
                    $message_text = "Erro ao excluir subcategoria: " . $stmt->error;
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message_text = "Erro: ID da subcategoria inválido para exclusão.";
                $message_type = 'error';
            }
            break;

        // --- Ações para Produtos ---
        case 'add_product':
            $name = trim($_POST['product_name'] ?? '');
            $slug = trim($_POST['product_slug'] ?? '');
            $short_description = trim($_POST['product_short_description'] ?? '');
            $full_description = trim($_POST['product_full_description'] ?? '');

            $price_consult = isset($_POST['price_consult']) && $_POST['price_consult'] == '1';
            $price = $price_consult ? null : (empty($_POST['product_price']) ? null : floatval(str_replace(',', '.', $_POST['product_price'])));

            $measures = trim($_POST['product_measures'] ?? '');

            $stock_consult = isset($_POST['stock_consult']) && $_POST['stock_consult'] == '1';
            $stock = $stock_consult ? null : (empty($_POST['product_stock']) ? null : (int)($_POST['product_stock']));

            $weight = empty($_POST['product_weight']) ? null : floatval(str_replace(',', '.', $_POST['product_weight']));
            $unit_weight = 'KG';
            $sku = trim($_POST['product_sku'] ?? '');
            $gtin = trim($_POST['product_gtin'] ?? '');
            $brand = trim($_POST['product_brand'] ?? '');
            $mpn = trim($_POST['product_mpn'] ?? '');
            $condition = 'novo';
            $seo_title = trim($_POST['product_seo_title'] ?? '');
            $seo_description = trim($_POST['product_seo_description'] ?? '');
            $seo_keywords = trim($_POST['product_seo_keywords'] ?? '');
            $category_id = (int)($_POST['product_category_id'] ?? 0);
            $subcategory_id = (int)($_POST['product_subcategory_id'] ?? 0);
            if ($subcategory_id === 0) $subcategory_id = NULL;

            $image_url = uploadImage('product_main_image'); // Pode ser NULL se não houver upload

            // Condição de validação de campos obrigatórios
            if (!empty($name) && $category_id > 0) {
                $stmt = $conn->prepare("INSERT INTO produtos (
                    nome, slug, descricao_curta, descricao_completa, preco, medidas, peso, unidade_peso,
                    quantidade_estoque, sku, gtin, marca, mpn, condicao, link_imagem_principal,
                    seo_titulo, seo_descricao, seo_palavras_chave, categoria_id, subcategoria_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                // A string de tipos correta: 15 's', 2 'd', 3 'i'
                // Ordem: nome, slug, desc_curta, desc_completa, preco, medidas, peso, unidade_peso,
                //         quantidade_estoque, sku, gtin, marca, mpn, condicao, link_imagem_principal,
                //         seo_titulo, seo_descricao, seo_palavras_chave, categoria_id, subcategoria_id
                $stmt->bind_param("sssdssidsssssssssii",
                    $name, $slug, $short_description, $full_description, $price, $measures, $weight, $unit_weight,
                    $stock, $sku, $gtin, $brand, $mpn, $condition, $image_url, 
                    $seo_title, $seo_description, $seo_keywords, $category_id, $subcategory_id
                );

                if ($stmt->execute()) {
                    $message_text = "Produto '$name' adicionado com sucesso!";
                    $message_type = 'success';
                } else {
                    if ($conn->errno == 1062) { // Erro 1062 é para violação de chave única (UNIQUE constraint)
                        $message_text = "Erro: O slug ou SKU já existe para outro produto. Por favor, escolha um slug único.";
                        $message_type = 'error';
                    } else {
                        // Loga o erro do statement para depuração
                        error_log("SQL ERROR in add_product: " . $stmt->error);
                        $message_text = "Erro ao adicionar produto: " . $stmt->error;
                        $message_type = 'error';
                    }
                }
                $stmt->close();
            } else {
                $message_text = "Erro: Campos obrigatórios do produto inválidos (Nome, Categoria).";
                $message_type = 'error';
            }
            break;


        case 'update_product':
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['product_name'] ?? '');
            $slug = trim($_POST['product_slug'] ?? '');
            $short_description = trim($_POST['product_short_description'] ?? '');
            $full_description = trim($_POST['product_full_description'] ?? '');

            $price_consult = isset($_POST['price_consult']) && $_POST['price_consult'] == '1';
            $price = $price_consult ? null : (empty($_POST['product_price']) ? null : floatval(str_replace(',', '.', $_POST['product_price'])));

            $measures = trim($_POST['product_measures'] ?? '');

            $stock_consult = isset($_POST['stock_consult']) && $_POST['stock_consult'] == '1';
            $stock = $stock_consult ? null : (empty($_POST['product_stock']) ? null : (int)($_POST['product_stock']));

            $weight = empty($_POST['product_weight']) ? null : floatval(str_replace(',', '.', $_POST['product_weight']));
            $unit_weight = 'KG';
            $sku = trim($_POST['product_sku'] ?? '');
            $gtin = trim($_POST['product_gtin'] ?? '');
            $brand = trim($_POST['product_brand'] ?? '');
            $mpn = trim($_POST['product_mpn'] ?? '');
            $condition = 'novo';
            $seo_title = trim($_POST['product_seo_title'] ?? '');
            $seo_description = trim($_POST['product_seo_description'] ?? '');
            $seo_keywords = trim($_POST['product_seo_keywords'] ?? '');
            $category_id = (int)($_POST['product_category_id'] ?? 0);
            $subcategory_id = (int)($_POST['product_subcategory_id'] ?? 0);
            if ($subcategory_id === 0) $subcategory_id = NULL;

            // Pega o caminho da imagem atual (se houver) enviado pelo formulário.
            // Se for uma string vazia (ex: de um input hidden para um campo NULL), converte para NULL.
            $current_image_path_from_post = trim($_POST['current_image_path'] ?? '');
            $image_url = ($current_image_path_from_post === '') ? null : $current_image_path_from_post;

            // Verifica se um NOVO arquivo de imagem foi enviado
            if (isset($_FILES['product_main_image']) && $_FILES['product_main_image']['error'] === UPLOAD_ERR_OK) {
                $new_image_url = uploadImage('product_main_image'); // Tenta fazer upload da nova imagem
                if ($new_image_url) { // Se o upload for bem-sucedido
                    $image_url = $new_image_url; // Atualiza $image_url com o novo caminho
                    // Se houver uma imagem antiga e ela existir no servidor, a exclui
                    if ($current_image_path_from_post && file_exists('../' . $current_image_path_from_post)) {
                        unlink('../' . $current_image_path_from_post); // Exclui a imagem antiga
                    }
                } else {
                    // Se o upload da NOVA imagem falhou, $image_url mantém o valor que tinha (o antigo caminho ou NULL).
                    // Não é necessário fazer mais nada aqui.
                }
            }
            // Se nenhum novo arquivo de imagem foi enviado, $image_url mantém o valor inicial
            // (o caminho da imagem atual ou NULL).

            // Condição de validação de campos obrigatórios
            if ($id > 0 && !empty($name) && $category_id > 0) {
                $stmt = $conn->prepare("UPDATE produtos SET
                    nome = ?, slug = ?, descricao_curta = ?, descricao_completa = ?, preco = ?, medidas = ?, peso = ?, unidade_peso = ?,
                    quantidade_estoque = ?, sku = ?, gtin = ?, marca = ?, mpn = ?, condicao = ?, link_imagem_principal = ?,
                    seo_titulo = ?, seo_descricao = ?, seo_palavras_chave = ?, categoria_id = ?, subcategoria_id = ?
                    WHERE id = ?");
                
                // A string de tipos correta: 15 's', 2 'd', 4 'i' (incluindo o ID do WHERE)
                // Ordem: nome, slug, desc_curta, desc_completa, preco, medidas, peso, unidade_peso,
                //         quantidade_estoque, sku, gtin, marca, mpn, condicao, link_imagem_principal,
                //         seo_titulo, seo_descricao, seo_palavras_chave, categoria_id, subcategoria_id, id
                $stmt->bind_param("sssdssidsssssssssiiii",
                    $name, $slug, $short_description, $full_description, $price, $measures, $weight, $unit_weight,
                    $stock, $sku, $gtin, $brand, $mpn, $condition, $image_url, 
                    $seo_title, $seo_description, $seo_keywords, $category_id, $subcategory_id, $id
                );

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message_text = "Produto '$name' atualizado com sucesso!";
                        $message_type = 'success';
                    } else {
                        $message_text = "Nenhuma alteração feita no produto '$name' (ou já estava atualizada).";
                        $message_type = 'info';
                    }
                } else {
                    // Loga o erro do statement para depuração
                    error_log("SQL ERROR in update_product: " . $stmt->error);
                    $message_text = "Erro ao atualizar produto: " . $stmt->error;
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message_text = "Erro: Campos obrigatórios do produto inválidos para atualização (Nome, Categoria, ID).";
                $message_type = 'error';
            }
            break;


        case 'delete_product':
            $id = (int)($_POST['id'] ?? 0);

            if ($id > 0) {
                $stmt_select_img = $conn->prepare("SELECT link_imagem_principal FROM produtos WHERE id = ?");
                $stmt_select_img->bind_param("i", $id);
                $stmt_select_img->execute();
                $result_img = $stmt_select_img->get_result();
                if ($row_img = $result_img->fetch_assoc()) {
                    $old_image_path = $row_img['link_imagem_principal'];
                    $full_image_path = '../' . $old_image_path;
                    if ($old_image_path && file_exists($full_image_path)) {
                        unlink($full_image_path);
                    }
                }
                $stmt_select_img->close();

                $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        $message_text = "Produto excluído com sucesso!";
                        $message_type = 'success';
                    } else {
                        $message_text = "Nenhum produto encontrado com o ID: $id.";
                        $message_type = 'error';
                    }
                } else {
                    $message_text = "Erro ao excluir produto: " . $stmt->error;
                    $message_type = 'error';
                }
                $stmt->close();
            } else {
                $message_text = "Erro: ID do produto inválido para exclusão.";
                $message_type = 'error';
            }
            break;


        default:
            $message_text = "Ação desconhecida.";
            $message_type = 'error';
            break;
    }
    // Armazena a mensagem e seu tipo na sessão
    $_SESSION['admin_message'] = ['text' => $message_text, 'type' => $message_type];

    // Redirecionamento após POST
    $redirect_url = "../admin/index.php";
    if (isset($_SERVER['HTTP_REFERER'])) {
        if (strpos($_SERVER['HTTP_REFERER'], 'categorias.php') !== false) {
            $redirect_url = "../admin/categorias.php";
        } elseif (strpos($_SERVER['HTTP_REFERER'], 'produtos.php') !== false) {
            $redirect_url = "../admin/produtos.php";
        }
    }
    header("Location: " . $redirect_url);
    exit();

}
// Lógica para requisições GET (como a busca de subcategorias via JS)
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'get_subcategories_by_category':
            header('Content-Type: application/json');
            $category_id = (int)($_GET['category_id'] ?? 0);
            $subcategories = [];

            if ($category_id > 0) {
                $stmt = $conn->prepare("SELECT id, nome FROM subcategorias WHERE categoria_id = ? ORDER BY nome ASC");
                $stmt->bind_param("i", $category_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $subcategories[] = $row;
                }
                $stmt->close();
            }
            echo json_encode($subcategories);
            exit();
            break;
        default:
            // Não faz nada ou redireciona para um erro genérico se for um GET inválido e não AJAX
            // echo "Ação GET desconhecida ou inválida.";
            break;
    }
}

// Fechar a conexão com o banco de dados
// A conexão será fechada ao final da execução do script principal
?>