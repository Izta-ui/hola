<script>
    const track = document.querySelector('.carrusel-track');
    const btnPrev = document.querySelector('.carrusel-btn.prev');
    const btnNext = document.querySelector('.carrusel-btn.next');

    let scrollAmount = 0;
    const scrollStep = 300;

  btnNext.addEventListener('click', () => {scrollAmount += scrollStep};
    track.style.transform = `translateX(-${scrollAmount}px)`;
  });

  btnPrev.addEventListener('click', () => {scrollAmount = Math.max(0, scrollAmount - scrollStep)};
    track.style.transform = `translateX(-${scrollAmount}px)`;
  });
</script>;
