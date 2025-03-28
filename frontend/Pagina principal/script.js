// Mostrar la cabecera al hacer scroll
window.addEventListener('scroll', function() {
    const header = document.querySelector('.header');
    if (window.scrollY > 50) {  // Si el scroll ha pasado los 50px
      header.classList.add('show');  // Añade la clase "show" a la cabecera
    } else {
      header.classList.remove('show');  // Si no, quita la clase "show"
    }
  });
  