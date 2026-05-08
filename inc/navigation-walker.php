<?php
/**
 * Navigation walker.
 */

class Restatify_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n{$indent}<ul class=\"dropdown-menu\">\n";
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "{$indent}</ul>\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $has_children = in_array('menu-item-has-children', $classes, true);

        $li_classes = ['nav-item'];
        if ($depth === 0 && $has_children) {
            $li_classes[] = 'dropdown';
        }
        if (in_array('current-menu-item', $classes, true) || in_array('current-menu-ancestor', $classes, true) || in_array('current_page_item', $classes, true)) {
            $li_classes[] = 'active';
        }

        $output .= '<li class="' . esc_attr(implode(' ', array_unique($li_classes))) . '">';

        $link_classes = $depth === 0 ? ['nav-link', 'link', 'text-success', 'display-4'] : ['dropdown-item'];
        if ($depth === 0 && $has_children) {
            $link_classes[] = 'dropdown-toggle';
        }

        if (in_array('current-menu-item', $classes, true) || in_array('current-menu-ancestor', $classes, true) || in_array('current_page_item', $classes, true)) {
            $link_classes[] = 'active';
        }

        $atts = [
            'class' => implode(' ', array_unique($link_classes)),
            'href'  => !empty($item->url) ? $item->url : '#',
        ];

        if ($depth === 0 && $has_children) {
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
            $atts['role'] = 'button';
        }

        if (!empty($item->target)) {
            $atts['target'] = $item->target;
        }
        if (!empty($item->xfn)) {
            $atts['rel'] = $item->xfn;
        }
        if (!empty($item->attr_title)) {
            $atts['title'] = $item->attr_title;
        }

        $attr_html = '';
        foreach ($atts as $name => $value) {
            if ($value === '') {
                continue;
            }

            $escaped_value = $name === 'href' ? esc_url($value) : esc_attr($value);
            $attr_html .= ' ' . $name . '="' . $escaped_value . '"';
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $output .= '<a' . $attr_html . '>' . esc_html($title) . '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
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
