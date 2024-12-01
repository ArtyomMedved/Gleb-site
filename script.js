// Toggle content visibility on click
document.querySelectorAll('.advantage-item').forEach(item => {
    item.addEventListener('click', () => {
      item.classList.toggle('open');
    });
  });

