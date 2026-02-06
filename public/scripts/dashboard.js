function setupToggle(selector) {
    document.querySelectorAll(selector).forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll(selector).forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
}

setupToggle('.mood');
setupToggle('.focus');

document.getElementById('saveEntry').addEventListener('click', async (e) => {
    e.preventDefault();
    const energy = document.getElementById('energy').value;
    const mood = document.querySelector('.mood.active').dataset.value;
    const focus = document.querySelector('.focus.active').dataset.value;
    const note = document.getElementById('note').value;

    const response = await fetch('/save-entry', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ energy, mood, focus, note })
    });

    if (response.ok) {
        window.location.href = '/history';
    } else {
        alert('Error while saving entry');
    }
});
