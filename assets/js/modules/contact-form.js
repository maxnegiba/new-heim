export const initContactForm = () => {
  const form = document.getElementById('ajaxForm');
  if (!form || form.dataset.initialized === 'true') return;
  form.dataset.initialized = 'true';

  const message = form.querySelector('.form-msg');
  const submit = form.querySelector('button[type="submit"]');
  const spinner = form.querySelector('.fa-spinner');

  const showMessage = (text, type) => {
    if (!message) return;
    message.textContent = text;
    message.className = `form-msg ${type}`;
  };

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (!form.reportValidity()) return;

    if (message) {
      message.textContent = '';
      message.className = 'form-msg';
    }
    if (submit) submit.disabled = true;
    if (spinner) spinner.style.display = 'inline-block';

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      let payload = {};
      try {
        payload = await response.json();
      } catch {
        throw new Error('Ungültige Serverantwort');
      }

      if (!response.ok || !payload.success) {
        throw new Error(payload.error || 'Die Anfrage konnte nicht gesendet werden.');
      }

      showMessage('Vielen Dank! Ihre Anfrage wurde gesendet. Wir melden uns schnellstmöglich.', 'success');
      form.reset();
    } catch (error) {
      console.error(error);
      showMessage(error.message || 'Beim Senden ist ein Fehler aufgetreten. Bitte rufen Sie uns an oder nutzen Sie WhatsApp.', 'error');
    } finally {
      if (submit) submit.disabled = false;
      if (spinner) spinner.style.display = 'none';
    }
  });
};
