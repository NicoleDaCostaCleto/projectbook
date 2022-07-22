const body=document.querySelector('.jsbody');
// j'affecte la class de mon button à nightToggleBtn
const nightToggleBtn = document.querySelector('.js-night-toggle');

// je controlle si le dark mode est activé dans le local storage,
// si oui je l'active avec le CSS avec la class night-activated
if (localStorage.getItem('nightActivated') === 'true') {
    body.classList.add('night-activated');
    // Je change l'affichage de mon button
    nightToggleBtn.innerText = "light Mode";
}

nightToggleBtn.addEventListener('click', function() {
    // si le mode dark est activé, je le désactive
    if (body.classList.contains('night-activated')) {
        body.classList.remove('night-activated');
        localStorage.removeItem('nightActivated');
        nightToggleBtn.innerText = "night Mode";
    // si le mode dark n'est pas activé, je l'active
    } else {
        body.classList.add('night-activated');
        localStorage.setItem('nightActivated', "true");
        nightToggleBtn.innerText = "light Mode";
    }
});