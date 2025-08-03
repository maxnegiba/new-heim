// assets/js/modules/mobile-menu.js
// Funcția principală de inițializare a meniului mobil
const initMobileMenu = () => {
    const hamburger = document.querySelector('.hamburger');
    const mobileNav = document.querySelector('.nav-mobile');

    // Dacă nu găsim elementele esențiale, ieșim din funcție
    if (!hamburger || !mobileNav) {
        // console.log("Meniul mobil sau butonul hamburger nu au fost găsite.");
        return;
    }

    // Funcție pentru a deschide/inchide meniul
    const toggleMenu = (e) => {
        e.stopPropagation(); // Oprire propagare pentru a nu declanșa închiderea imediată

        const isExpanded = hamburger.getAttribute('aria-expanded') === 'true';

        // Comută clasele active
        hamburger.classList.toggle('active');
        mobileNav.classList.toggle('active');

        // Actualizează aria-expanded pentru accesibilitate
        hamburger.setAttribute('aria-expanded', !isExpanded);

        // Opțional: Poți adăuga aici logica pentru animarea liniilor hamburgerului
        // dacă nu este controlată complet de CSS.
    };

    // Funcție pentru a închide meniul
    const closeMenu = () => {
        hamburger.classList.remove('active');
        mobileNav.classList.remove('active');
        hamburger.setAttribute('aria-expanded', 'false');
        // Revenire la starea inițială a liniilor hamburger (dacă este cazul)
        // const spans = hamburger.querySelectorAll('span');
        // spans[0].style.transform = 'none';
        // spans[1].style.opacity = '1';
        // spans[2].style.transform = 'none';
    };

    // Adaugă event listener pe hamburger
    hamburger.addEventListener('click', toggleMenu);

    // Adaugă event listener pe document pentru a închide meniul la click în exterior
    document.addEventListener('click', (e) => {
        // Verifică dacă meniul este activ și dacă click-ul NU a fost pe hamburger sau pe meniul mobil
        if (mobileNav.classList.contains('active') &&
            !e.target.closest('.hamburger') &&
            !e.target.closest('.nav-mobile')) {
            closeMenu();
        }
    });

    // Opțional: Închide meniul la redimensionarea ferestrei (poate fi util)
    window.addEventListener('resize', () => {
        if (window.innerWidth > 991.98) { // Potrivit cu media query-ul din CSS
            closeMenu();
        }
    });
};

// APELAREA FUNCȚIEI
// Deoarece scriptul este încărcat cu `defer`, DOM-ul este deja gata.
// Putem apela funcția direct.
initMobileMenu();

// Dacă vrei să fii extra sigur sau folosești module ES6:
// if (document.readyState === 'loading') {
//     document.addEventListener('DOMContentLoaded', initMobileMenu);
// } else {
//     initMobileMenu();
// }

// Pentru module ES6, dacă este cazul (nu e necesar aici dacă nu folosești `import` în altă parte):
// export default initMobileMenu;