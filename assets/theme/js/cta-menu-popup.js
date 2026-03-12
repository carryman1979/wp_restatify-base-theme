(function () {
  function setupCtaPopup(container) {
    var toggle = container.querySelector('.restatify-cta-toggle');
    var popup = container.querySelector('.restatify-cta-popup');

    if (!toggle || !popup) {
      return;
    }

    function closePopup() {
      popup.hidden = true;
      container.classList.remove('is-open');
      toggle.setAttribute('aria-expanded', 'false');
    }

    function openPopup() {
      popup.hidden = false;
      container.classList.add('is-open');
      toggle.setAttribute('aria-expanded', 'true');
    }

    toggle.addEventListener('click', function (event) {
      event.preventDefault();
      if (popup.hidden) {
        openPopup();
      } else {
        closePopup();
      }
    });

    document.addEventListener('click', function (event) {
      if (!container.contains(event.target)) {
        closePopup();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closePopup();
      }
    });

    popup.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', closePopup);
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.restatify-cta').forEach(setupCtaPopup);
  });
})();
