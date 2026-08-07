/**
 * Módulo para o carrossel de produtos com botões de navegação.
 */
function initProductCarousel() {
  const produtosCarousel = document.querySelector('.produtos-carousel');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');

  if (!produtosCarousel || !prevBtn || !nextBtn) return;

  const cardWidth = 290; // Largura do card + margem

  prevBtn.addEventListener('click', () => {
    produtosCarousel.scrollBy({ left: -cardWidth, behavior: 'smooth' });
  });

  nextBtn.addEventListener('click', () => {
    produtosCarousel.scrollBy({ left: cardWidth, behavior: 'smooth' });
  });
}

export { initProductCarousel };