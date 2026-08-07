// =========================================================
// --- Módulo: accessibility-widget.js ---
// Lógica para o widget de acessibilidade (tamanho da fonte, contraste).
// =========================================================

function initAccessibilityWidget() {
  const triggerBtn = document.getElementById('accessibility-widget-trigger');
  const panel = document.getElementById('accessibility-panel');
  const closeBtn = document.querySelector('.accessibility-close-btn');

  const fontSizeDecreaseBtn = document.getElementById('font-size-decrease');
  const fontSizeIncreaseBtn = document.getElementById('font-size-increase');
  const fontSizeResetBtn = document.getElementById('font-size-reset');
  const toggleHighContrastBtn = document.getElementById('toggle-high-contrast');

  const body = document.body;
  let currentFontSize = localStorage.getItem('accessibilityFontSize') || 'normal'; // 'normal', 'large', 'xlarge'
  let highContrastActive = localStorage.getItem('accessibilityHighContrast') === 'true';

  // --- Funções Auxiliares ---

  // Aplica as classes de fonte salvas no localStorage
  function applyFontSize() {
    body.classList.remove('font-size-small', 'font-size-large', 'font-size-xlarge');
    if (currentFontSize !== 'normal') {
      body.classList.add(`font-size-${currentFontSize}`);
    }
  }

  // Aplica a classe de alto contraste salva no localStorage
  function applyHighContrast() {
    if (highContrastActive) {
      body.classList.add('high-contrast');
    } else {
      body.classList.remove('high-contrast');
    }
  }

  // --- Inicialização ao Carregar a Página ---
  applyFontSize();
  applyHighContrast();

  // --- Event Listeners ---

  // Abrir/Fechar Painel
  if (triggerBtn && panel) {
    triggerBtn.addEventListener('click', () => {
      panel.classList.toggle('is-open');
    });
  }

  if (closeBtn && panel) {
    closeBtn.addEventListener('click', () => {
      panel.classList.remove('is-open');
    });
  }

  // Fechar painel ao clicar fora (opcional, mas bom para UX)
  document.addEventListener('click', (event) => {
    if (panel && triggerBtn && !panel.contains(event.target) && !triggerBtn.contains(event.target)) {
      panel.classList.remove('is-open');
    }
  });


  // Ajuste de Tamanho da Fonte
  if (fontSizeDecreaseBtn) {
    fontSizeDecreaseBtn.addEventListener('click', () => {
      if (currentFontSize === 'xlarge') {
        currentFontSize = 'large';
      } else if (currentFontSize === 'large') {
        currentFontSize = 'normal';
      } else { // currentFontSize === 'normal' ou 'small' (se fosse implementado)
        currentFontSize = 'small'; // Ou um valor base, dependendo da sua escala
      }
      localStorage.setItem('accessibilityFontSize', currentFontSize);
      applyFontSize();
    });
  }

  if (fontSizeIncreaseBtn) {
    fontSizeIncreaseBtn.addEventListener('click', () => {
      if (currentFontSize === 'normal') {
        currentFontSize = 'large';
      } else if (currentFontSize === 'large') {
        currentFontSize = 'xlarge';
      } else { // currentFontSize === 'xlarge'
        // currentFontSize = 'xxlarge'; // Se quiser mais um nível
      }
      localStorage.setItem('accessibilityFontSize', currentFontSize);
      applyFontSize();
    });
  }

  if (fontSizeResetBtn) {
    fontSizeResetBtn.addEventListener('click', () => {
      currentFontSize = 'normal';
      localStorage.setItem('accessibilityFontSize', currentFontSize);
      applyFontSize();
    });
  }

  // Alternar Alto Contraste
  if (toggleHighContrastBtn) {
    toggleHighContrastBtn.addEventListener('click', () => {
      highContrastActive = !highContrastActive;
      localStorage.setItem('accessibilityHighContrast', highContrastActive);
      applyHighContrast();
    });
  }
}

export { initAccessibilityWidget };