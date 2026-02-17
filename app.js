const menuBtn = document.getElementById('menuBtn');
const mobileMenu = document.getElementById('mobileMenu');

if (menuBtn && mobileMenu) {
  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
}

const filterButtons = document.querySelectorAll('.filter-btn');
const portfolioItems = document.querySelectorAll('.portfolio-item');

filterButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const selected = button.dataset.filter;

    filterButtons.forEach((btn) => {
      btn.classList.remove('bg-brand-700', 'text-white');
      btn.classList.add('bg-white', 'text-brand-700', 'border', 'border-brand-200');
    });

    button.classList.remove('bg-white', 'text-brand-700', 'border', 'border-brand-200');
    button.classList.add('bg-brand-700', 'text-white');

    portfolioItems.forEach((item) => {
      const visible = selected === 'all' || item.dataset.cat === selected;
      item.style.display = visible ? 'block' : 'none';
    });
  });
});
