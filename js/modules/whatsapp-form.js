/**
 * Módulo para controlar o formulário de chat do WhatsApp.
 * Depende do objeto global 'grecaptcha' para o reCAPTCHA.
 */
function initWhatsAppForm() {
  const iniciarChatBotao = document.getElementById('iniciar-chat');

  if (!iniciarChatBotao) return;

  iniciarChatBotao.addEventListener('click', () => {
    // Acessa os elementos do formulário usando optional chaining
    const nome = document.getElementById('nome')?.value || '';
    const sobrenome = document.getElementById('sobrenome')?.value || '';
    const email = document.getElementById('email')?.value || '';
    const telefone = document.getElementById('telefone')?.value || '';
    const assunto = document.getElementById('assunto')?.value || '';
    
    // Verifica se o grecaptcha está disponível e validado
    if (typeof grecaptcha === 'undefined' || !grecaptcha.getResponse()) {
      alert('Por favor, complete o reCAPTCHA para continuar.');
      return;
    }

    const mensagem = `Olá! Meu nome é ${nome} ${sobrenome}. Meu e-mail é ${email} e meu telefone é ${telefone}. Assunto: ${assunto}`;
    const numeroWhatsApp = '557191150648'; // Mantenha o número aqui ou carregue de uma config
    const urlWhatsApp = `https://wa.me/${numeroWhatsApp}?text=${encodeURIComponent(mensagem)}`;

    window.open(urlWhatsApp, '_blank');
  });
}

// Exporta a função para que ela possa ser importada em outro arquivo
export { initWhatsAppForm };