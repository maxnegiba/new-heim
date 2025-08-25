/* =========================================================
MAIN.JS – MeisterDach Website Core
Version: 3.1 | Last update: 2025-08-25
Contains:
• Header scroll & resize
• Hero video lazy-load & autoplay
• Cinematic Swiper init
• Mobile-menu delegation
• Accessibility helpers
• Performance optimisations
========================================================= */

/* ---------------------------------------------------------
1. CONFIG / UTILS
--------------------------------------------------------- */
const CONFIG = {
  debounceDelay: 150,
  autoplayDelay: 6000,
  rootPath: window.location.origin + '/'
};

const debounce = (fn, wait = CONFIG.debounceDelay) => {
  let t;
  return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); };
};

/* ---------------------------------------------------------
2. HEADER SCROLL EFFECT
--------------------------------------------------------- */
const header = document.querySelector('.header');
const heroSection = document.querySelector('.hero-section');

function updateHeaderState() {
  if (!header) return;
  header.classList.toggle('scrolled', window.scrollY > 50);
}
window.addEventListener('scroll', debounce(updateHeaderState, 50));

function syncHeroPadding() {
  if (!heroSection || !header) return;
  heroSection.style.paddingTop = header.offsetHeight + 'px';
}
window.addEventListener('resize', debounce(syncHeroPadding));
document.addEventListener('DOMContentLoaded', syncHeroPadding);

/* ---------------------------------------------------------
3. HERO VIDEO LAZY-LOAD & AUTOPLAY
--------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  const heroVideo = document.querySelector('.hero-video');
  if (!heroVideo) return;

  heroVideo.setAttribute('data-loading', 'true');
  heroVideo.addEventListener('loadeddata', () => {
    heroVideo.setAttribute('data-loaded', 'true');
    heroVideo.removeAttribute('data-loading');
  });

  setTimeout(() => {
    if (!heroVideo.hasAttribute('data-loaded')) {
      heroVideo.setAttribute('data-loaded', 'true');
      heroVideo.removeAttribute('data-loading');
    }
  }, 3500);

  const playPromise = heroVideo.play();
  if (playPromise !== undefined) {
    playPromise.catch(() => {
      heroVideo.style.display = 'none';
      heroSection.style.backgroundImage = 'url(assets/img/hero-fallback.jpg)';
    });
  }
});

/* ---------------------------------------------------------
4. CINEMATIC CAROUSEL (Swiper)
--------------------------------------------------------- */
class CinematicCarousel {
  constructor() {
    this.swiper = null;
    this.videos = [];
    this.progressBar = null;
    this.autoplayProgress = null;
    this.slideCounter = null;
    this.init();
  }

  init() {
    document.addEventListener('DOMContentLoaded', () => this.initializeCarousel());
  }

  initializeCarousel() {
    const swiperElement = document.querySelector('.videoProjectsSwiper');
    if (!swiperElement) return;

    this.progressBar = document.querySelector('.progress-bar');
    this.autoplayProgress = document.querySelector('.swiper-autoplay-progress circle');
    this.slideCounter = {
      current: document.querySelector('.current-slide'),
      total: document.querySelector('.total-slides')
    };
    this.videos = document.querySelectorAll('.bg-video');

    this.swiper = new Swiper(swiperElement, {
      loop: true,
      effect: 'fade',
      speed: 1200,
      grabCursor: true,

      autoplay: {
        delay: CONFIG.autoplayDelay,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      pagination: { el: '.swiper-pagination', clickable: true },
      keyboard: { enabled: true },

      fadeEffect: { crossFade: true },

      on: {
        init: (s) => this.onSwiperInit(s),
        slideChange: (s) => this.onSlideChange(s),
        autoplayTimeLeft: (s, time, progress) => this.updateAutoplayProgress(progress)
      }
    });
  }

  onSwiperInit(swiper) {
    this.updateSlideCounter(swiper);
    this.playActiveVideo();
  }

  onSlideChange(swiper) {
    this.updateSlideCounter(swiper);
    this.pauseAllVideos();
    setTimeout(() => this.playActiveVideo(), 300);
  }

  playActiveVideo() {
    const activeSlide = this.swiper.slides[this.swiper.activeIndex];
    const video = activeSlide?.querySelector('.bg-video');
    if (!video) return;
    video.currentTime = 0;
    video.play().catch(() => {
      video.style.display = 'none';
      const poster = video.getAttribute('poster');
      if (poster) {
        activeSlide.style.backgroundImage = `url(${poster})`;
      }
    });
  }

  pauseAllVideos() {
    this.videos.forEach(v => { if (!v.paused) v.pause(); });
  }

  updateSlideCounter(swiper) {
    if (!this.slideCounter.current || !this.slideCounter.total) return;
    const total = swiper.slides.filter(s => !s.classList.contains('swiper-slide-duplicate')).length;
    this.slideCounter.current.textContent = String(swiper.realIndex + 1).padStart(2, '0');
    this.slideCounter.total.textContent = String(total).padStart(2, '0');
  }

  updateAutoplayProgress(progress) {
    if (!this.autoplayProgress) return;
    const circumference = 2 * Math.PI * 20;
    this.autoplayProgress.style.strokeDashoffset = circumference * (1 - progress);
  }
}
let cinematicCarousel = new CinematicCarousel();

/* ---------------------------------------------------------
5. MOBILE MENU HANDLING
--------------------------------------------------------- */
function initMobileMenu() {
  const burger = document.querySelector('.hamburger');
  const menu = document.querySelector('.mobile-menu');
  const body = document.body;
  if (!burger || !menu) return;

  const openMenu = () => {
    menu.classList.add('is-open');
    burger.classList.add('is-active');
    body.classList.add('menu-open');
    body.style.overflow = 'hidden';
  };

  const closeMenu = () => {
    menu.classList.remove('is-open');
    burger.classList.remove('is-active');
    body.classList.remove('menu-open');
    body.style.overflow = '';
  };

  burger.addEventListener('click', () => {
    menu.classList.contains('is-open') ? closeMenu() : openMenu();
  });

  menu.querySelectorAll('a').forEach(link =>
    link.addEventListener('click', closeMenu)
  );

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && menu.classList.contains('is-open')) closeMenu();
  });
}
document.addEventListener('DOMContentLoaded', initMobileMenu);

/* ---------------------------------------------------------
6. PROJECTS SWIPER (Secondary)
--------------------------------------------------------- */
function initProjectsSwiper() {
  const el = document.querySelector('.projectsSwiper');
  if (!el) return;
  new Swiper(el, {
    loop: true,
    spaceBetween: 10,
    pagination: { el: '.swiper-pagination', clickable: true },
    autoplay: { delay: 5000, disableOnInteraction: false },
    breakpoints: {
      640: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 }
    }
  });
}
document.addEventListener('DOMContentLoaded', () => {
  initProjectsSwiper();
  if (document.querySelector('#gallery')) {
    import('./modules/projects.js').then(m => m.default?.());
  }
  if (document.querySelector('#ajaxForm')) {
    import('./modules/contact-form.js').then(m => m.initContactForm?.());
  }
});

/* ---------------------------------------------------------
7. ACCESSIBILITY & REDUCED MOTION
--------------------------------------------------------- */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.documentElement.style.setProperty('--transition-fast', '0.001ms');
  document.documentElement.style.setProperty('--transition-normal', '0.001ms');
  document.documentElement.style.scrollBehavior = 'auto';
}
