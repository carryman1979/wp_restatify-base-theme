(function () {
  var STORAGE_KEY = 'restatify_theme_mode';
  var root = document.documentElement;
  var mediaQuery = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

  function getSystemTheme() {
    return mediaQuery && mediaQuery.matches ? 'dark' : 'light';
  }

  function getSavedMode() {
    try {
      var value = window.localStorage.getItem(STORAGE_KEY);
      if (value === 'light' || value === 'dark' || value === 'auto') {
        return value;
      }
    } catch (error) {
      // Ignore storage read errors.
    }

    return 'auto';
  }

  function getIconClassForMode(mode) {
    if (mode === 'light') {
      return 'imind-sun';
    }

    if (mode === 'dark') {
      return 'imind-half-moon';
    }

    return 'imind-auto-flash';
  }

  function updateCurrentIcons(mode) {
    var currentIcons = document.querySelectorAll('[data-theme-current-icon]');
    var iconClass = getIconClassForMode(mode);

    currentIcons.forEach(function (iconNode) {
      iconNode.className = 'restatify-theme-current-icon imind ' + iconClass;
    });
  }

  function updateOptionStates(mode) {
    var options = document.querySelectorAll('[data-theme-choice]');
    options.forEach(function (option) {
      var isActive = option.getAttribute('data-theme-choice') === mode;
      option.classList.toggle('is-active', isActive);
      option.setAttribute('aria-pressed', isActive ? 'true' : 'false');
      option.setAttribute('aria-checked', isActive ? 'true' : 'false');
    });
  }

  function closeThemeMenus() {
    var switches = document.querySelectorAll('.restatify-theme-switch[open]');
    switches.forEach(function (switchNode) {
      switchNode.removeAttribute('open');
    });
  }

  function setThemeMode(mode, persist) {
    var normalized = mode === 'light' || mode === 'dark' ? mode : 'auto';
    var effectiveTheme = normalized === 'auto' ? getSystemTheme() : normalized;

    root.setAttribute('data-rs-theme-mode', normalized);
    root.setAttribute('data-rs-theme', effectiveTheme);

    updateOptionStates(normalized);
    updateCurrentIcons(normalized);

    if (persist) {
      try {
        window.localStorage.setItem(STORAGE_KEY, normalized);
      } catch (error) {
        // Ignore storage write errors.
      }
    }
  }

  function initThemeSwitcher() {
    var options = document.querySelectorAll('[data-theme-choice]');
    if (!options.length) {
      return;
    }

    setThemeMode(getSavedMode(), false);

    options.forEach(function (option) {
      option.addEventListener('click', function () {
        var mode = option.getAttribute('data-theme-choice') || 'auto';
        setThemeMode(mode, true);
        closeThemeMenus();
      });
    });

    document.addEventListener('click', function (event) {
      if (event.target.closest('.restatify-theme-switch')) {
        return;
      }

      closeThemeMenus();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closeThemeMenus();
      }
    });

    if (mediaQuery) {
      var onMediaChange = function () {
        var mode = getSavedMode();
        if (mode === 'auto') {
          setThemeMode('auto', false);
        }
      };

      if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', onMediaChange);
      } else if (typeof mediaQuery.addListener === 'function') {
        mediaQuery.addListener(onMediaChange);
      }
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeSwitcher, { once: true });
  } else {
    initThemeSwitcher();
  }
})();
