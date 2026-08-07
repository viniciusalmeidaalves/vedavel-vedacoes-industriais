/**
 * Módulo para aplicar animações em elementos quando eles aparecem na tela ao rolar.
 */
function initScrollAnimations() {
  const elementsToAnimate = document.querySelectorAll('.sobre-content, .produto-card, .depoimento-card, .stat, .setor, .nivel, .compromisso-item, .atendimento-item');

  if (!elementsToAnimate.length) return;

  const animate = () => {
    elementsToAnimate.forEach(element => {
      const position = element.getBoundingClientRect().top;
      // Anima se o topo do elemento estiver a menos de 100px do final da viewport
      if (position < window.innerHeight - 100) {
        element.classList.add('animate');
      }
    });
  };

  // Aplica a classe 'scrolled' ao body para outros efeitos de UI
  const bodyScrollEffect = () => {
    document.body.classList.toggle('scrolled', window.scrollY > 300);
  }

  window.addEventListener('scroll', animate);
  window.addEventListener('scroll', bodyScrollEffect);
  
  // Executa uma vez no carregamento para animar elementos já visíveis
  animate();
}

export { initScrollAnimations };