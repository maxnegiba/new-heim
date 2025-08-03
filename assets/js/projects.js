
// lazy-load batch-uri
let loadedCount = 12;
const batchSize = 12;
const gallery = document.getElementById('gallery');
const loader = document.getElementById('loader');
let isLoading = false; // Flag pentru a preveni apeluri multiple la scroll rapid

// Accesează array-ul de pe window (setat în PHP)
const allProjects = window.allProjects;

function loadMore() {
    if (loadedCount >= allProjects.length || isLoading) return;
    isLoading = true;
    loader.style.display = 'block';
    const next = allProjects.slice(loadedCount, loadedCount + batchSize);
    next.forEach((p, localIndex) => {
        const globalIndex = loadedCount + localIndex; // Calcul index global
        const card = document.createElement('div');
        card.className = 'project-card';
        card.innerHTML = `
            <picture>
                <source type="image/webp" srcset="${p.webp} 1x, ${p.webp2x} 2x">
                <img src="${p.src}" srcset="${p.src2x} 2x" loading="lazy" 
                     data-full="${p.src2x}" alt="${p.title}">
            </picture>
            <div class="overlay"><span>${p.title}</span></div>`;
        card.addEventListener('click', () => openModal(globalIndex));
        gallery.appendChild(card);
    });
    loadedCount += next.length;
    document.getElementById('loaded').textContent = loadedCount;
    if (loadedCount >= allProjects.length) loader.style.display = 'none';
    isLoading = false;
}

// Încarcă mai mult la scroll (cu check pentru a evita duplicări)
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        loadMore();
    }
});

// Atașează listener pe cardurile inițiale (primele 12)
document.querySelectorAll('.project-card').forEach((card, index) => {
    card.addEventListener('click', () => openModal(index));
});

// Lightbox
let currentIndex = 0;
const modal = document.getElementById('projectModal');
const modalImg = document.getElementById('modalImg');
const caption = document.getElementById('modalCaption');
function openModal(i) {
    currentIndex = i;
    modalImg.src = allProjects[i].src2x;
    caption.textContent = allProjects[i].title;
    modal.style.display = 'block';
}
document.querySelector('.close').onclick = () => modal.style.display = 'none';
document.querySelector('.arrow.left').onclick = () => {
    currentIndex = (currentIndex - 1 + allProjects.length) % allProjects.length;
    openModal(currentIndex);
};
document.querySelector('.arrow.right').onclick = () => {
    currentIndex = (currentIndex + 1) % allProjects.length;
    openModal(currentIndex);
};


// ... codul tău existent ...

// Inițializează VeluxSwiper
document.addEventListener('DOMContentLoaded', () => {
  const veluxSwiper = new VeluxSwiper(); // Apel noua clasă

  // Debug: Verifică încărcarea media (similar cu Gauben)
  const videos = document.querySelectorAll('.veluxVideoSwiper video');
  videos.forEach((video, index) => {
    video.addEventListener('loadeddata', () => console.log(`Velux Video ${index + 1} loaded`));
    video.addEventListener('error', () => console.error(`Velux Video ${index + 1} failed to load`));
  });

  const images = document.querySelectorAll('.velux-photo-grid img');
  images.forEach((img, index) => {
    img.addEventListener('load', () => console.log(`Velux Image ${index + 1} loaded`));
    img.addEventListener('error', () => console.error(`Velux Image ${index + 1} failed to load`));
  });
});

// Noua clasă pentru Velux Swiper
export class VeluxSwiper {
  constructor() {
    this.init();
  }

  init() {
    const el = document.querySelector('.veluxVideoSwiper');
    if (!el) return;

    new Swiper(el, {
      loop: true,
      spaceBetween: 10,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      autoplay: {
        delay: 6000,
        disableOnInteraction: false,
      },
      breakpoints: {
        640: { slidesPerView: 1 }, // Mobil: 1
        768: { slidesPerView: 2 }, // Tablet: 2
        1024: { slidesPerView: 3 }, // Desktop: Toate 3 (ca să vezi toate fără swipe)
      },
    });
  }
}