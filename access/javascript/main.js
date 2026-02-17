document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  if (toggle && mobileMenu) {
    toggle.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
  }

  const filterButtons = document.querySelectorAll('[data-filter]');
  const portfolioItems = document.querySelectorAll('[data-item]');
  filterButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      filterButtons.forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      const category = btn.dataset.filter;
      portfolioItems.forEach((item) => {
        const show = category === 'all' || item.dataset.item === category;
        item.classList.toggle('hidden', !show);
      });
    });
  });

  const slides = Array.from(document.querySelectorAll('[data-testimonial]'));
  const dotsWrap = document.querySelector('[data-slider-dots]');
  if (slides.length && dotsWrap) {
    let current = 0;
    const dots = slides.map((_, idx) => {
      const dot = document.createElement('button');
      dot.className = `dot ${idx === 0 ? 'active' : ''}`;
      dot.type = 'button';
      dot.addEventListener('click', () => showSlide(idx));
      dotsWrap.appendChild(dot);
      return dot;
    });

    const showSlide = (idx) => {
      slides.forEach((slide, i) => slide.classList.toggle('hidden', i !== idx));
      dots.forEach((dot, i) => dot.classList.toggle('active', i === idx));
      current = idx;
    };

    if (slides.length > 1) {
      setInterval(() => showSlide((current + 1) % slides.length), 4000);
    }
  }
});
