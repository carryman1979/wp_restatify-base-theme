<?php
/**
 * Theme admin page: Icon browser and class copier.
 */

function restatify_register_icons_admin_page(): void {
    add_theme_page(
        __('Icons', 'restatify-base'),
        __('Icons', 'restatify-base'),
        'edit_theme_options',
        'restatify-theme-icons',
        'restatify_render_icons_admin_page'
    );
}
add_action('admin_menu', 'restatify_register_icons_admin_page');

function restatify_extract_icon_classes_from_css(string $absolute_path, string $prefix = ''): array {
    if (!file_exists($absolute_path) || !is_readable($absolute_path)) {
        return [];
    }

    $css = file_get_contents($absolute_path);
    if (!is_string($css) || $css === '') {
        return [];
    }

    if (preg_match_all('/\.([a-z0-9][a-z0-9\-]*)\s*::?before\s*\{/i', $css, $matches) !== 1 && empty($matches[1])) {
        return [];
    }

    $icons = [];
    foreach ((array) ($matches[1] ?? []) as $icon_class) {
        $icon_class = strtolower(trim((string) $icon_class));
        if ($icon_class === '') {
            continue;
        }

        if ($prefix !== '' && strpos($icon_class, $prefix) !== 0) {
            continue;
        }

        $icons[] = $icon_class;
    }

    $icons = array_values(array_unique($icons));
    sort($icons, SORT_NATURAL | SORT_FLAG_CASE);

    return $icons;
}

function restatify_get_icon_library_definition(): array {
    $base = get_template_directory();
    $uri = get_template_directory_uri();

    return [
        [
            'slug' => 'socicon',
            'label' => 'Socicon',
            'prefix' => 'socicon-',
            'css_path' => $base . '/assets/socicon/css/styles.css',
            'css_uri' => $uri . '/assets/socicon/css/styles.css',
        ],
        [
            'slug' => 'iconsmind',
            'label' => 'iconsMind',
            'prefix' => 'imind-',
            'css_path' => $base . '/assets/iconsMind/style.css',
            'css_uri' => $uri . '/assets/iconsMind/style.css',
        ],
        [
            'slug' => 'icon54',
            'label' => 'icon54-v2',
            'prefix' => 'icon54-v2-',
            'css_path' => $base . '/assets/icon54-v2/style.css',
            'css_uri' => $uri . '/assets/icon54-v2/style.css',
        ],
        [
            'slug' => 'mobirise2',
            'label' => 'mobi-mbri',
            'prefix' => 'mobi-mbri-',
            'css_path' => $base . '/assets/web/assets/mobirise-icons2/mobirise2.css',
            'css_uri' => $uri . '/assets/web/assets/mobirise-icons2/mobirise2.css',
        ],
    ];
}

function restatify_enqueue_icons_admin_assets(string $hook): void {
    if ($hook !== 'appearance_page_restatify-theme-icons') {
        return;
    }

    $libraries = restatify_get_icon_library_definition();
    foreach ($libraries as $library) {
        $handle = 'restatify-icons-lib-' . $library['slug'];
        $version = file_exists($library['css_path']) ? (string) filemtime($library['css_path']) : '1.0.0';
        wp_enqueue_style($handle, $library['css_uri'], [], $version);
    }

    wp_register_style('restatify-icons-admin', false, [], '1.0.0');
    wp_enqueue_style('restatify-icons-admin');
    wp_add_inline_style('restatify-icons-admin', '
        .restatify-icons-wrap { margin-top: 16px; }
        .restatify-icons-toolbar { display: flex; gap: 12px; align-items: center; margin-bottom: 16px; }
        .restatify-icons-toolbar input[type="search"] { min-width: 340px; }
        .restatify-icons-meta { color: #50575e; font-size: 12px; }
        .restatify-icons-library { margin: 20px 0 28px; }
        .restatify-icons-library h2 { margin: 0 0 10px; }
        .restatify-icons-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 10px; }
        .restatify-icon-card { background: #fff; border: 1px solid #dcdcde; border-radius: 8px; padding: 8px; display: flex; flex-direction: column; gap: 8px; }
        .restatify-icon-actions { display: flex; gap: 6px; }
        .restatify-icon-actions .button { min-height: 24px; line-height: 22px; padding: 0 8px; font-size: 11px; }
        .restatify-icon-preview { min-height: 34px; display: flex; align-items: center; justify-content: center; border: 1px dashed #dcdcde; border-radius: 6px; background: #f6f7f7; }
        .restatify-icon-preview .mbr-iconfont { font-size: 20px; }
        .restatify-icon-class { font-family: Consolas, Menlo, Monaco, monospace; font-size: 11px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .restatify-icon-card.is-hidden { display: none; }
        .restatify-icons-empty { display: none; margin-top: 10px; color: #50575e; }
        .restatify-icons-empty.is-visible { display: block; }
    ');

    wp_register_script('restatify-icons-admin', false, [], '1.0.0', true);
    wp_enqueue_script('restatify-icons-admin');
    wp_add_inline_script('restatify-icons-admin', '
        (function () {
          function copyText(value) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
              return navigator.clipboard.writeText(value);
            }
            var helper = document.createElement("textarea");
            helper.value = value;
            document.body.appendChild(helper);
            helper.select();
            document.execCommand("copy");
            helper.remove();
            return Promise.resolve();
          }

          function init() {
            var search = document.getElementById("restatify-icons-search");
            var cards = Array.prototype.slice.call(document.querySelectorAll(".restatify-icon-card"));
            var empty = document.getElementById("restatify-icons-empty");

            document.querySelectorAll("[data-copy-value]").forEach(function (btn) {
              btn.addEventListener("click", function () {
                var value = btn.getAttribute("data-copy-value") || "";
                if (!value) {
                  return;
                }

                copyText(value).then(function () {
                  var old = btn.textContent;
                  btn.textContent = "Copied";
                  window.setTimeout(function () { btn.textContent = old; }, 900);
                });
              });
            });

            if (!search) {
              return;
            }

            var filterCards = function () {
              var query = (search.value || "").toLowerCase().trim();
              var visible = 0;

              cards.forEach(function (card) {
                var haystack = (card.getAttribute("data-search") || "").toLowerCase();
                var match = query === "" || haystack.indexOf(query) !== -1;
                card.classList.toggle("is-hidden", !match);
                if (match) {
                  visible += 1;
                }
              });

              if (empty) {
                empty.classList.toggle("is-visible", visible === 0);
              }
            };

            search.addEventListener("input", filterCards);
            filterCards();
          }

          if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", init, { once: true });
          } else {
            init();
          }
        })();
    ');
}
add_action('admin_enqueue_scripts', 'restatify_enqueue_icons_admin_assets');

function restatify_render_icons_admin_page(): void {
    if (!current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to access this page.', 'restatify-base'));
    }

    $libraries = restatify_get_icon_library_definition();
    ?>
    <div class="wrap restatify-icons-wrap">
        <h1><?php echo esc_html__('Theme Icons', 'restatify-base'); ?></h1>
        <p class="description"><?php echo esc_html__('Klicke auf Copy Class, um die reine CSS-Klasse zu kopieren. Klicke auf Copy Title Syntax, um direkt die Menue-Titel-Syntax zu kopieren.', 'restatify-base'); ?></p>

        <div class="restatify-icons-toolbar">
            <input type="search" id="restatify-icons-search" class="regular-text" placeholder="<?php echo esc_attr__('Search icon class...', 'restatify-base'); ?>" />
            <span class="restatify-icons-meta"><?php echo esc_html__('Syntax im Menue-Titel: [icon:imind-home] Start oder icon:imind-home|Start', 'restatify-base'); ?></span>
        </div>

        <p id="restatify-icons-empty" class="restatify-icons-empty"><?php echo esc_html__('Keine Icons fuer die aktuelle Suche gefunden.', 'restatify-base'); ?></p>

        <?php foreach ($libraries as $library) : ?>
            <?php
            $icons = restatify_extract_icon_classes_from_css($library['css_path'], $library['prefix']);
            if (empty($icons)) {
                continue;
            }
            ?>
            <section class="restatify-icons-library" aria-label="<?php echo esc_attr($library['label']); ?>">
                <h2><?php echo esc_html($library['label']); ?> <span class="restatify-icons-meta">(<?php echo (int) count($icons); ?>)</span></h2>
                <div class="restatify-icons-grid">
                    <?php foreach ($icons as $icon_class) : ?>
                        <?php $title_syntax = '[icon:' . $icon_class . '] '; ?>
                        <article class="restatify-icon-card" data-search="<?php echo esc_attr($library['label'] . ' ' . $icon_class); ?>">
                            <div class="restatify-icon-actions">
                                <button type="button" class="button button-small" data-copy-value="<?php echo esc_attr($icon_class); ?>"><?php echo esc_html__('Copy Class', 'restatify-base'); ?></button>
                                <button type="button" class="button button-small" data-copy-value="<?php echo esc_attr($title_syntax); ?>"><?php echo esc_html__('Copy Title Syntax', 'restatify-base'); ?></button>
                            </div>
                            <div class="restatify-icon-preview">
                                <span class="mbr-iconfont <?php echo esc_attr($icon_class); ?><?php echo strpos($icon_class, 'socicon-') === 0 ? ' socicon' : ''; ?>" aria-hidden="true"></span>
                            </div>
                            <div class="restatify-icon-class" title="<?php echo esc_attr($icon_class); ?>"><?php echo esc_html($icon_class); ?></div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
    <?php
}
