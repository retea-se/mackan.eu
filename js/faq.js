// FAQ accordion functionality
document.addEventListener('DOMContentLoaded', function() {
  const faqItems = document.querySelectorAll('.faq__item');

  faqItems.forEach(item => {
    const question = item.querySelector('.faq__fraga');

    question.addEventListener('click', () => {
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
