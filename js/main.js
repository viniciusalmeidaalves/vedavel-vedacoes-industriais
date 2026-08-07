// js/main.js

// Importa as funções de inicialização de cada módulo
import { initWhatsAppForm } from './modules/whatsapp-form.js';
import { initBackToTop } from './modules/back-to-top.js';
import { initProductCarousel } from './modules/product-carousel.js';
import { initTestimonialSlider } from './modules/testimonial-slider.js';
import { initScrollAnimations } from './modules/scroll-animations.js';
import { initStickyMenu } from './modules/sticky-menu.js';
import { initSmoothScroll } from './modules/smooth-scroll.js';
import { initFooterYear } from './modules/footer-year.js';
import { initMobileMenu } from './modules/mobile-menu.js';
import { initAccordion } from './modules/accordion.js';
import { initCookieConsent } from './modules/cookie-consent.js';
import { initAccessibilityWidget } from './modules/accessibility-widget.js'; // NOVO: Importa o módulo de acessibilidade

// Nota: O arquivo 'product-data.js' não é chamado aqui porque ele apenas
// exporta dados. Ele será importado por outros módulos que precisarem
// renderizar os produtos na tela.

// Aguarda o conteúdo da página ser totalmente carregado para executar os scripts
document.addEventListener('DOMContentLoaded', () => {
  // Executa cada função importada
  initWhatsAppForm();
  initBackToTop();
  initProductCarousel();
  initTestimonialSlider();
  initScrollAnimations();
  initStickyMenu();
  initSmoothScroll();
  initFooterYear();
  initMobileMenu();
  initAccordion();
  initCookieConsent();
  initAccessibilityWidget(); // NOVO: Chama a função de inicialização do widget de acessibilidade
});