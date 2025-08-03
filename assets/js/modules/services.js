/* services.js – init pentru swiperele de pe services.php */
document.addEventListener('DOMContentLoaded', () => {
  /* 1. KlempnerSwiper (8 poze + 3 clipuri) */
  if (document.querySelector('.klempnerSwiper')) {
    new Swiper('.klempnerSwiper', {
      loop: true,
      spaceBetween: 10,
      slidesPerView: 1,
      pagination: { el: '.swiper-pagination', clickable: true },
      navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      autoplay: { delay: 5000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });
  }

  /* 2. GaubenSwiper (4 clipuri) */
  if (document.querySelector('.gaubenVideoSwiper')) {
    new Swiper('.gaubenVideoSwiper', {
      loop: true,
      spaceBetween: 10,
      pagination: { el: '.swiper-pagination', clickable: true },
      autoplay: { delay: 6000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 4 }
      }
    });
  }

  /* 3. VeluxSwiper (3 clipuri) */
  if (document.querySelector('.veluxVideoSwiper')) {
    new Swiper('.veluxVideoSwiper', {
      loop: true,
      spaceBetween: 10,
      pagination: { el: '.swiper-pagination', clickable: true },
      autoplay: { delay: 6000, disableOnInteraction: false },
      breakpoints: {
        640: { slidesPerView: 1 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });
  }

  /* 4. Preview preț material Neueindeckung */
  const prices = { ziegel: 90, metal: 75, membrane: 110 };
  document.querySelectorAll('input[name="material"]').forEach(radio => {
    radio.addEventListener('change', e => {
      const el = document.getElementById('price');
      if (el) el.textContent = `ca. ${prices[e.target.id]} € / m²`;
    });
  });

  /* 5. Deschidere / închidere modale */
  document.querySelectorAll('[data-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(btn.dataset.open);
      if (modal) modal.style.display = 'block';
    });
  });
  document.querySelectorAll('.modal__close').forEach(close => {
    close.addEventListener('click', () => close.closest('.modal').style.display = 'none');
  });
});