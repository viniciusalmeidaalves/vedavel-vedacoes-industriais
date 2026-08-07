// =========================================================
// --- Módulo: cookie-consent.js ---
// Lógica para exibir e gerenciar o banner de consentimento de cookies.
// =========================================================

function initCookieConsent() {
  const cookieBanner = document.getElementById('cookie-banner');
  const acceptCookiesBtn = document.getElementById('accept-cookies-btn');

  // 1. Verifica se o usuário já aceitou os cookies
  if (localStorage.getItem('cookieConsent') === 'accepted') {
    if (cookieBanner) {
      cookieBanner.style.display = 'none'; // Se já aceitou, esconde o banner
    }
  } else {
    // Se não aceitou, mostra o banner (garantindo que esteja visível)
    if (cookieBanner) {
      cookieBanner.style.display = 'flex'; // Usamos 'flex' para o layout do banner
    }
  }

  // 2. Adiciona o event listener ao botão de aceitar
  if (acceptCookiesBtn) {
    acceptCookiesBtn.addEventListener('click', () => {
      localStorage.setItem('cookieConsent', 'accepted'); // Salva a preferência
      if (cookieBanner) {
        cookieBanner.style.display = 'none'; // Esconde o banner após aceitar
      }
    });
  }
}

export { initCookieConsent };