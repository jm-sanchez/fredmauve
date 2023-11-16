let scrollPosition = window.scrollY;
let navbar = document.getElementById('navbar')
navbar.scrollTop
console.log(navbar.scrollTop)

// console.log(document)
window.addEventListener('scroll', function() {
    // Récupération de la position de défilement verticale
    // let scrollPosition = window.scrollY;
    // let navbar = document.getElementsByTagName('nav')
    // let logo2 = document.querySelector('#logo2')
    console.log(navbar)

    // Ajout de la classe une fois le défilement atteint 133px du haut de la page
    if (navbar.scrollTop > 133) {
        // logo2.classList.remove('d-none')
        navbar.classList.add('fixed-top');
    } else {
        // logo2.classList.add('d-none')
        navbar.classList.remove('fixed-top');
    }
  });