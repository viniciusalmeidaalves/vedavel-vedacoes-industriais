<?php
// =========================================================
// --- includes/header.php ---
// Cabeçalho da área pública do site, incluindo menu de navegação dinâmico.
// =========================================================

// Inclui a conexão com o banco de dados.
// Certifique-se de que o caminho para db_connect.php está correto a partir deste arquivo.
require_once __DIR__ . '/../php/db_connect.php';

$categorias_menu = [];

// Consulta para buscar categorias e suas subcategorias
// Usaremos LEFT JOIN para garantir que todas as categorias apareçam, mesmo sem subcategorias
$sql_menu = "SELECT c.id AS categoria_id, c.nome AS categoria_nome,
                    s.id AS subcategoria_id, s.nome AS subcategoria_nome
             FROM categorias c
             LEFT JOIN subcategorias s ON c.id = s.categoria_id
             ORDER BY c.nome ASC, s.nome ASC";

$result_menu = $conn->query($sql_menu);

if ($result_menu) {
    while ($row = $result_menu->fetch_assoc()) {
        $categoria_id = $row['categoria_id'];
        $categoria_nome = htmlspecialchars($row['categoria_nome']);

        // Se a categoria ainda não existe no array, adicione-a
        if (!isset($categorias_menu[$categoria_id])) {
            $categorias_menu[$categoria_id] = [
                'id' => $categoria_id, // Adicionado o ID da categoria aqui
                'nome' => $categoria_nome,
                'subcategorias' => []
            ];
        }

        // Se houver uma subcategoria para a linha atual, adicione-a
        if ($row['subcategoria_id'] !== null) {
            $categorias_menu[$categoria_id]['subcategorias'][] = [
                'id' => $row['subcategoria_id'],
                'nome' => htmlspecialchars($row['subcategoria_nome'])
            ];
        }
    }
} else {
    // Em caso de erro na consulta, você pode logar ou tratar de alguma forma
    error_log("Erro ao buscar dados para o menu: " . $conn->error);
}

// REMOVIDO: A linha $conn->close(); NÃO deve estar aqui.
?>

<header class="menu-topo">
  <a href="index.php">
    <div class="logo">
      <img src="./midias/imagens/logo/logo-vedavel-menu-e-footer-blue.png" alt="Logo da Vedavel" class="logo-img">
    </div>
  </a>
  <nav class="menu-principal">
    <ul>
      <li><a href="index.php">VEDAVEL</a></li>
      <li class="dropdown-produtos">
        <a href="produtos.php">PRODUTOS <span class="arrow">▾</span></a>
        <div class="dropdown-produtos-menu">
          <?php if (!empty($categorias_menu)): ?>
            <?php foreach ($categorias_menu as $cat): ?>
              <div class="produtos-coluna">
                <span class="produtos-titulo"><?php echo $cat['nome']; ?></span>
                <?php if (!empty($cat['subcategorias'])): ?>
                  <?php foreach ($cat['subcategorias'] as $sub): ?>
                    <a href="produtos.php?subcategoria_id=<?php echo $sub['id']; ?>"><?php echo $sub['nome']; ?></a>
                  <?php endforeach; ?>
                <?php else: ?>
                  <a href="produtos.php?categoria_id=<?php echo $cat['id']; ?>">Todos de <?php echo $cat['nome']; ?></a>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="produtos-coluna">
              <span class="produtos-titulo">Nenhuma Categoria Encontrada</span>
              <a href="produtos.php">Ver Todos os Produtos</a>
            </div>
          <?php endif; ?>
        </div>
      </li>
      <li><a href="catalogo.php">CATALOGO</a></li>
      <li><a href="contato.php">CONTATO</a></li>
      
      <?php 
      // NOVO: Adiciona o ícone de acesso ao painel admin SOMENTE na página index.php
      if (basename($_SERVER['PHP_SELF']) == 'index.php') {
      ?>
      <li class="admin-login-icon">
        <a href="admin/login.php" title="Acessar Portal Administrativo">
          <i class="fas fa-user-lock"></i>
        </a>
      </li>
      <?php
      }
      ?>
    </ul>
  </nav>
</header>