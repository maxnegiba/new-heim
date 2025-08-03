// assets/js/modules/contact-form.js
export const initContactForm = () => {
  const form = document.getElementById('ajaxForm');
  if (!form) return;

  const msg     = form.querySelector('.form-msg');
  const spinner = form.querySelector('.fa-spinner');

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    msg.textContent = '';
    msg.className   = 'form-msg';
    spinner.style.display = 'inline-block';

    // creează FormData ÎNAINTE de captcha
    const data = new FormData(form);

    grecaptcha.ready(() => {
      grecaptcha.execute('6Lfd6IwrAAAAAAq9AnqQDYHssazEzrcAQaCfQLDj', { action: 'submit' })
        .then(token => {
          data.set('recaptcha_response', token); // adaugă / suprascrie token-ul
          return fetch('php/send-mail.php', {
            method: 'POST',
            body: data
          });
        })
        .then(r => {
          if (!r.ok) throw new Error(`HTTP ${r.status}`);
          return r.json();
        })
        .then(res => {
          spinner.style.display = 'none';
          if (res.success) {
            msg.textContent = 'Vielen Dank! Wir melden uns schnellstmöglich.';
            msg.className   = 'form-msg success';
            form.reset();
          } else {
            msg.textContent = res.error || 'Fehler beim Senden.';
            msg.className   = 'form-msg error';
          }
        })
        .catch(err => {
          spinner.style.display = 'none';
          console.error(err);
          msg.textContent = 'Netzwerk-/Serverfehler. Bitte später erneut versuchen.';
          msg.className   = 'form-msg error';
        });
    });
  });
};