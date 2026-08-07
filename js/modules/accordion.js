/**
 * Módulo para o menu acordeão de categorias e subcategorias.
 */
function initAccordion() {
  // A lógica do acordeão será executada quando esta função for chamada.
  // Ela é chamada pelo main.js após o DOM ser carregado.
  const categoryLinks = document.querySelectorAll('.category-list > li > a.has-subcategories');

  categoryLinks.forEach(link => {
    link.addEventListener('click', function(event) {
      const isCurrentFilter = this.classList.contains('active');
      const targetSubcategoriesId = this.dataset.targetSubcategories;
      const targetSubcategoriesUl = document.querySelector(targetSubcategoriesId);

      if (targetSubcategoriesUl) {
        // Evita o comportamento padrão do link se ele não for um filtro ativo
        // e se tiver subcategorias para expandir/recolher
        if (!isCurrentFilter || targetSubcategoriesUl.classList.contains('expanded')) {
          event.preventDefault(); // Impede a navegação do link
        }

        // Recolhe todas as outras subcategorias
        document.querySelectorAll('.subcategory-list.expanded').forEach(ul => {
          // Se não for a UL clicada, recolhe
          if (ul !== targetSubcategoriesUl) {
            ul.classList.remove('expanded');
            const parentLink = ul.closest('li').querySelector('.has-subcategories');
            if (parentLink) {
              parentLink.classList.remove('expanded');
            }
          }
        });

        // Alterna a classe 'expanded' na subcategoria clicada e no link pai
        targetSubcategoriesUl.classList.toggle('expanded');
        this.classList.toggle('expanded');
      }
    });
  });

  // Expande a categoria pai se uma subcategoria estiver ativa na carga da página
  const activeSubcategory = document.querySelector('.subcategory-list a.active');
  if (activeSubcategory) {
    const parentUl = activeSubcategory.closest('.subcategory-list');
    if (parentUl) {
      parentUl.classList.add('expanded');
      const parentCategoryLink = parentUl.closest('li').querySelector('.category-list > li > a.has-subcategories');
      if (parentCategoryLink) {
        parentCategoryLink.classList.add('expanded');
      }
    }
  }
}

// Exporta a função para que outros módulos (como main.js) possam importá-la.
export { initAccordion };