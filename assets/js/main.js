
/* =========================================================
   MAIN.JS – MeisterDach Website Core
   Version: 3.0 | Last update: 2024-07-19
   Contains:
   • Header scroll & resize
   • Hero video lazy-load & autoplay
   • Cinematic Swiper init
   • Mobile-menu delegation
   • Accessibility helpers
   • Performance optimisations
========================================================= */

/* ---------------------------------------------------------
   1.  CONFIG / UTILS
--------------------------------------------------------- */
const CONFIG = {
  debounceDelay: 150,
  autoplayDelay: 6000,
  rootPath: window.location.origin + '/'
};

/* Throttle / debounce helpers */
const debounce = (fn, wait = CONFIG.debounceDelay) => {
  let t;
  return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), wait); };
};

/* ---------------------------------------------------------
   2.  HEADER SCROLL EFFECT
--------------------------------------------------------- */
const header = document.querySelector('.header');
const heroSection = document.querySelector('.hero-section');

function updateHeaderState() {
  if (!header) return;
  header.classList.toggle('scrolled', window.scrollY > 50);
}
window.addEventListener('scroll', debounce(updateHeaderState, 50));

/* Adjust hero padding when header resizes */
function syncHeroPadding() {
  if (!heroSection || !header) return;
  heroSection.style.paddingTop = header.offsetHeight + 'px';
}
window.addEventListener('resize', debounce(syncHeroPadding));
document.addEventListener('DOMContentLoaded', syncHeroPadding);

/* ---------------------------------------------------------
   3.  HERO VIDEO LAZY-LOAD & AUTOPLAY
--------------------------------------------------------- */
document.addEventListener('DOMContentLoaded', () => {
  const heroVideo = document.querySelector('.hero-video');
  if (!heroVideo) return;

  heroVideo.setAttribute('data-loading', 'true');

  heroVideo.addEventListener('loadeddata', () => {
    heroVideo.setAttribute('data-loaded', 'true');
    heroVideo.removeAttribute('data-loading');
  });

  /* Fallback if video fails to load */
  setTimeout(() => {
    if (!heroVideo.hasAttribute('data-loaded')) {
      heroVideo.setAttribute('data-loaded', 'true');
      heroVideo.removeAttribute('data-loading');
    }
  }, 3500);

  /* Try autoplay. If blocked, show fallback image */
  const playPromise = heroVideo.play();
  if (playPromise !== undefined) {
    playPromise.catch(() => {
      heroVideo.style.display = 'none';
      heroSection.style.backgroundImage = 'url(assets/img/hero-fallback.jpg)';
    });
  }
});

/* ==========================================================================
   ENHANCED CINEMATIC SWIPER INITIALIZATION
========================================================================== */

class CinematicCarousel {
  constructor() {
    this.swiper = null;
    this.isInitialized = false;
    this.autoplayProgress = null;
    this.progressBar = null;
    this.slideCounter = null;
    this.videos = [];
    
    this.init();
  }

  init() {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.initializeCarousel());
    } else {
      this.initializeCarousel();
    }
  }

  initializeCarousel() {
    const carouselElement = document.querySelector('.videoProjectsSwiper');
    if (!carouselElement) return;

    // Initialize components
    this.initializeElements();
    this.preloadVideos();
    this.initializeSwiper();
    this.setupEventListeners();
    this.initializeAnimations();
    
    // Remove loading state
    setTimeout(() => {
      document.querySelector('.cinematic-carousel')?.classList.remove('loading');
    }, 500);
  }

  initializeElements() {
    this.autoplayProgress = document.querySelector('.swiper-autoplay-progress circle');
    this.progressBar = document.querySelector('.progress-bar');
    this.slideCounter = {
      current: document.querySelector('.current-slide'),
      total: document.querySelector('.total-slides')
    };
    this.videos = document.querySelectorAll('.bg-video');
  }

  preloadVideos() {
    this.videos.forEach((video, index) => {
      // Set loading priority
      video.setAttribute('loading', index === 0 ? 'eager' : 'lazy');
      
      // Handle video loading
      video.addEventListener('loadeddata', () => {
        video.classList.add('loaded');
      });

      // Handle video errors
      video.addEventListener('error', () => {
        console.warn(`Video ${index + 1} failed to load`);
        video.style.display = 'none';
      });
    });
  }

  initializeSwiper() {
    const swiperElement = document.querySelector('.videoProjectsSwiper');
    
    this.swiper = new Swiper(swiperElement, {
      // Core settings
      loop: true,
      effect: 'fade',
      speed: 1200,
      grabCursor: true,
      watchSlidesProgress: true,
      
      // Autoplay configuration
      autoplay: {
        delay: CONFIG?.autoplayDelay || 6000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      
      // Navigation
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      
      // Pagination
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
        renderBullet: (index, className) => {
          return `<span class="${className}" aria-label="Slide ${index + 1}"></span>`;
        },
      },
      
      // Keyboard navigation
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },
      
      // Accessibility
      a11y: {
        prevSlideMessage: 'Proiectul anterior',
        nextSlideMessage: 'Următorul proiect',
        firstSlideMessage: 'Acesta este primul slide',
        lastSlideMessage: 'Acesta este ultimul slide',
      },
      
      // Fade effect settings
      fadeEffect: {
        crossFade: true,
      },
      
      // Event callbacks
      on: {
        init: (swiper) => {
          this.onSwiperInit(swiper);
        },
        slideChange: (swiper) => {
          this.onSlideChange(swiper);
        },
        autoplayTimeLeft: (swiper, time, progress) => {
          this.updateAutoplayProgress(progress);
        },
        autoplayStart: () => {
          this.onAutoplayStart();
        },
        autoplayStop: () => {
          this.onAutoplayStop();
        },
        touchStart: () => {
          this.pauseAllVideos();
        },
        touchEnd: () => {
          this.playActiveVideo();
        },
      },
    });

    this.isInitialized = true;
  }

  onSwiperInit(swiper) {
    // Update slide counter
    this.updateSlideCounter(swiper);
    
    // Play first video
    this.playActiveVideo();
    
    // Initialize AOS animations
    if (typeof AOS !== 'undefined') {
      AOS.refresh();
    }
    
    // Set initial progress
    this.updateProgressBar(0);
  }

  onSlideChange(swiper) {
    // Update slide counter
    this.updateSlideCounter(swiper);
    
    // Handle video playback
    this.handleVideoTransition();
    
    // Refresh animations
    this.refreshSlideAnimations();
    
    // Update progress bar
    const progress = (swiper.realIndex + 1) / this.getTotalUniqueSlides(swiper) * 100;
    this.updateProgressBar(progress);
    
    // Announce slide change for screen readers
    this.announceSlideChange(swiper.realIndex + 1);
  }

  getTotalUniqueSlides(swiper) {
    // Calculează numărul de slide-uri unice, excluzând duplicatele din loop
    return swiper.slides.filter(slide => !slide.classList.contains('swiper-slide-duplicate')).length;
  }

  handleVideoTransition() {
    // Pause all videos first
    this.pauseAllVideos();
    
    // Small delay to ensure smooth transition
    setTimeout(() => {
      this.playActiveVideo();
    }, 300);
  }

  playActiveVideo() {
    if (!this.swiper) return;
    
    const activeSlide = this.swiper.slides[this.swiper.activeIndex];
    const activeVideo = activeSlide?.querySelector('.bg-video');
    
    if (activeVideo) {
      activeVideo.currentTime = 0;
      activeVideo.play().catch(() => {
        // Fallback: afișăm poster dacă video nu poate fi redat
        activeVideo.style.display = 'none';
        const poster = activeVideo.getAttribute('poster');
        if (poster) {
          activeSlide.style.backgroundImage = `url(${poster})`;
          activeSlide.style.backgroundSize = 'cover';
        }
      });
    }
  }

  pauseAllVideos() {
    this.videos.forEach(video => {
      if (!video.paused) {
        video.pause();
      }
    });
  }

  updateSlideCounter(swiper) {
    if (!this.slideCounter.current || !this.slideCounter.total) return;
    
    const totalSlides = this.getTotalUniqueSlides(swiper);
    const current = String(swiper.realIndex + 1).padStart(2, '0');
    const total = String(totalSlides).padStart(2, '0');
    
    this.slideCounter.current.textContent = current;
    this.slideCounter.total.textContent = total;
  }

  updateAutoplayProgress(progress) {
    if (!this.autoplayProgress) return;
    
    const circumference = 2 * Math.PI * 20; // radius = 20
    const offset = circumference * (1 - progress);
    
    this.autoplayProgress.style.strokeDashoffset = offset;
    this.autoplayProgress.style.stroke = progress > 0.8 ? '#d32f2f' : 'rgba(255,255,255,0.6)';
  }

  updateProgressBar(progress) {
    if (!this.progressBar) return;
    
    this.progressBar.style.width = `${progress}%`;
  }

  onAutoplayStart() {
    document.querySelector('.swiper-autoplay-progress')?.classList.add('active');
  }

  onAutoplayStop() {
    document.querySelector('.swiper-autoplay-progress')?.classList.remove('active');
  }

  refreshSlideAnimations() {
    // Reset and trigger AOS animations for current slide
    if (typeof AOS !== 'undefined') {
      const activeSlide = document.querySelector('.swiper-slide-active');
      if (activeSlide) {
        const animatedElements = activeSlide.querySelectorAll('[data-aos]');
        animatedElements.forEach(el => {
          el.classList.remove('aos-animate');
          setTimeout(() => {
            el.classList.add('aos-animate');
          }, 100);
        });
      }
    }
  }

  announceSlideChange(slideNumber) {
    // Create announcement for screen readers
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'sr-only';
    announcement.textContent = `Slide ${slideNumber} activ`;
    
    document.body.appendChild(announcement);
    
    setTimeout(() => {
      document.body.removeChild(announcement);
    }, 1000);
  }

  setupEventListeners() {
    const carousel = document.querySelector('.cinematic-carousel');
    if (!carousel) return;

    // Pause/resume on hover
    carousel.addEventListener('mouseenter', () => {
      if (this.swiper?.autoplay) {
        this.swiper.autoplay.stop();
      }
    });

    carousel.addEventListener('mouseleave', () => {
      if (this.swiper?.autoplay) {
        this.swiper.autoplay.start();
      }
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (!this.swiper) return;
      
      switch(e.key) {
        case 'ArrowLeft':
          e.preventDefault();
          this.swiper.slidePrev();
          break;
        case 'ArrowRight':
          e.preventDefault();
          this.swiper.slideNext();
          break;
        case ' ':
          e.preventDefault();
          this.toggleAutoplay();
          break;
      }
    });

    // Visibility change handling
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        this.pauseAllVideos();
        this.swiper?.autoplay?.stop();
      } else {
        this.playActiveVideo();
        this.swiper?.autoplay?.start();
      }
    });

    // Intersection Observer for performance
    this.setupIntersectionObserver();
  }

  setupIntersectionObserver() {
    const carousel = document.querySelector('.cinematic-carousel');
    if (!carousel) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          // Carousel is visible
          this.playActiveVideo();
          this.swiper?.autoplay?.start();
        } else {
          // Carousel is not visible
          this.pauseAllVideos();
          this.swiper?.autoplay?.stop();
        }
      });
    }, {
      threshold: 0.5
    });

    observer.observe(carousel);
  }

  initializeAnimations() {
    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
      AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: false,
        mirror: true,
        offset: 100,
      });
    }

    // Add entrance animations
    const carousel = document.querySelector('.cinematic-carousel');
    if (carousel) {
      carousel.style.opacity = '0';
      carousel.style.transform = 'scale(1.05)';
      
      setTimeout(() => {
        carousel.style.transition = 'opacity 1s ease, transform 1s ease';
        carousel.style.opacity = '1';
        carousel.style.transform = 'scale(1)';
      }, 100);
    }
  }

  toggleAutoplay() {
    if (!this.swiper?.autoplay) return;
    
    if (this.swiper.autoplay.running) {
      this.swiper.autoplay.stop();
    } else {
      this.swiper.autoplay.start();
    }
  }

  // Public methods for external control
  goToSlide(index) {
    if (this.swiper) {
      this.swiper.slideTo(index);
    }
  }

  nextSlide() {
    if (this.swiper) {
      this.swiper.slideNext();
    }
  }

  prevSlide() {
    if (this.swiper) {
      this.swiper.slidePrev();
    }
  }

  destroy() {
    if (this.swiper) {
      this.pauseAllVideos();
      this.swiper.destroy(true, true);
      this.swiper = null;
      this.isInitialized = false;
    }
  }

  // Performance monitoring
  getPerformanceMetrics() {
    return {
      isInitialized: this.isInitialized,
      activeSlide: this.swiper?.activeIndex || 0,
      totalSlides: this.swiper?.slides?.length || 0,
      autoplayRunning: this.swiper?.autoplay?.running || false,
      videosLoaded: Array.from(this.videos).filter(v => v.readyState >= 2).length,
      totalVideos: this.videos.length
    };
  }
}

// Initialize carousel when DOM is ready
let cinematicCarousel;

document.addEventListener('DOMContentLoaded', () => {
  cinematicCarousel = new CinematicCarousel();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
  if (cinematicCarousel) {
    cinematicCarousel.destroy();
  }
});

// Export for external use
window.CinematicCarousel = CinematicCarousel;

/* ---------------------------------------------------------
   6.  LAZY MODULES
--------------------------------------------------------- */
function initProjectsSwiper() {
  const el = document.querySelector('.projectsSwiper'); // Presupunem un selector generic; ajustează dacă este necesar
  if (!el) return;

  new Swiper(el, {
    loop: true,
    spaceBetween: 10,
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    breakpoints: {
      640: { slidesPerView: 1 },
      768: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    },
  });
}

document.addEventListener('DOMContentLoaded', () => {
  initProjectsSwiper();

  /* Dynamically import project-specific modules */
  if (document.querySelector('#gallery')) {
    import('./modules/projects.js').then(m => m.default?.());
  }
  if (document.querySelector('#ajaxForm')) {
    import('./modules/contact-form.js').then(m => m.initContactForm?.());
  }
});

/* ---------------------------------------------------------
   7.  ACCESSIBILITY & REDUCED MOTION
--------------------------------------------------------- */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.documentElement.style.setProperty('--transition-fast', '0.001ms');
  document.documentElement.style.setProperty('--transition-normal', '0.001ms');
  document.documentElement.style.scrollBehavior = 'auto';
}

/* ---------------------------------------------------------
   8.  PERFORMANCE – Preload critical assets
--------------------------------------------------------- */
const preload = (href, as = 'image') => {
  const l = document.createElement('link');
  l.rel = 'preload';
  l.href = href;
  l.as = as;
  document.head.appendChild(l);
};

/* Preload first video poster & fonts */
document.addEventListener('DOMContentLoaded', () => {
  preload(`${CONFIG.rootPath}assets/video/projects/project1-poster.webp`);
  preload('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap', 'style');
});



