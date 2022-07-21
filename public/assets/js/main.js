const nightToggleBtn = document.querySelector('.js-night-toggle');

nightToggleBtn.addEventListener('click', function() {
    const body=document.querySelector('.jsbody');

    if (body.classList.contains('night-activated')) {
        body.classList.remove('night-activated');
    }else {
        body.classList.add('night-activated');
    }});