/**
 * Módulo para o menu fixo que aparece/desaparece ao rolar a página.
 */
function initStickyMenu() {
  const menuTopo = document.querySelector('.menu-topo');
  if (!menuTopo) return;

  let lastScrollTop = 0;

  window.addEventListener('scroll', () => {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Adiciona classe 'sticky' após rolar 100px
    if (scrollTop > 100) {
      menuTopo.classList.add('sticky');
      // Esconde o menu se estiver rolando para baixo, mostra se estiver rolando para cima
      menuTopo.classList.toggle('hidden', scrollTop > lastScrollTop);
    } else {
      menuTopo.classList.remove('sticky', 'hidden');
    }

    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Evita valor negativo no iOS
  });
}

export { initStickyMenu };