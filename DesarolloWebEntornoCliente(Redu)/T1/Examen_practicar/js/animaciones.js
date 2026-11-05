// Si el usuario prefiere sin animaciones, no hacemos nada
const sinAnimaciones = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!sinAnimaciones) {

  /* ---------- 1. TÍTULO DEL HERO: letra a letra con efecto elástico ---------- */
  const titulo = document.querySelector('.hero-titulo');
  titulo.innerHTML = titulo.textContent
    .split('')
    .map(l => l === ' ' ? ' ' : `<span class="letra">${l}</span>`)
    .join('');

  anime.timeline({ easing: 'easeOutElastic(1, .6)' })
    .add({
      targets: '.hero-titulo .letra',
      translateY: [-80, 0],
      rotate: [() => anime.random(-90, 90), 0],
      opacity: [0, 1],
      delay: anime.stagger(40),
      duration: 1400
    })
    .add({
      targets: ['.hero .etiqueta', '.hero-texto', '.hero-botones .boton'],
      translateY: [30, 0],
      opacity: [0, 1],
      delay: anime.stagger(120),
      duration: 700,
      easing: 'easeOutCubic'
    }, '-=900')
    .add({
      targets: '.hero-imagen',
      scale: [0.8, 1],
      rotate: [-4, 0],
      opacity: [0, 1],
      duration: 1200,
      easing: 'easeOutExpo'
    }, '-=1200');

  /* ---------- 2. Preparar elementos para animar al hacer scroll ---------- */
  document.querySelectorAll('.titulo-seccion, .descripcion-seccion')
    .forEach(el => el.classList.add('anima-titulo'));
  document.querySelectorAll('.tarjeta').forEach(el => el.classList.add('anima-tarjeta'));
  document.querySelectorAll('.pie .row > div, .pie-copyright')
    .forEach(el => el.classList.add('anima-texto'));

  /* ---------- 3. Animar cuando entran en pantalla (IntersectionObserver) ---------- */
  const observador = new IntersectionObserver((entradas) => {
    entradas.forEach(entrada => {
      if (!entrada.isIntersecting) return;
      const el = entrada.target;
      observador.unobserve(el);               // solo una vez

      if (el.classList.contains('anima-titulo')) {
        anime({
          targets: el,
          translateY: [50, 0],
          scaleX: [0.6, 1],
          opacity: [0, 1],
          duration: 900,
          easing: 'easeOutExpo'
        });

      } else if (el.classList.contains('anima-tarjeta')) {
        // Las tarjetas de una misma fila entran escalonadas y con giro 3D
        const indice = [...el.parentElement.parentElement.children]
          .indexOf(el.parentElement);
        anime({
          targets: el,
          translateY: [80, 0],
          rotateX: [-35, 0],
          scale: [0.85, 1],
          opacity: [0, 1],
          delay: (indice % 3) * 150,
          duration: 1100,
          easing: 'easeOutElastic(1, .7)'
        });

      } else {
        anime({
          targets: el,
          translateY: [30, 0],
          opacity: [0, 1],
          duration: 800,
          easing: 'easeOutCubic'
        });
      }

      // Efecto extra: el precio cuenta desde 0 hasta su valor
      const precio = el.querySelector('.plan-precio');
      if (precio && !precio.dataset.hecho) {
        precio.dataset.hecho = '1';
        const periodo = precio.querySelector('.plan-periodo');
        const meta = parseInt(precio.textContent, 10);
        const contador = { valor: 0 };
        anime({
          targets: contador,
          valor: meta,
          round: 1,
          duration: 1400,
          easing: 'easeOutExpo',
          update: () => {
            precio.firstChild.textContent = contador.valor + ' €';
          }
        });
      }
    });
  }, { threshold: 0.2 });

  document.querySelectorAll('.anima-titulo, .anima-tarjeta, .anima-texto')
    .forEach(el => observador.observe(el));

  /* ---------- 4. Efectos continuos con el scroll ---------- */
  const progreso = document.getElementById('progreso');
  const imagenHero = document.querySelector('.hero-imagen');

  window.addEventListener('scroll', () => {
    const total = document.documentElement.scrollHeight - window.innerHeight;
    const porcentaje = window.scrollY / total;
    progreso.style.transform = `scaleX(${porcentaje})`;      // barra de progreso
    imagenHero.style.translate = `0 ${window.scrollY * 0.15}px`; // parallax suave
  }, { passive: true });

  /* ---------- 5. Iconos de ventajas flotando sin parar ---------- */
  anime({
    targets: '.icono-ventaja',
    translateY: [-6, 6],
    rotate: [-4, 4],
    direction: 'alternate',
    loop: true,
    easing: 'easeInOutSine',
    duration: 1800,
    delay: anime.stagger(300)
  });
}