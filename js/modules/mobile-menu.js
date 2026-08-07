/**
 * Módulo para gerenciar toda a interatividade do menu em modo mobile.
 * Inclui o toggle de categorias e o fechamento ao clicar fora.
 */
function initMobileMenu() {
  // Toggle para subcategorias dentro do menu principal (se houver)
  document.querySelectorAll('.produtos-toggle').forEach(btn => {
    btn.addEventListener('click', function(e) {
      // Atua apenas em telas menores
      if (window.innerWidth < 900) {
        e.preventDefault();
        this.parentElement.classList.toggle('open'); //
      }
    });
  });

  // Toggle para o dropdown principal de "Produtos"
  const dropdown = document.querySelector('.dropdown-produtos');
  if (dropdown) {
    const toggle = dropdown.querySelector('a'); // O link principal
    
    toggle.addEventListener('click', function(e) {
      if (window.innerWidth <= 900) {
        e.preventDefault();
        dropdown.classList.toggle('open'); //
      }
    });

    // Lógica para fechar o menu ao clicar fora dele
    document.addEventListener('click', function(e) {
      if (window.innerWidth <= 900) {
        // Verifica se o clique foi fora do elemento .dropdown-produtos
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove('open'); //
        }
      }
    });
  }
}

export { initMobileMenu };