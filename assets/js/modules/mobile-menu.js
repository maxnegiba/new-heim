// assets/js/modules/mobile-menu.js
const initMobileMenu = () => {
    const hamburger = document.querySelector('.hamburger');
    if (!hamburger) return;
    const mobileNav = document.querySelector('.nav-mobile');

    // Toggle menu on hamburger click
    hamburger.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent event from bubbling to document
        const isActive = mobileNav.classList.contains('active');
        hamburger.classList.toggle('active', !isActive);
        mobileNav.classList.toggle('active', !isActive);

        // Optional: Animate hamburger lines (if CSS doesn't handle it)
        const spans = hamburger.querySelectorAll('span');
        if (isActive) {
             // Revert to hamburger icon
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        } else {
            // Animate to X icon (example)
            spans[0].style.transform = 'translateY(8px) rotate(45deg)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'translateY(-8px) rotate(-45deg)';
        }
        // Update aria-expanded
        hamburger.setAttribute('aria-expanded', !isActive);
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
         // Check if menu is active and click is outside both hamburger and nav
        if (mobileNav.classList.contains('active') &&
            !e.target.closest('.nav-mobile') &&
            !e.target.closest('.hamburger')) {
            
            hamburger.classList.remove('active');
            mobileNav.classList.remove('active');
             // Revert hamburger icon
            const spans = hamburger.querySelectorAll('span');
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
             // Update aria-expanded
            hamburger.setAttribute('aria-expanded', 'false');
        }
    });
};

// Call the function when the script loads
// Wrap in DOMContentLoaded check if needed, but since this script is deferred,
// it should run after the DOM is parsed.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMobileMenu);
} else {
     // DOM is already ready
    initMobileMenu();
}

// Remove default export if not using module imports elsewhere
// export default initMobileMenu;