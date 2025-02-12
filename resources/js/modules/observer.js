document.addEventListener('DOMContentLoaded', () => {
  const selector = '[data-observe]';
  const visibleClass = 'is-visible';
  const rootMargin = '0px';
  const threshold = 0; // Set threshold to 0 to trigger as soon as the element enters the viewport

  const observerOptions = {
    root: null,
    rootMargin,
    threshold
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add(visibleClass);
        observer.unobserve(entry.target); // Stop observing after the class is added
      }
    });
  }, observerOptions);

  document.querySelectorAll(selector).forEach(element => {
    observer.observe(element);
  });
});