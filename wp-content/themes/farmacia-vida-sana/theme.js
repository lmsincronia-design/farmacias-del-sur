document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.fv-reveal');
  if (!('IntersectionObserver' in window)) {
    items.forEach((item) => item.classList.add('is-visible'));
    return;
  }
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  items.forEach((item) => observer.observe(item));

  const slider = document.querySelector('[data-fv-slider]');
  if (!slider) return;

  const slides = [...slider.querySelectorAll('.fv-showcase__slide')];
  const dots = [...slider.querySelectorAll('.fv-showcase__dots button')];
  const previous = slider.querySelector('.fv-showcase__arrow--prev');
  const next = slider.querySelector('.fv-showcase__arrow--next');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let index = 0;
  let timer;

  const show = (newIndex) => {
    index = (newIndex + slides.length) % slides.length;
    slides.forEach((slide, position) => {
      const active = position === index;
      slide.classList.toggle('is-active', active);
      slide.setAttribute('aria-hidden', String(!active));
    });
    dots.forEach((dot, position) => {
      const active = position === index;
      dot.classList.toggle('is-active', active);
      active ? dot.setAttribute('aria-current', 'true') : dot.removeAttribute('aria-current');
    });
  };
  const stop = () => window.clearInterval(timer);
  const start = () => {
    stop();
    if (!reducedMotion) timer = window.setInterval(() => show(index + 1), 5500);
  };

  previous.addEventListener('click', () => { show(index - 1); start(); });
  next.addEventListener('click', () => { show(index + 1); start(); });
  dots.forEach((dot, position) => dot.addEventListener('click', () => { show(position); start(); }));
  slider.addEventListener('mouseenter', stop);
  slider.addEventListener('mouseleave', start);
  slider.addEventListener('focusin', stop);
  slider.addEventListener('focusout', start);
  start();

  const productStrip = document.querySelector('[data-fv-product-strip]');
  if (!productStrip) return;

  const stripViewport = productStrip.querySelector('.fv-product-strip__viewport');
  const stripTrack = productStrip.querySelector('.fv-product-strip__track');
  const stripCards = [...productStrip.querySelectorAll('.fv-product-strip__card')];
  const stripPrevious = productStrip.querySelector('.fv-product-strip__arrow--prev');
  const stripNext = productStrip.querySelector('.fv-product-strip__arrow--next');
  let stripIndex = 0;
  let stripTimer;

  const stripMetrics = () => {
    const firstCard = stripCards[0];
    if (!firstCard) return { step: 0, maximum: 0 };
    const gap = parseFloat(getComputedStyle(stripTrack).gap) || 0;
    const step = firstCard.getBoundingClientRect().width + gap;
    const visible = Math.max(1, Math.round((stripViewport.clientWidth + gap) / step));
    return { step, maximum: Math.max(0, stripCards.length - visible) };
  };
  const moveStrip = (newIndex) => {
    const { step, maximum } = stripMetrics();
    stripIndex = newIndex > maximum ? 0 : newIndex < 0 ? maximum : newIndex;
    stripTrack.style.transform = `translateX(-${stripIndex * step}px)`;
  };
  const stopStrip = () => window.clearInterval(stripTimer);
  const startStrip = () => {
    stopStrip();
    if (!reducedMotion) stripTimer = window.setInterval(() => moveStrip(stripIndex + 1), 3600);
  };

  stripPrevious.addEventListener('click', () => { moveStrip(stripIndex - 1); startStrip(); });
  stripNext.addEventListener('click', () => { moveStrip(stripIndex + 1); startStrip(); });
  productStrip.addEventListener('mouseenter', stopStrip);
  productStrip.addEventListener('mouseleave', startStrip);
  productStrip.addEventListener('focusin', stopStrip);
  productStrip.addEventListener('focusout', startStrip);
  window.addEventListener('resize', () => moveStrip(0));
  startStrip();
});
