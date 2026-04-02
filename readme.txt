== Restatify Base ==

Contributors: Thomas Hoffermann
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 5.7
Version: 1.0.4
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
* Oracle


== Oracle Block ==

Oracle is a futuristic word-pair animation section with periodic logo reveal.

Configurable options include:

* Word pairs (one pair per line, separated by "|").
* Word cycle duration.
* Logo reveal interval and visible duration.
* Position drift range for animated movement.
* Word colors, glow color, and shell gradient colors.
* Optional fallback logo image (used when no site logo is detected).
* Standard background/layout controls (background image, overlay, parallax, fullscreen).


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

= Where are important vs optional customizer settings now? =

Footer customizer settings are split into:

1. Footer Core (Recommended) for important contact/slogan fields.
2. Footer Expert Settings (Optional) for social links, trust badges and vCard.

= Can I install directly from a GitHub source ZIP? =

For this theme, prefer a release ZIP package generated from the repository workspace.
GitHub source ZIP archives can miss required compiled block assets.

Generate a release package with:

1. npm install
2. npm run package

The ZIP is created in /release.


== Changelog ==

= 1.0.4 =
* Improved customizer UX by separating Footer Core (recommended) and Footer Expert (optional) settings.
* Promoted key footer contact fields to the primary section and added required email input attributes.
* Updated documentation for revised settings hierarchy.

= 1.0.3 =
* Added admin-scoped SVG/SVGZ upload sanitization in theme setup.
* Added hardened SVG upload prefilter for regular uploads and media sideload imports.
* Kept SVG MIME mapping and filetype validation for WordPress upload checks.

= 1.0.2 =
* Added new Oracle custom block with futuristic word-pair animation.
* Added configurable sequence mode (ordered or random), animation timing, and logo overlay timing.
* Added Oracle block color tuning and improved pair transition behavior.

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

