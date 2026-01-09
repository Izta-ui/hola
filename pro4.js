let indice = 0;

    function mover(direccion) {
      const carrusel = document.getElementById('carrusel');
      const total = carrusel.children.length;
      indice = (indice + direccion + total) % total;
      carrusel.style.transform = `translateX(-${indice * 100}%)`;
    }