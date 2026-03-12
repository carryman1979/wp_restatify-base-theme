<?php
/**
 * Navigation walker.
 */

class Restatify_Walker_Nav_Menu extends Walker_Page {
    public function start_lvl(&$output, $depth = 0, $args = []) {
        $output .= "\n<ul class=\"dropdown-menu\">\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = []) {
        $output .= "</ul>\n";
    }

    public function start_el(&$output, $page, $depth = 0, $args = [], $current_page = 0) {
        $has_children = !empty($args['pages_with_children'][$page->ID]);

        $li_classes = ['nav-item'];
        if ($depth === 0 && $has_children) {
            $li_classes[] = 'dropdown';
        }
        if (!empty($current_page) && (int) $page->ID === (int) $current_page) {
            $li_classes[] = 'active';
        }

        $output .= '<li class="' . esc_attr(implode(' ', $li_classes)) . '">';

        $link_class = $depth === 0 ? 'nav-link link text-success display-4' : 'dropdown-item';
        $atts = '';
        if ($depth === 0 && $has_children) {
            $link_class .= ' dropdown-toggle';
            $atts .= ' data-bs-toggle="dropdown" aria-expanded="false"';
        }

        $output .= '<a class="' . esc_attr($link_class) . '" href="' . esc_url(get_permalink($page->ID)) . '"' . $atts . '>';

        if ($depth === 0 && ($page->post_name === 'home' || (int) $page->ID === (int) get_option('page_on_front'))) {
            $output .= '<span class="socicon socicon-homes mbr-iconfont mbr-iconfont-btn"></span> ';
        }

        $output .= esc_html(apply_filters('the_title', $page->post_title, $page->ID));
        $output .= '</a>';
    }
}

function restatify_get_social_icon_class($url) {
    $host = wp_parse_url((string) $url, PHP_URL_HOST);
    if (!$host) {
        return 'socicon-sharethis';
    }

    $host = strtolower(preg_replace('/^www\./', '', $host));

    $map = [
        'linkedin.com' => 'socicon-linkedin',
        'facebook.com' => 'socicon-facebook',
        'instagram.com' => 'socicon-instagram',
        'youtube.com' => 'socicon-youtube',
        'youtu.be' => 'socicon-youtube',
        'x.com' => 'socicon-twitter',
        'twitter.com' => 'socicon-twitter',
        'xing.com' => 'socicon-xing',
        'tiktok.com' => 'socicon-tiktok',
        'github.com' => 'socicon-github',
        'pinterest.com' => 'socicon-pinterest',
        'reddit.com' => 'socicon-reddit',
        'vimeo.com' => 'socicon-vimeo',
        'discord.com' => 'socicon-discord',
        'telegram.org' => 'socicon-telegram',
        't.me' => 'socicon-telegram',
        'whatsapp.com' => 'socicon-whatsapp',
        'wa.me' => 'socicon-whatsapp',
        'medium.com' => 'socicon-medium',
        'threads.net' => 'socicon-threads',
        'bsky.app' => 'socicon-bluesky',
        'mastodon.social' => 'socicon-mastodon',
    ];

    foreach ($map as $domain => $icon_class) {
        if ($host === $domain || str_ends_with($host, '.' . $domain)) {
            return $icon_class;
        }
    }

    if (str_starts_with($url, 'mailto:')) {
        return 'socicon-mail';
    }

    return 'socicon-sharethis';
}

function restatify_get_social_icon_override($menu_item) {
    if (empty($menu_item) || empty($menu_item->classes) || !is_array($menu_item->classes)) {
        return '';
    }

    foreach ($menu_item->classes as $class_name) {
        $class_name = trim((string) $class_name);
        if ($class_name === '') {
            continue;
        }

        if (preg_match('/^socicon-[a-z0-9\-]+$/', $class_name) === 1) {
            return $class_name;
        }

        if (preg_match('/^icon:([a-z0-9\-]+)$/', $class_name, $matches) === 1) {
            return 'socicon-' . $matches[1];
        }
    }

    return '';
}

class Restatify_Walker_Social_Menu extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth > 0 || empty($item->url)) {
            return;
        }

        $icon_override = restatify_get_social_icon_override($item);
        $icon_class = $icon_override !== '' ? $icon_override : restatify_get_social_icon_class($item->url);
        $label = trim(wp_strip_all_tags($item->title));
        $label = $label !== '' ? $label : __('Social Link', 'restatify-base');
        $target = !empty($item->target) ? $item->target : '_blank';

        $rel_values = [];
        if (!empty($item->xfn)) {
            $rel_values[] = $item->xfn;
        }
        if ($target === '_blank') {
            $rel_values[] = 'noopener';
        }
        $rel = trim(implode(' ', array_unique(array_filter($rel_values))));

        $output .= '<a class="iconfont-wrapper" href="' . esc_url($item->url) . '" target="' . esc_attr($target) . '"';
        if ($rel !== '') {
            $output .= ' rel="' . esc_attr($rel) . '"';
        }
        $output .= ' aria-label="' . esc_attr($label) . '">';
        $output .= '<span class="p-2 mbr-iconfont socicon ' . esc_attr($icon_class) . '"></span>';
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
    }
}
