(() => {
  'use strict';

  const onReady = (fn) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else {
      fn();
    }
  };

  const debounce = (fn, wait = 100) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), wait);
    };
  };

  onReady(() => {
    const header = document.querySelector('.header');
    const burger = document.querySelector('.hamburger');
    const menu = document.querySelector('.nav-mobile');
    const overlay = document.querySelector('.mobile-overlay');

    const updateHeader = () => header?.classList.toggle('scrolled', window.scrollY > 24);
    updateHeader();
    window.addEventListener('scroll', debounce(updateHeader, 40), { passive: true });

    if (burger && menu) {
      const closeMenu = () => {
        menu.classList.remove('active');
        burger.classList.remove('active');
        overlay?.classList.remove('active');
        burger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('menu-open');
        document.body.style.overflow = '';
      };

      const openMenu = () => {
        menu.classList.add('active');
        burger.classList.add('active');
        overlay?.classList.add('active');
        burger.setAttribute('aria-expanded', 'true');
        document.body.classList.add('menu-open');
        document.body.style.overflow = 'hidden';
      };

      burger.addEventListener('click', () => {
        menu.classList.contains('active') ? closeMenu() : openMenu();
      });
      overlay?.addEventListener('click', closeMenu);
      menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) closeMenu();
      });
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeMenu();
      });
      window.addEventListener('resize', debounce(() => {
        if (window.innerWidth >= 992) closeMenu();
      }, 120));
    }

    const heroVideo = document.querySelector('.hero-video');
    if (heroVideo) {
      heroVideo.muted = true;
      heroVideo.playsInline = true;
      const playAttempt = heroVideo.play();
      if (playAttempt && typeof playAttempt.catch === 'function') {
        playAttempt.catch(() => {
          heroVideo.hidden = true;
        });
      }
    }

    const floating = document.querySelector('.floating-buttons');
    const floatingToggle = floating?.querySelector('.main-button');
    if (floating && floatingToggle) {
      floatingToggle.addEventListener('click', () => {
        const isOpen = floating.classList.toggle('open');
        floatingToggle.setAttribute('aria-expanded', String(isOpen));
      });
      document.addEventListener('click', (event) => {
        if (!floating.contains(event.target)) {
          floating.classList.remove('open');
          floatingToggle.setAttribute('aria-expanded', 'false');
        }
      });
    }
  });
})();
