<?php
/**
 * Dark mode site icon switching output.
 */

function restatify_output_dark_site_icon() {
    $dark_site_icon_id = (int) get_theme_mod('restatify_dark_site_icon');
    $dark_mask_icon_id = (int) get_theme_mod('restatify_dark_mask_icon');
    if (!$dark_site_icon_id && !$dark_mask_icon_id) {
        return;
    }

    $dark_site_icon_url = $dark_site_icon_id ? wp_get_attachment_image_url($dark_site_icon_id, 'full') : '';
    $dark_mask_icon_url = $dark_mask_icon_id ? wp_get_attachment_image_url($dark_mask_icon_id, 'full') : '';
    $dark_icon_type = $dark_site_icon_id ? get_post_mime_type($dark_site_icon_id) : '';

    if (empty($dark_site_icon_url) && empty($dark_mask_icon_url)) {
        return;
    }

    if (!empty($dark_site_icon_url) && empty($dark_mask_icon_url) && $dark_icon_type === 'image/svg+xml') {
        $dark_mask_icon_url = $dark_site_icon_url;
    }

    $light_site_icon_id = (int) get_option('site_icon');
    $light_site_icon_url = get_site_icon_url(512);
    $light_apple_icon_url = get_site_icon_url(180);
    $light_icon_type = $light_site_icon_id ? get_post_mime_type($light_site_icon_id) : '';

    $icon_data = [
        'darkIcon'   => $dark_site_icon_url,
        'darkType'   => $dark_icon_type,
        'lightIcon'  => $light_site_icon_url,
        'lightType'  => $light_icon_type,
        'darkApple'  => $dark_site_icon_url,
        'lightApple' => $light_apple_icon_url ?: $light_site_icon_url,
        'darkMask'   => $dark_mask_icon_url,
        'lightMask'  => '',
    ];
    ?>
    <script id="restatify-dark-icons">
    (function () {
      var data = <?php echo wp_json_encode($icon_data); ?>;
      if (!data || (!data.darkIcon && !data.darkMask)) return;

      var query = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

      function removeExistingIcons() {
        var selectors = [
          'link[rel="icon"]',
          'link[rel="shortcut icon"]',
          'link[rel="apple-touch-icon"]',
          'link[rel="mask-icon"]'
        ];
        document.head.querySelectorAll(selectors.join(',')).forEach(function (node) {
          node.parentNode.removeChild(node);
        });
      }

      function appendLink(relValue, href, type, extraAttrs) {
        if (!href) return;
        var link = document.createElement('link');
        link.setAttribute('rel', relValue);
        link.setAttribute('href', href);
        if (type) {
          link.setAttribute('type', type);
        }
        if (extraAttrs) {
          Object.keys(extraAttrs).forEach(function (key) {
            link.setAttribute(key, extraAttrs[key]);
          });
        }
        document.head.appendChild(link);
      }

      function withThemeSuffix(url, dark) {
        if (!url) return url;
        var sep = url.indexOf('?') === -1 ? '?' : '&';
        return url + sep + 'theme=' + (dark ? 'dark' : 'light') + '&v=1';
      }

      function applyIcons() {
        var dark = query ? query.matches : false;
        var iconHref = dark ? data.darkIcon : data.lightIcon;
        var iconType = dark ? data.darkType : data.lightType;
        var appleHref = dark ? (data.darkApple || data.darkIcon) : (data.lightApple || data.lightIcon);
        var maskHref = dark ? data.darkMask : data.lightMask;

        iconHref = withThemeSuffix(iconHref, dark);
        appleHref = withThemeSuffix(appleHref, dark);
        maskHref = withThemeSuffix(maskHref, dark);

        removeExistingIcons();
        appendLink('shortcut icon', iconHref, iconType);
        appendLink('icon', iconHref, iconType);
        appendLink('apple-touch-icon', appleHref);
        appendLink('mask-icon', maskHref, null, { color: '#e2e8f0' });
      }

      function start() {
        applyIcons();
        if (query) {
          if (typeof query.addEventListener === 'function') {
            query.addEventListener('change', applyIcons);
          } else if (typeof query.addListener === 'function') {
            query.addListener(applyIcons);
          }
        }
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start, { once: true });
      } else {
        start();
      }
      window.addEventListener('load', applyIcons, { once: true });
    })();
    </script>
    <?php
}
add_action('wp_head', 'restatify_output_dark_site_icon', 1000);
