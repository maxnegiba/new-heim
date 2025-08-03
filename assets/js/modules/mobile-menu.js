// assets/js/modules/mobile-menu.js

const initMobileMenu = () => {
    const hamburger = document.querySelector('.hamburger');
    if (!hamburger) return;
    
    const mobileNav = document.querySelector('.nav-mobile');


    document.addEventListener('click', (e) => {
        if (mobileNav.classList.contains('active') && 
            !e.target.closest('.mobile-nav') && 
            !e.target.closest('.nav-mobile')) {
            hamburger.classList.remove('active');
            mobileNav.classList.remove('active');
            const spans = hamburger.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    });
};

export default initMobileMenu;