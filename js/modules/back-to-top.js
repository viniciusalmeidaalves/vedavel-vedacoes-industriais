/**
 * Módulo para o botão "Voltar ao Topo".
 */
function initBackToTop() {
  const backToTopButton = document.querySelector('.back-to-top-button');

  if (!backToTopButton) return;

  const toggleVisibility = () => {
    backToTopButton.classList.toggle('show', window.scrollY > 300);
  };

  const scrollToTop = (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  window.addEventListener('scroll', toggleVisibility);
  backToTopButton.addEventListener('click', scrollToTop);
}

export { initBackToTop };