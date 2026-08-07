<?php
// =========================================================
// --- admin/categorias.php ---
// Página para gerenciamento (cadastro e listagem) de categorias e subcategorias.
// =========================================================

session_start(); // Inicia a sessão

// Verifica se o usuário NÃO está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    $_SESSION['login_message'] = "Você precisa fazer login para acessar esta página.";
    header('Location: login.php');
    exit; // Termina a execução do script
}

// Inclui o arquivo de conexão com o banco de dados
require_once '../php/db_connect.php';

// --- Variáveis para Edição ---
$edit_category_id = $_GET['edit_id'] ?? null;
$category_to_edit = null;

// Se um ID de edição foi passado, busca os dados da categoria
if ($edit_category_id) {
    $stmt = $conn->prepare("SELECT id, nome, descricao FROM categorias WHERE id = ?");
    $stmt->bind_param("i", $edit_category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $category_to_edit = $result->fetch_assoc();
    } else {
        $_SESSION['admin_message'] = ['text' => "Categoria não encontrada para edição.", 'type' => 'error'];
        header('Location: categorias.php');
        exit;
    }
    $stmt->close();
}

// Função para buscar categorias e subcategorias
function getCategoriasESubcategorias($conn) {
    $data = ['categorias' => [], 'subcategorias' => []];

    // Busca Categorias
    $sql_categorias = "SELECT id, nome, descricao FROM categorias ORDER BY nome ASC";
    $result_categorias = $conn->query($sql_categorias);
    if ($result_categorias->num_rows > 0) {
        while ($row = $result_categorias->fetch_assoc()) {
            $data['categorias'][] = $row;
        }
    }

    // Busca Subcategorias
    $sql_subcategorias = "SELECT s.id, s.nome, s.descricao, c.nome AS categoria_nome, s.categoria_id
                            FROM subcategorias s
                            JOIN categorias c ON s.categoria_id = c.id
                            ORDER BY c.nome, s.nome ASC";
    $result_subcategorias = $conn->query($sql_subcategorias);
    if ($result_subcategorias->num_rows > 0) {
        while ($row = $result_subcategorias->fetch_assoc()) {
            $data['subcategorias'][] = $row;
        }
    }
    return $data;
}

$categorias_data = getCategoriasESubcategorias($conn);

// Fechar a conexão com o banco de dados após buscar todos os dados necessários
$conn->close();

// Lógica para exibir e limpar mensagens de status da sessão [NOVO]
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
    <title>Gerenciar Categorias | Admin Vedavel</title>
    <link rel="icon" href="../midias/icones/favicon-vedavel.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
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
            <h3><?php echo $category_to_edit ? 'Editar Categoria' : 'Cadastrar Nova Categoria'; ?></h3>
            <form action="../php/admin_api.php" method="POST">
                <input type="hidden" name="action" value="<?php echo $category_to_edit ? 'update_category' : 'add_category'; ?>">
                <?php if ($category_to_edit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($category_to_edit['id']); ?>">
                <?php endif; ?>
                <div class="form-group">
                    <label for="category_name">Nome da Categoria:</label>
                    <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category_to_edit['nome'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="category_description">Descrição da Categoria:</label>
                    <textarea id="category_description" name="category_description"><?php echo htmlspecialchars($category_to_edit['descricao'] ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn-submit"><?php echo $category_to_edit ? 'Atualizar Categoria' : 'Adicionar Categoria'; ?></button>
                <?php if ($category_to_edit): ?>
                    <a href="categorias.php" class="btn btn-secondary" style="margin-left: 10px;">Cancelar Edição</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="form-section">
            <h3>Cadastrar Nova Subcategoria</h3>
            <form action="../php/admin_api.php" method="POST">
                <input type="hidden" name="action" value="add_subcategory">
                <div class="form-group">
                    <label for="parent_category">Categoria Principal:</label>
                    <select id="parent_category" name="parent_category_id" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias_data['categorias'] as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                <?php echo htmlspecialchars($category['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="subcategory_name">Nome da Subcategoria:</label>
                    <input type="text" id="subcategory_name" name="subcategory_name" required>
                </div>
                <div class="form-group">
                    <label for="subcategory_description">Descrição da Subcategoria:</label>
                    <textarea id="subcategory_description" name="subcategory_description"></textarea>
                </div>
                <button type="submit" class="btn-submit">Adicionar Subcategoria</button>
            </form>
        </div>

        <div class="list-section">
            <h3>Categorias Existentes</h3>
            <?php if (!empty($categorias_data['categorias'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias_data['categorias'] as $category): ?>
                            <tr>
                                <td data-label="ID"><?php echo htmlspecialchars($category['id']); ?></td>
                                <td data-label="Nome"><?php echo htmlspecialchars($category['nome']); ?></td>
                                <td data-label="Descrição"><?php echo htmlspecialchars($category['descricao']); ?></td>
                                <td data-label="Ações" class="actions">
                                    <a href="categorias.php?edit_id=<?php echo htmlspecialchars($category['id']); ?>" class="btn edit-btn">Editar</a>
                                    <form action="../php/admin_api.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_category">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">
                                        <button type="submit" class="btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir esta categoria e todas as suas subcategorias e produtos associados?');">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma categoria cadastrada ainda.</p>
            <?php endif; ?>
        </div>

        <div class="list-section">
            <h3>Subcategorias Existentes</h3>
            <?php if (!empty($categorias_data['subcategorias'])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria Principal</th>
                            <th>Descrição</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias_data['subcategorias'] as $subcategory): ?>
                            <tr>
                                <td data-label="ID"><?php echo htmlspecialchars($subcategory['id']); ?></td>
                                <td data-label="Nome"><?php echo htmlspecialchars($subcategory['nome']); ?></td>
                                <td data-label="Categoria Principal"><?php echo htmlspecialchars($subcategory['categoria_nome']); ?></td>
                                <td data-label="Descrição"><?php echo htmlspecialchars($subcategory['descricao']); ?></td>
                                <td data-label="Ações" class="actions">
                                    <button class="btn edit-btn">Editar</button>
                                    <form action="../php/admin_api.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="delete_subcategory">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($subcategory['id']); ?>">
                                        <button type="submit" class="btn delete-btn" onclick="return confirm('Tem certeza que deseja excluir esta subcategoria?');">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma subcategoria cadastrada ainda.</p>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>