(() => {
  'use strict';

  const init = () => {
    const cards = Array.from(document.querySelectorAll('a.video-thumb'));
    if (!cards.length) return;

    const videos = [];

    cards.forEach((card) => {
      const video = card.querySelector('video');
      if (!video) return;

      // Lightbox2 is for images; MP4 links must use the native video player instead.
      card.removeAttribute('data-lightbox');
      card.removeAttribute('href');
      card.setAttribute('role', 'group');
      card.setAttribute('aria-label', video.getAttribute('aria-label') || 'Video');

      video.controls = true;
      video.playsInline = true;
      video.preload = 'metadata';
      videos.push(video);

      const setPlaying = (playing) => {
        card.classList.toggle('is-playing', playing);
      };

      video.addEventListener('play', () => {
        videos.forEach((other) => {
          if (other !== video && !other.paused) {
            other.pause();
          }
        });
        setPlaying(true);
      });

      video.addEventListener('pause', () => setPlaying(false));
      video.addEventListener('ended', () => setPlaying(false));

      const playIcon = card.querySelector('.play-icon');
      if (playIcon) {
        playIcon.setAttribute('aria-hidden', 'true');
      }

      card.addEventListener('click', (event) => {
        // Clicks on the actual video/controls are handled by the browser.
        if (event.target === video) return;

        event.preventDefault();
        event.stopPropagation();

        if (video.paused || video.ended) {
          const playPromise = video.play();
          if (playPromise && typeof playPromise.catch === 'function') {
            playPromise.catch(() => {});
          }
        } else {
          video.pause();
        }
      });
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
