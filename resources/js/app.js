//
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('global-delete-modal');
    const deleteForm = document.getElementById('global-delete-form');
    const targetName = document.getElementById('delete-target-name');
    const confirmButton = document.getElementById('confirm-delete-button');

    if (!modal || !deleteForm || !targetName || !confirmButton) {
        return;
    }

    const defaultButtonText = 'Ya, Hapus Data';

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-delete-url]');

        if (trigger) {
            event.preventDefault();

            deleteForm.action = trigger.dataset.deleteUrl;
            targetName.textContent = trigger.dataset.deleteName || 'data ini';

            confirmButton.disabled = false;
            confirmButton.textContent = defaultButtonText;

            modal.showModal();
            return;
        }

        if (event.target.closest('[data-delete-cancel], [data-delete-close]')) {
            modal.close();
        }
    });

    confirmButton.addEventListener('click', () => {
        if (!deleteForm.action) return;

        confirmButton.disabled = true;
        confirmButton.textContent = 'Menghapus...';

        deleteForm.requestSubmit();
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.close();
        }
    });
});
