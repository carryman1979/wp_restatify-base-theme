<?php
/**
 * Server-rendered single-post navigation controls.
 */

/**
 * Render the blog overview link and accessible article-list dialog.
 *
 * The list contains published posts in the active Polylang language, when
 * Polylang is enabled. The overview link uses the configured posts page.
 *
 * @return string
 */
function restatify_render_blog_navigation_block() {
    $posts_page_id = (int) get_option('page_for_posts');
    if ($posts_page_id > 0 && function_exists('pll_get_post')) {
        $translated_page_id = (int) pll_get_post($posts_page_id);
        if ($translated_page_id > 0) {
            $posts_page_id = $translated_page_id;
        }
    }

    $overview_url = $posts_page_id > 0 ? get_permalink($posts_page_id) : false;
    if (! $overview_url) {
        $overview_url = get_post_type_archive_link('post');
    }
    if (! $overview_url) {
        $overview_url = home_url('/');
    }

    $current_post_id = get_queried_object_id();
    $dialog_id = 'restatify-article-list-' . $current_post_id;
    $heading_id = $dialog_id . '-heading';
    $page_size = 10;
    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'suppress_filters' => false,
    ]);

    $overview_label = __('Zur Blog-Übersicht', 'restatify-base');
    $open_label = __('Artikelübersicht öffnen', 'restatify-base');
    $dialog_heading = __('Alle Blogartikel', 'restatify-base');
    $close_label = __('Artikelübersicht schließen', 'restatify-base');
    $nav_label = __('Blog-Navigation', 'restatify-base');
    $pagination_label = __('Artikellisten-Seiten', 'restatify-base');
    $previous_label = __('Vorherige Artikel', 'restatify-base');
    $next_label = __('Weitere Artikel', 'restatify-base');
    $page_status = __('Seite %1$d von %2$d', 'restatify-base');
    $empty_label = __('Es sind noch keine Artikel veröffentlicht.', 'restatify-base');

    if (function_exists('pll__')) {
        $overview_label = pll__($overview_label);
        $open_label = pll__($open_label);
        $dialog_heading = pll__($dialog_heading);
        $close_label = pll__($close_label);
        $nav_label = pll__($nav_label);
        $pagination_label = pll__($pagination_label);
        $previous_label = pll__($previous_label);
        $next_label = pll__($next_label);
        $page_status = pll__($page_status);
        $empty_label = pll__($empty_label);
    }

    ob_start();
    ?>
    <nav class="restatify-post-navigation" aria-label="<?php echo esc_attr($nav_label); ?>">
        <a class="restatify-post-navigation__back" href="<?php echo esc_url($overview_url); ?>">
            <span aria-hidden="true">&larr;</span>
            <?php echo esc_html($overview_label); ?>
        </a>
        <button
            class="restatify-post-navigation__open"
            type="button"
            aria-haspopup="dialog"
            aria-controls="<?php echo esc_attr($dialog_id); ?>"
            aria-label="<?php echo esc_attr($open_label); ?>"
            data-restatify-article-dialog-open
        >
            <svg class="restatify-post-navigation__icon" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                <path d="M7 5h9M7 10h9M7 15h9" />
                <circle cx="3" cy="5" r=".75" />
                <circle cx="3" cy="10" r=".75" />
                <circle cx="3" cy="15" r=".75" />
            </svg>
        </button>
        <dialog
            class="restatify-article-dialog"
            id="<?php echo esc_attr($dialog_id); ?>"
            aria-labelledby="<?php echo esc_attr($heading_id); ?>"
            data-restatify-article-dialog
        >
            <div class="restatify-article-dialog__header">
                <h2 class="restatify-article-dialog__title" id="<?php echo esc_attr($heading_id); ?>">
                    <?php echo esc_html($dialog_heading); ?>
                </h2>
                <button
                    class="restatify-article-dialog__close"
                    type="button"
                    aria-label="<?php echo esc_attr($close_label); ?>"
                    data-restatify-article-dialog-close
                >&times;</button>
            </div>
            <?php if ($posts) : ?>
                <ul class="restatify-article-dialog__list">
                    <?php foreach ($posts as $post) : ?>
                        <?php
                        $post_id = (int) $post->ID;
                        $is_current = $post_id === $current_post_id;
                        ?>
                        <li data-restatify-article-item>
                            <a
                                class="restatify-article-dialog__link<?php echo $is_current ? ' is-current' : ''; ?>"
                                href="<?php echo esc_url(get_permalink($post_id)); ?>"
                                <?php if ($is_current) : ?>
                                    aria-current="page"
                                <?php endif; ?>
                            >
                                <?php echo esc_html(get_the_title($post_id)); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (count($posts) > $page_size) : ?>
                    <nav
                        class="restatify-article-dialog__pagination"
                        aria-label="<?php echo esc_attr($pagination_label); ?>"
                        data-restatify-article-pagination
                        data-page-size="<?php echo esc_attr((string) $page_size); ?>"
                        data-page-status="<?php echo esc_attr($page_status); ?>"
                        hidden
                    >
                        <button
                            class="restatify-article-dialog__page-button"
                            type="button"
                            data-restatify-article-previous
                            aria-label="<?php echo esc_attr($previous_label); ?>"
                        >&larr;</button>
                        <span class="restatify-article-dialog__page-status" aria-live="polite" data-restatify-article-page-status></span>
                        <button
                            class="restatify-article-dialog__page-button"
                            type="button"
                            data-restatify-article-next
                            aria-label="<?php echo esc_attr($next_label); ?>"
                        >&rarr;</button>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <p class="restatify-article-dialog__empty"><?php echo esc_html($empty_label); ?></p>
            <?php endif; ?>
        </dialog>
    </nav>
    <?php
    return (string) ob_get_clean();
}

/**
 * Render links to the adjacent published posts.
 *
 * @return string
 */
function restatify_render_post_adjacent_navigation_block() {
    if (! is_singular('post')) {
        return '';
    }

    $previous_post = get_previous_post();
    $next_post = get_next_post();
    if (! $previous_post && ! $next_post) {
        return '';
    }

    $nav_label = __('Artikelnavigation', 'restatify-base');
    $previous_label = __('Vorheriger Artikel', 'restatify-base');
    $next_label = __('Nächster Artikel', 'restatify-base');
    if (function_exists('pll__')) {
        $nav_label = pll__($nav_label);
        $previous_label = pll__($previous_label);
        $next_label = pll__($next_label);
    }

    ob_start();
    ?>
    <nav class="restatify-adjacent-posts" aria-label="<?php echo esc_attr($nav_label); ?>">
        <?php if ($previous_post) : ?>
            <a class="restatify-adjacent-posts__link restatify-adjacent-posts__link--previous" href="<?php echo esc_url(get_permalink($previous_post)); ?>" rel="prev">
                <span class="restatify-adjacent-posts__label"><?php echo esc_html($previous_label); ?></span>
                <span class="restatify-adjacent-posts__title"><?php echo esc_html(get_the_title($previous_post)); ?></span>
            </a>
        <?php endif; ?>
        <?php if ($next_post) : ?>
            <a class="restatify-adjacent-posts__link restatify-adjacent-posts__link--next" href="<?php echo esc_url(get_permalink($next_post)); ?>" rel="next">
                <span class="restatify-adjacent-posts__label"><?php echo esc_html($next_label); ?></span>
                <span class="restatify-adjacent-posts__title"><?php echo esc_html(get_the_title($next_post)); ?></span>
            </a>
        <?php endif; ?>
    </nav>
    <?php
    return (string) ob_get_clean();
}

add_action('init', static function () {
    register_block_type('restatify/blog-navigation', [
        'api_version' => 3,
        'title' => __('Restatify Blog-Navigation', 'restatify-base'),
        'category' => 'theme',
        'supports' => [
            'html' => false,
        ],
        'render_callback' => 'restatify_render_blog_navigation_block',
    ]);

    register_block_type('restatify/post-adjacent-navigation', [
        'api_version' => 3,
        'title' => __('Restatify Artikel-Navigation', 'restatify-base'),
        'category' => 'theme',
        'supports' => [
            'html' => false,
        ],
        'render_callback' => 'restatify_render_post_adjacent_navigation_block',
    ]);
});

add_action('enqueue_block_editor_assets', static function () {
    $editor_script_path = get_template_directory() . '/assets/theme/js/blog-navigation-editor.js';
    wp_enqueue_script(
        'restatify-blog-navigation-editor',
        get_template_directory_uri() . '/assets/theme/js/blog-navigation-editor.js',
        ['wp-blocks', 'wp-element', 'wp-i18n', 'wp-server-side-render'],
        file_exists($editor_script_path) ? (string) filemtime($editor_script_path) : null,
        true
    );
});
