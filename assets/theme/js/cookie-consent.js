(function () {
  function updateBodyState(state) {
    var classes = ['pending', 'accepted', 'rejected'];
    classes.forEach(function (name) {
      document.body.classList.remove('restatify-consent-' + name);
    });

    document.body.classList.add('restatify-consent-' + state);
  }

  function setCookie(name, value, maxAgeSeconds) {
    var cookie = name + '=' + value + '; path=/; SameSite=Lax';
    if (typeof maxAgeSeconds === 'number') {
      cookie += '; max-age=' + String(maxAgeSeconds);
    }
    document.cookie = cookie;
  }

  function deleteCookieEverywhere(name) {
    var expires = 'Thu, 01 Jan 1970 00:00:00 GMT';
    var host = window.location.hostname || '';
    var hostParts = host.split('.').filter(Boolean);
    var domains = [''];

    for (var i = 0; i < hostParts.length; i += 1) {
      var domain = hostParts.slice(i).join('.');
      if (domain) {
        domains.push(domain);
        domains.push('.' + domain);
      }
    }

    var pathCandidates = ['/'];
    var segments = window.location.pathname.split('/').filter(Boolean);
    var currentPath = '';
    segments.forEach(function (segment) {
      currentPath += '/' + segment;
      pathCandidates.push(currentPath);
      pathCandidates.push(currentPath + '/');
    });

    var seen = {};
    domains.forEach(function (domain) {
      pathCandidates.forEach(function (path) {
        var key = name + '|' + domain + '|' + path;
        if (seen[key]) {
          return;
        }
        seen[key] = true;

        var cookie = name + '=; expires=' + expires + '; path=' + path + '; SameSite=Lax';
        if (domain) {
          cookie += '; domain=' + domain;
        }
        document.cookie = cookie;
      });
    });
  }

  function clearAllAccessibleCookies() {
    var raw = document.cookie || '';
    if (!raw) {
      return;
    }

    raw.split(';').forEach(function (entry) {
      var name = entry.split('=')[0].trim();
      if (!name) {
        return;
      }
      deleteCookieEverywhere(name);
    });
  }

  function setConsent(state) {
    if (state === 'rejected') {
      clearAllAccessibleCookies();
    }

    setCookie('restatify_cookie_consent', state, 31536000);

    if (state === 'accepted') {
      setCookie('cookiesDirective', '1', 31536000);
    } else {
      deleteCookieEverywhere('cookiesDirective');
    }
  }

  function setBannerVisibility(visible) {
    var banner = document.getElementById('restatify-cookie-banner');
    var backdrop = document.getElementById('restatify-cookie-backdrop');
    var reopenButtons = document.querySelectorAll('[data-cookie-open="true"]');

    if (banner) {
      if (visible) {
        banner.hidden = false;
        banner.classList.remove('is-hidden');
      } else {
        banner.hidden = true;
        banner.classList.add('is-hidden');
      }
    }

    if (backdrop) {
      if (visible) {
        backdrop.classList.remove('is-hidden');
        backdrop.setAttribute('aria-hidden', 'false');
      } else {
        backdrop.classList.add('is-hidden');
        backdrop.setAttribute('aria-hidden', 'true');
      }
    }

    reopenButtons.forEach(function (reopenButton) {
      if (visible) {
        reopenButton.hidden = true;
        reopenButton.classList.add('is-hidden');
      } else {
        reopenButton.hidden = false;
        reopenButton.classList.remove('is-hidden');
      }
    });
  }

  function handleDecision(state) {
    setConsent(state);
    updateBodyState(state);

    if (window.restatifyCookieConsent && window.restatifyCookieConsent.reloadOnDecision) {
      window.location.reload();
      return;
    }

    setBannerVisibility(false);
  }

  function openBanner() {
    updateBodyState('pending');
    setBannerVisibility(true);
  }

  function init() {
    if (!window.restatifyCookieConsent) {
      return;
    }

    var banner = document.getElementById('restatify-cookie-banner');
    if (banner) {
      banner.querySelectorAll('[data-cookie-action]').forEach(function (button) {
        button.addEventListener('click', function () {
          var action = button.getAttribute('data-cookie-action');
          handleDecision(action === 'accept' ? 'accepted' : 'rejected');
        });
      });
    }

    var reopenButtons = document.querySelectorAll('[data-cookie-open="true"]');
    reopenButtons.forEach(function (reopenButton) {
      reopenButton.addEventListener('click', function () {
        openBanner();
      });
    });

    var initialState = window.restatifyCookieConsent.state || 'pending';
    updateBodyState(initialState);
    setBannerVisibility(initialState === 'pending');
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }
})();
