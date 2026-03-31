== Restatify Base ==

Contributors: Thomas Hoffermann
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 5.7
Version: 1.0.1
Text Domain: restatify-base
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: custom-blocks, full-site-editing, business


== Description ==

Restatify Base is the custom WordPress base theme for Restatify UG (haftungsbeschraenkt).

The theme ships with a custom Gutenberg block library and section-focused layouts for modern business pages.

Included custom blocks:

* Hero
* Services
* Studies
* Testimonials
* Mission
* Metrics
* Insight
* Gallery
* FAQ
* Pricing
* Contact
* Clients


== Installation ==

1. Copy the theme folder to /wp-content/themes/wp_restatify-base-theme.
2. Activate Restatify Base in Appearance -> Themes.
3. Open the Site Editor and insert Restatify blocks as needed.

For external installations, use a release ZIP package that contains compiled /build assets.


== Development ==

This theme includes block sources in /src and compiled assets in /build.

1. Run npm install in the theme directory.
2. Run npm run build for production assets.
3. Run npm run start for watch mode during development.

Block registration and runtime block style variables are handled in /inc/blocks.php.


== Frequently Asked Questions ==

= Which editor is supported? =

The theme targets the block editor (Gutenberg) and site editing workflows.

= Do I need to run a build before using the theme in production? =

Yes. Build assets with npm run build after source changes in /src.

= Where are default block backgrounds and CSS variables configured? =

Default block background variables are injected in /inc/blocks.php.

= Is there a German documentation file? =

Yes. See /README.de.md for the German theme documentation.

= Can I install directly from a GitHub source ZIP? =

For this theme, prefer a release ZIP package generated from the repository workspace.
GitHub source ZIP archives can miss required compiled block assets.

Generate a release package with:

1. npm install
2. npm run package

The ZIP is created in /release.


== Changelog ==

= 1.0.1 =
* Added reproducible release ZIP packaging workflow.
* Added external installation guidance for production-ready packages.

= 1.0.0 =
* Initial public release.
* Added Restatify custom Gutenberg block library.
* Added shared background/layout control integration for sections.


== Copyright ==

Restatify Base WordPress Theme, (C) 2026 Thomas Hoffermann
Restatify Base is distributed under the terms of the GNU GPL.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

