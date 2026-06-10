document.addEventListener('DOMContentLoaded', () => {

    const token = document.querySelector('meta[name="csrf-token"]').content;

    // ===== SERVERS =====
    const buttons = document.querySelectorAll('.start-btn');

    buttons.forEach(btn => {
        updateServerStatus(btn, token);

        btn.addEventListener('click', async () => {
            btn.innerHTML = '<img src="/images/hourglass.svg" width="10" height="10" alt="Loading...">';

            const response = await fetch('/toggle-server', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ name: btn.dataset.name })
            });

            const result = await response.text();

            if (result.includes("started")) {
                btn.textContent = "■";
                btn.style.color = "red";
            } else {
                btn.textContent = "▶";
                btn.style.color = "green";
            }
        });
    });

    // ===== MODAL =====
    const openBtn = document.getElementById('openCreateServer');
    const modal = document.getElementById('createServerModal');
    const closeBtn = modal?.querySelector('.close');

    if (openBtn && modal && closeBtn) {
        openBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    // ===== PRIX =====
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

async function updateServerStatus(btn, token) {
    const response = await fetch('/server-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ name: btn.dataset.name })
    });

    const status = await response.text();

    if (status.includes("running")) {
        btn.textContent = "■";
        btn.style.color = "red";
    } else {
        btn.textContent = "▶";
        btn.style.color = "green";
    }
}