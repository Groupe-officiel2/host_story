document.addEventListener('DOMContentLoaded', () => {

    const openBtn = document.getElementById('openCreateServer');
    const form = document.getElementById('createServerForm');

    if (openBtn && form) {
        openBtn.addEventListener('click', () => {
            form.classList.toggle('hidden');
        });
    }

    const slotsInput = document.getElementById('slots');
    const priceSpan = document.getElementById('price');

    if (slotsInput && priceSpan) {
        slotsInput.addEventListener('input', () => {
            const slots = parseInt(slotsInput.value) || 0;
            const price = slots * 2;
            priceSpan.innerText = price + ' €';
        });
    }

});
