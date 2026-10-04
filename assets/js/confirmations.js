document.addEventListener('click', (event) => {
    const element = event.target.closest('[data-confirm]');

    if (element && !window.confirm(element.dataset.confirm)) {
        event.preventDefault();
    }
});