/**
 * Módulo para o slider de depoimentos controlado por "dots".
 */
function initTestimonialSlider() {
  const depoimentosSlider = document.querySelector('.depoimentos-slider');
  const dots = document.querySelectorAll('.depoimentos-dots .dot');

  if (!depoimentosSlider || !dots.length) return;

  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => {
      // Remove a classe ativa do dot anterior e adiciona no atual
      document.querySelector('.dot.active')?.classList.remove('active');
      dot.classList.add('active');

      // Calcula o deslocamento e move o slider
      const card = depoimentosSlider.querySelector('.depoimento-card');
      if (!card) return;

      const cardWidth = card.offsetWidth + 30; // Largura do card + gap
      depoimentosSlider.scrollTo({ left: cardWidth * index, behavior: 'smooth' });
    });
  });
}

export { initTestimonialSlider };