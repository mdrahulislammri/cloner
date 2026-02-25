document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const mobileMenu = document.querySelector('[data-mobile-menu]');
  const menuIcon = document.querySelector('[data-menu-icon]');
  const mobileBackdrop = document.querySelector('[data-mobile-backdrop]');

  const closeMobileMenu = () => {
    if (!mobileMenu || !toggle) return;
    mobileMenu.classList.add('hidden');
    mobileMenu.classList.remove('open');
    toggle.setAttribute('aria-expanded', 'false');
    if (menuIcon) menuIcon.textContent = '☰';
    if (mobileBackdrop) mobileBackdrop.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  };

  const openMobileMenu = () => {
    if (!mobileMenu || !toggle) return;
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('open');
    toggle.setAttribute('aria-expanded', 'true');
    if (menuIcon) menuIcon.textContent = '✕';
    if (mobileBackdrop) mobileBackdrop.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
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

    if (mobileBackdrop) {
      mobileBackdrop.addEventListener('click', closeMobileMenu);
    }
  }




  const toastStack = document.querySelector('[data-toast-stack]');
  const toasts = Array.from(document.querySelectorAll('[data-toast]'));

  if (toastStack && toasts.length) {
    const toastDuration = Number.parseInt(toastStack.dataset.toastDuration || '4000', 10);
    const safeDuration = Number.isFinite(toastDuration) ? Math.min(Math.max(toastDuration, 1000), 15000) : 4000;

    const setupToast = (toast, index) => {
      const closeBtn = toast.querySelector('[data-toast-close]');
      const progressBar = toast.querySelector('[data-toast-progress]');
      let timer;
      let startedAt = 0;
      let remaining = safeDuration + (index * 160);

      const removeToast = () => {
        toast.classList.add('toast-leave');
        window.setTimeout(() => {
          toast.remove();
          if (!toastStack.querySelector('[data-toast]')) {
            toastStack.remove();
          }
        }, 220);
      };

      const startTimer = () => {
        window.clearTimeout(timer);
        startedAt = Date.now();
        if (progressBar) {
          progressBar.style.transitionDuration = `${remaining}ms`;
          progressBar.style.transform = 'scaleX(0)';
        }
        timer = window.setTimeout(removeToast, remaining);
      };

      const pauseTimer = () => {
        window.clearTimeout(timer);
        remaining -= Date.now() - startedAt;
        if (remaining < 150) remaining = 150;
        if (progressBar) {
          const ratio = remaining / (safeDuration + (index * 160));
          progressBar.style.transitionDuration = '0ms';
          progressBar.style.transform = `scaleX(${Math.max(0, Math.min(1, ratio))})`;
        }
      };

      if (closeBtn) {
        closeBtn.addEventListener('click', () => {
          window.clearTimeout(timer);
          removeToast();
        });
      }

      toast.addEventListener('mouseenter', pauseTimer);
      toast.addEventListener('mouseleave', startTimer);
      startTimer();
    };

    toasts.forEach((toast, index) => setupToast(toast, index));

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') {
        const firstToast = toastStack.querySelector('[data-toast]');
        const closeBtn = firstToast?.querySelector('[data-toast-close]');
        if (closeBtn) closeBtn.click();
      }
    });
  }

  const chatWidget = document.querySelector('[data-chat-widget]');
  const chatToggle = document.querySelector('[data-chat-toggle]');
  const chatMenu = document.querySelector('[data-chat-menu]');
  const chatIcon = document.querySelector('[data-chat-icon]');

  if (chatWidget && chatToggle && chatMenu) {
    const setChatToggleIcon = (iconClass) => {
      if (!chatIcon) return;
      chatIcon.innerHTML = `<i class="${iconClass}" aria-hidden="true"></i>`;
    };

    const closeChatMenu = () => {
      chatMenu.classList.add('hidden');
      chatToggle.setAttribute('aria-expanded', 'false');
      setChatToggleIcon('fa-solid fa-comments');
    };

    const openChatMenu = () => {
      chatMenu.classList.remove('hidden');
      chatToggle.setAttribute('aria-expanded', 'true');
      setChatToggleIcon('fa-solid fa-xmark');
    };

    chatToggle.addEventListener('click', () => {
      if (chatMenu.classList.contains('hidden')) {
        openChatMenu();
      } else {
        closeChatMenu();
      }
    });

    document.addEventListener('click', (event) => {
      if (!chatWidget.contains(event.target)) {
        closeChatMenu();
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
