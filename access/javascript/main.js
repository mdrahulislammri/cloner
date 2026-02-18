document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const menuIcon = document.querySelector('[data-menu-icon]');

  const closeMobileMenu = () => {
    if (!mobileMenu || !toggle) return;
    mobileMenu.classList.add('hidden');
    mobileMenu.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    if (menuIcon) menuIcon.textContent = '☰';
  };

  const openMobileMenu = () => {
    if (!mobileMenu || !toggle) return;
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('open');
    toggle.setAttribute('aria-expanded', 'true');
    if (menuIcon) menuIcon.textContent = '✕';
  };

  if (toggle && mobileMenu) {
    toggle.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.contains('open');
      if (isOpen) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => closeMobileMenu());
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 768) {
        closeMobileMenu();
      }
    });
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
  const prevBtn = document.querySelector('[data-prev]');
  const nextBtn = document.querySelector('[data-next]');

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

    if (prevBtn) {
      prevBtn.addEventListener('click', () => showSlide((current - 1 + slides.length) % slides.length));
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', () => showSlide((current + 1) % slides.length));
    }

    if (slides.length > 1) {
      setInterval(() => showSlide((current + 1) % slides.length), 4500);
    }
  }
});
