/**
 * Módulo para atualizar dinamicamente o ano no rodapé.
 */
function initFooterYear() {
  const anoAtualElemento = document.getElementById('ano-atual');
  if (anoAtualElemento) {
    anoAtualElemento.textContent = new Date().getFullYear(); //
  }
}

export { initFooterYear };