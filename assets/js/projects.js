const allProjects = Array.isArray(window.allProjects) ? window.allProjects : [];
const totalProjects = Number(window.totalProjects || allProjects.length || 0);
const gallery = document.getElementById('gallery');
const loader = document.getElementById('loader');
const loadedLabel = document.getElementById('loaded');
const modal = document.getElementById('projectModal');
const modalImg = document.getElementById('modalImg');
const caption = document.getElementById('modalCaption');
const closeButton = modal?.querySelector('.close');
const previousButton = modal?.querySelector('.arrow.left');
const nextButton = modal?.querySelector('.arrow.right');

let loadedCount = Math.min(12, allProjects.length);
let currentIndex = 0;
let swiper = null;
let loading = false;

function escapeHtml(value) {
  return String(value)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}

function createProjectSlide(project, index) {
  const slide = document.createElement('div');
  slide.className = 'project-card swiper-slide';
  slide.dataset.index = String(index);
  slide.innerHTML = `
    <picture>
      <source type="image/webp" srcset="${escapeHtml(project.webp)} 1x, ${escapeHtml(project.webp2x)} 2x">
      <img src="${escapeHtml(project.src)}"
           srcset="${escapeHtml(project.src2x)} 2x"
           loading="lazy"
           data-full="${escapeHtml(project.src2x)}"
           alt="${escapeHtml(project.title)}">
    </picture>
    <div class="overlay"><span>${escapeHtml(project.title)}</span></div>
  `;
  slide.addEventListener('click', () => openModal(index));
  return slide;
}

function loadMore() {
  if (!swiper || loading || loadedCount >= allProjects.length) return;

  loading = true;
  if (loader) loader.style.display = 'block';

  const nextProjects = allProjects.slice(loadedCount, loadedCount + 12);
  const slides = nextProjects.map((project, localIndex) =>
    createProjectSlide(project, loadedCount + localIndex)
  );

  swiper.appendSlide(slides);
  loadedCount += slides.length;

  if (loadedLabel) loadedLabel.textContent = String(loadedCount);
  if (loader) loader.style.display = loadedCount < allProjects.length ? 'block' : 'none';

  loading = false;
}

function openModal(index) {
  if (!modal || !modalImg || !caption || !allProjects[index]) return;
  currentIndex = index;
  modalImg.src = allProjects[index].src2x || allProjects[index].src;
  modalImg.alt = allProjects[index].title || '';
  caption.textContent = allProjects[index].title || '';
  modal.style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  if (!modal) return;
  modal.style.display = 'none';
  document.body.style.overflow = '';
}

function showPrevious() {
  if (!allProjects.length) return;
  currentIndex = (currentIndex - 1 + allProjects.length) % allProjects.length;
  openModal(currentIndex);
}

function showNext() {
  if (!allProjects.length) return;
  currentIndex = (currentIndex + 1) % allProjects.length;
  openModal(currentIndex);
}

function bindInitialCards() {
  gallery?.querySelectorAll('.project-card').forEach((card) => {
    const index = Number(card.dataset.index || 0);
    card.addEventListener('click', () => openModal(index));
  });
}

function initCarousel() {
  if (!gallery || !window.Swiper || swiper) return;

  swiper = new window.Swiper(gallery, {
    slidesPerView: 1,
    spaceBetween: 22,
    speed: 650,
    grabCursor: true,
    watchSlidesProgress: true,
    loop: false,
    keyboard: {
      enabled: true,
      onlyInViewport: true,
    },
    pagination: {
      el: gallery.querySelector('.swiper-pagination'),
      clickable: true,
      dynamicBullets: true,
    },
    navigation: {
      nextEl: gallery.querySelector('.swiper-button-next'),
      prevEl: gallery.querySelector('.swiper-button-prev'),
    },
    breakpoints: {
      700: {
        slidesPerView: 2,
        spaceBetween: 22,
      },
      1100: {
        slidesPerView: 3,
        spaceBetween: 24,
      },
    },
    on: {
      reachEnd() {
        loadMore();
      },
    },
  });
}

function waitForSwiper(tries = 0) {
  if (!gallery) return;
  if (window.Swiper) {
    initCarousel();
    return;
  }
  if (tries < 80) {
    window.setTimeout(() => waitForSwiper(tries + 1), 75);
  }
}

bindInitialCards();
waitForSwiper();

closeButton?.addEventListener('click', closeModal);
previousButton?.addEventListener('click', showPrevious);
nextButton?.addEventListener('click', showNext);

modal?.addEventListener('click', (event) => {
  if (event.target === modal) closeModal();
});

document.addEventListener('keydown', (event) => {
  if (!modal || modal.style.display !== 'block') return;
  if (event.key === 'Escape') closeModal();
  if (event.key === 'ArrowLeft') showPrevious();
  if (event.key === 'ArrowRight') showNext();
});

if (totalProjects <= loadedCount && loader) {
  loader.style.display = 'none';
}
