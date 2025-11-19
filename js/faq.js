// FAQ two-level accordion functionality
document.addEventListener('DOMContentLoaded', function() {
  const faqSection = document.querySelector('.faq');
  if (!faqSection) return;

  const faqRubrik = faqSection.querySelector('.faq__rubrik');
  const faqLista = faqSection.querySelector('.faq__lista');
  const faqItems = faqSection.querySelectorAll('.faq__item');

  // Level 1: Toggle entire FAQ section
  if (faqRubrik && faqLista) {
    faqRubrik.style.cursor = 'pointer';
    faqRubrik.addEventListener('click', () => {
      faqSection.classList.toggle('faq--collapsed');
    });
  }

  // Level 2: Toggle individual FAQ items
  faqItems.forEach(item => {
    const question = item.querySelector('.faq__fraga');

    question.addEventListener('click', (e) => {
      e.stopPropagation(); // Prevent triggering section collapse
      const isOpen = item.classList.contains('faq__item--open');

      // Close all other items
      faqItems.forEach(otherItem => {
        otherItem.classList.remove('faq__item--open');
      });

      // Toggle current item
      if (!isOpen) {
        item.classList.add('faq__item--open');
      }
    });
  });
});
