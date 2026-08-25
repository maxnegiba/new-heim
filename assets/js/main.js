/* =========================================================
MAIN.JS – MeisterDach Website Core
Version: 3.2 | Last update: 2025-08-25
Contains:
• Header scroll & resize
• Hero video lazy-load & autoplay
• Cinematic Swiper init (robust)
• Mobile-menu delegation (matches .nav-mobile)
• Accessibility helpers
• Performance optimisations
========================================================= */

/* ---------------------------------------------------------
1. CONFIG / UTILS
--------------------------------------------------------- */
const CONFIG = {
  debounceDelay: 150,
  autoplayDelay: 6000,
  rootPath: window.location.origin + '/',
  desktopBreakpoint: 992
};

const debounce = (fn, wait = CONFIG.debounceDelay) => {
  let t;
  return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); };
};

const onReady = (fn) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', fn, { once: true });
  } else {
    fn();
  }
};

/* Safe async wait for a global (e.g., Swiper) */
const waitForGlobal = (name, { tries = 60, interval = 100 } = {}) =>
  new Promise((resolve, reject) => {
    let count = 0;
    const i = setInterval(() => {
      if (window[name]) { clearInterval(i); resolve(window[name]); }
      else if (++count >= tries) { clearInterval(i); reject(new Error(`${name} not found`)); }
    }, interval);
  });

/* ---------------------------------------------------------
2. HEADER SCROLL EFFECT + HERO PADDING
--------------------------------------------------------- */
const headerEl = document.querySelector('.header');
const heroSection = document.querySelector('.hero-section');

function updateHeaderState() {
  if (!headerEl) return;
  headerEl.classList.toggle('scrolled', window.scrollY > 50);
}
window.addEventListener('scroll', debounce(updateHeaderState, 50), { passive: true });

function syncHeroPadding() {
  if (!heroSection || !headerEl) return;
  heroSection.style.paddingTop = headerEl.offsetHeight + 'px';
}
window.addEventListener('resize', debounce(syncHeroPadding));
onReady(syncHeroPadding);

/* ---------------------------------------------------------
3. HERO VIDEO LAZY-LOAD & AUTOPLAY
--------------------------------------------------------- */
onReady(() => {
  const heroVideo = document.querySelector('.hero-video');
  if (!heroVideo) return;

  // Mark loading state
  heroVideo.setAttribute('data-loading', 'true');
  heroVideo.muted = true; // harden autoplay on mobile

  heroVideo.addEventListener('loadeddata', () => {
    heroVideo.setAttribute('data-loaded', 'true');
    heroVideo.removeAttribute('data-loading');
  });

  // Fallback if video stalls
  setTimeout(() => {
    if (!heroVideo.hasAttribute('data-loaded')) {
      heroVideo.setAttribute('data-loaded', 'true');
      heroVideo.removeAttribute('data-loading');
    }
  }, 3500);

  // Try autoplay
  const p = heroVideo.play?.();
  if (p && typeof p.then === 'function') {
    p.catch(() => {
      heroVideo.style.display = 'none';
      if (heroSection) {
        heroSection.style.backgroundImage = 'url(assets/img/hero-fallback.jpg)';
        heroSection.style.backgroundSize = 'cover';
        heroSection.style.backgroundPosition = 'center';
      }
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
    this.autoplayCircle = null;
    this.slideCounter = { current: null, total: null };
    this._initialized = false;
    this._init();
  }

  _init() {
    onReady(async () => {
      const container = document.querySelector('.videoProjectsSwiper');
      if (!container) return;

      // Ensure Swiper library is available, regardless of script order
      try { await waitForGlobal('Swiper', { tries: 80, interval: 75 }); }
      catch { console.warn('Swiper library not found. Skipping carousel.'); return; }

      this._cacheEls();
      this._prepareVideos();
      this._initSwiper(container);
      this._setupVisibilityControls();
      this._initialized = true;

      // Remove loading state from wrapper section
      setTimeout(() => {
        document.querySelector('.cinematic-carousel')?.classList.remove('loading');
      }, 400);
    });
  }

  _cacheEls() {
    this.progressBar = document.querySelector('.progress-bar');
    this.autoplayCircle = document.querySelector('.swiper-autoplay-progress circle');
    this.slideCounter.current = document.querySelector('.current-slide');
    this.slideCounter.total = document.querySelector('.total-slides');
    this.videos = Array.from(document.querySelectorAll('.bg-video'));
  }

  _prepareVideos() {
    // Force mute for mobile autoplay and add simple load/error handlers
    this.videos.forEach((v, i) => {
      v.muted = true;
      v.setAttribute('playsinline', '');
      v.setAttribute('preload', i === 0 ? 'auto' : 'metadata');

      v.addEventListener('loadeddata', () => v.classList.add('loaded'));
      v.addEventListener('error', () => {
        console.warn('Slide video failed to load:', v);
        v.style.display = 'none';
      });
    });
  }

  _initSwiper(container) {
    if (this.swiper) return; // guard double init

    this.swiper = new Swiper(container, {
      loop: true,
      effect: 'fade',
      speed: 1200,
      grabCursor: true,
      watchSlidesProgress: true,
      fadeEffect: { crossFade: true },

      autoplay: {
        delay: CONFIG.autoplayDelay,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },

      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },

      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        renderBullet: (index, className) =>
          `<span class="${className}" aria-label="Slide ${index + 1}"></span>`,
      },

      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },

      a11y: {
        prevSlideMessage: 'Proiectul anterior',
        nextSlideMessage: 'Următorul proiect',
        firstSlideMessage: 'Acesta este primul slide',
        lastSlideMessage: 'Acesta este ultimul slide',
      },

      on: {
        init: (s) => this._onInit(s),
        slideChange: (s) => this._onSlideChange(s),
        autoplayTimeLeft: (s, _time, progress) => this._updateAutoplayCircle(progress),
      }
    });
  }

  _onInit(swiper) {
    this._updateSlideCounter(swiper);
    this._playActiveVideo();
    this._updateLinearProgress(0);
  }

  _onSlideChange(swiper) {
    this._updateSlideCounter(swiper);
    this._pauseAllVideos();
    setTimeout(() => this._playActiveVideo(), 250);

    const total = this._getTotalUniqueSlides(swiper);
    const progressPct = total ? ((swiper.realIndex + 1) / total) * 100 : 0;
    this._updateLinearProgress(progressPct);
  }

  _getTotalUniqueSlides(swiper) {
    return Array.from(swiper.slides).filter(
      (s) => !s.classList.contains('swiper-slide-duplicate')
    ).length;
  }

  _updateSlideCounter(swiper) {
    const { current, total } = this.slideCounter;
    if (!current || !total) return;
    const t = this._getTotalUniqueSlides(swiper);
    current.textContent = String(swiper.realIndex + 1).padStart(2, '0');
    total.textContent = String(t).padStart(2, '0');
  }

  _playActiveVideo() {
    if (!this.swiper) return;
    const activeSlide = this.swiper.slides[this.swiper.activeIndex];
    const video = activeSlide?.querySelector('.bg-video');
    if (!video) return;

    video.currentTime = 0;
    const pp = video.play?.();
    if (pp && typeof pp.then === 'function') {
      pp.catch(() => {
        video.style.display = 'none';
        const poster = video.getAttribute('poster');
        if (poster) {
          activeSlide.style.backgroundImage = `url(${poster})`;
          activeSlide.style.backgroundSize = 'cover';
          activeSlide.style.backgroundPosition = 'center';
        }
      });
    }
  }

  _pauseAllVideos() {
    this.videos.forEach(v => { try { if (!v.paused) v.pause(); } catch(_){} });
  }

  _updateAutoplayCircle(progress) {
    if (!this.autoplayCircle) return;
    const circumference = 2 * Math.PI * 20; // r=20 matches your SVG
    this.autoplayCircle.style.strokeDashoffset = String(circumference * (1 - progress));
  }

  _updateLinearProgress(percent) {
    if (this.progressBar) this.progressBar.style.width = `${percent}%`;
  }

  _setupVisibilityControls() {
    const wrapper = document.querySelector('.cinematic-carousel');
    if (!wrapper || !this.swiper) return;

    // Hover pause/resume (desktop)
    wrapper.addEventListener('mouseenter', () => this.swiper?.autoplay?.stop());
    wrapper.addEventListener('mouseleave', () => this.swiper?.autoplay?.start());

    // Page visibility
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) { this._pauseAllVideos(); this.swiper?.autoplay?.stop(); }
      else { this._playActiveVideo(); this.swiper?.autoplay?.start(); }
    });

    // Intersection observer for performance
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) { this._playActiveVideo(); this.swiper?.autoplay?.start(); }
        else { this._pauseAllVideos(); this.swiper?.autoplay?.stop(); }
      });
    }, { threshold: 0.5 });
    io.observe(wrapper);
  }
}
const cinematicCarousel = new CinematicCarousel();

/* ---------------------------------------------------------
5. MOBILE MENU HANDLING (matches your .nav-mobile & overlay)
--------------------------------------------------------- */
function initMobileMenu() {
  const burger = document.querySelector('.hamburger');
  const menu = document.querySelector('.nav-mobile'); // NOTE: matches your header
  const overlay = document.querySelector('.mobile-overlay');
  const body = document.body;

  if (!burger || !menu) return;

  const openMenu = () => {
    menu.classList.add('active');
    burger.classList.add('is-active');
    overlay?.classList.add('active');
    body.classList.add('menu-open');
    body.style.overflow = 'hidden';
    burger.setAttribute('aria-expanded', 'true');
  };

  const closeMenu = () => {
    menu.classList.remove('active');
    burger.classList.remove('is-active');
    overlay?.classList.remove('active');
    body.classList.remove('menu-open');
    body.style.overflow = '';
    burger.setAttribute('aria-expanded', 'false');
  };

  const toggleMenu = () => (menu.classList.contains('active') ? closeMenu() : openMenu());

  burger.addEventListener('click', (e) => { e.stopPropagation(); toggleMenu(); });

  // Close on overlay click
  overlay?.addEventListener('click', closeMenu);

  // Close when a nav link is clicked
  menu.addEventListener('click', (e) => {
    const target = e.target;
    if (target && target.closest('a')) closeMenu();
  });

  // Close on ESC
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && menu.classList.contains('active')) closeMenu();
  });

  // Reset state on resize to desktop
  window.addEventListener('resize', debounce(() => {
    if (window.innerWidth >= CONFIG.desktopBreakpoint) closeMenu();
  }, 150));

  // Defensive: close if user scrolls to top and layout drastically changes
  window.addEventListener('orientationchange', () => setTimeout(closeMenu, 300));
}
onReady(initMobileMenu);

/* ---------------------------------------------------------
6. PROJECTS SWIPER (secondary simple grid swiper)
--------------------------------------------------------- */
function initProjectsSwiper() {
  const el = document.querySelector('.projectsSwiper');
  if (!el) return;

  const start = () => {
    // eslint-disable-next-line no-undef
    new Swiper(el, {
      loop: true,
      spaceBetween: 10,
      pagination: { el: '.swiper-pagination', clickable: true },
      autoplay: { delay: 5000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 },
      },
    });
  };

  if (window.Swiper) start();
  else waitForGlobal('Swiper').then(start).catch(() => console.warn('Swiper not found for .projectsSwiper'));
}
onReady(() => {
  initProjectsSwiper();

  // Lazy import other modules
  if (document.querySelector('#gallery')) {
    import('./modules/projects.js').then(m => m.default?.()).catch(() => {});
  }
  if (document.querySelector('#ajaxForm')) {
    import('./modules/contact-form.js').then(m => m.initContactForm?.()).catch(() => {});
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

/* ---------------------------------------------------------
8. MISC (remove undefined preload() calls)
--------------------------------------------------------- */
// If you want to preload a stylesheet safely, use this helper:
function safePreloadStyle(href) {
  const link = document.createElement('link');
  link.rel = 'preload';
  link.as = 'style';
  link.href = href;
  link.onload = function() { this.onload = null; this.rel = 'stylesheet'; };
  document.head.appendChild(link);
}
// Example (kept disabled because bundle.min.css is already loaded synchronously):
// safePreloadStyle('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');
document.addEventListener('DOMContentLoaded', function() {
  // Select all elements that should be Swiper containers
  const swiperContainers = document.querySelectorAll('.swiper-container');

  // Default Swiper parameters (can be overridden per instance if needed)
  const defaultParams = {
    loop: true,
    spaceBetween: 16,
    pagination: {
      // Use function to find pagination within the specific swiper container
      el: null, // Will be set dynamically
      clickable: true,
    },
    navigation: {
      // Use function to find navigation buttons within the specific swiper container
      nextEl: null, // Will be set dynamically
      prevEl: null, // Will be set dynamically
    },
    breakpoints: {
      768: {
        slidesPerView: 1.2
      },
      1024: {
        slidesPerView: 1.4
      },
    }
  };

  // Iterate through each container and initialize Swiper
  swiperContainers.forEach(container => {
    // Clone default parameters to avoid mutation issues
    const params = {...defaultParams};

    // Set specific elements for pagination and navigation within this container
    params.pagination.el = container.querySelector('.swiper-pagination');
    params.navigation.nextEl = container.querySelector('.swiper-button-next');
    params.navigation.prevEl = container.querySelector('.swiper-button-prev');

    // Check if required elements exist before initializing
    if (params.pagination.el && params.navigation.nextEl && params.navigation.prevEl) {
      try {
        new Swiper(container, params);
        console.log('Swiper initialized for:', container);
      } catch (error) {
        console.error('Error initializing Swiper for container:', container, error);
      }
    } else {
      console.warn('Swiper navigation/pagination elements not found in:', container);
    }
  });
});