<?php
/**
 * Theme setup and supports.
 */

function restatify_setup() {
    // Editor Styles & Wide Align
    add_theme_support('editor-styles');
    add_editor_style([
        'assets/bootstrap/css/bootstrap.min.css',
        'assets/theme/css/style.css',
        'assets/mobirise/css/mbr-additional.css',
        'https://fonts.googleapis.com/css?family=Science+Gothic:100,200,300,400,500,600,700,800,900&display=swap'
    ]);
    add_theme_support('align-wide');

    // Logo Support
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Menü-Registrierung (Zentralisiert)
    register_nav_menus([
        'cta_button_menu' => 'Header Aktions-Button (Start Now Ersatz)',
        'header_social_menu' => 'Header Social Links'
    ]);
}
add_action('after_setup_theme', 'restatify_setup');
