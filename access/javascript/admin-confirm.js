(function () {
  const triggers = document.querySelectorAll('[data-confirm-action]');
  if (!triggers.length) {
    return;
  }

  const overlay = document.createElement('div');
  overlay.className = 'fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4';
  overlay.innerHTML = `
    <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-xl">
      <h2 class="text-lg font-bold text-slate-900" data-confirm-title>Confirm action</h2>
      <p class="mt-2 text-sm text-slate-600" data-confirm-message>Are you sure?</p>
      <div class="mt-5 flex justify-end gap-2">
        <button type="button" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50" data-confirm-cancel>No</button>
        <button type="button" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700" data-confirm-accept>Yes</button>
      </div>
    </div>
  `;

  document.body.appendChild(overlay);

  const titleNode = overlay.querySelector('[data-confirm-title]');
  const messageNode = overlay.querySelector('[data-confirm-message]');
  const cancelButton = overlay.querySelector('[data-confirm-cancel]');
  const acceptButton = overlay.querySelector('[data-confirm-accept]');

  let pendingAction = null;

  function closeModal() {
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    pendingAction = null;
  }

  function openModal(action) {
    pendingAction = action;

    const confirmTitle = action.getAttribute('data-confirm-title') || 'Confirm action';
    const confirmMessage = action.getAttribute('data-confirm-message') || 'Are you sure you want to continue?';
    const yesText = action.getAttribute('data-confirm-yes') || 'Yes';
    const noText = action.getAttribute('data-confirm-no') || 'No';

    titleNode.textContent = confirmTitle;
    messageNode.textContent = confirmMessage;
    acceptButton.textContent = yesText;
    cancelButton.textContent = noText;

    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
  }

  triggers.forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      openModal(trigger);
    });
  });

  cancelButton.addEventListener('click', closeModal);
  overlay.addEventListener('click', (event) => {
    if (event.target === overlay) {
      closeModal();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeModal();
    }
  });

  acceptButton.addEventListener('click', () => {
    if (!pendingAction) {
      return;
    }

    const href = pendingAction.getAttribute('href');
    if (href) {
      window.location.href = href;
      return;
    }

    const formId = pendingAction.getAttribute('data-confirm-form-id');
    if (formId) {
      const targetForm = document.getElementById(formId);
      if (targetForm) {
        targetForm.submit();
      }
    }

    closeModal();
  });
})();
