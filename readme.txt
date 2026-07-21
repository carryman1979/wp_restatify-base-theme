== Restatify Base ==

Contributors: Thomas Hoffermann
Requires at least: 6.9
Tested up to: 6.9
Requires PHP: 5.7
Version: 1.1.1
Text Domain: restatify-base
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: custom-blocks, full-site-editing, business


== Description ==

Restatify Base is the custom WordPress base theme for Restatify UG (haftungsbeschraenkt).

The theme is designed around standard WordPress mechanisms:

* Core blocks for content authoring.
* Block patterns for reusable section layouts.
* Templates and template parts for site structure.
* theme.json for global style control.

It additionally ships with a selected custom Gutenberg block library for advanced, brand-specific sections.

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
* Timer


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


== Timer Block ==

Timer is a target-date countdown section with automatic unit logic and calendar-accurate year/month calculations.

Configurable options include:

* Target date and time via date-time picker.
* Optional title, subtitle, and description.
* Automatic unit visibility based on remaining time:
	* years/months/days/hours/minutes/seconds when relevant
	* smooth reflow animation when a leading unit drops out.
* Proper singular/plural unit labels in German.
* CI-aligned colors with dedicated editor contrast handling.


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

= 1.1.1 =
* Maintenance release consolidating current local theme updates and wiki refresh.
* Synchronized theme metadata and package version for coordinated multi-repo publication.

= 1.1.0 =
* Consolidated responsive header hotfix package: improved brand/nav/actions distribution and stable `navbar-actions` grouping in header pattern.
* Fixed tagline behavior across breakpoints: wraps on desktop, tighter compact typography, hidden in mobile header.
* Added compact header mode for 992-1220px to switch earlier to hamburger/collapse and avoid overlap between tagline and navigation.
* Hardened hamburger icon rendering for Opera desktop/mobile via pseudo-element fallback and stronger toggler visibility/layering rules.
* Fixed cookie consent + booking overlay interaction: prevent forced reload when booking overlay/hash booking is active.
* Added consent change event `restatify:cookie-consent-changed` for integration listeners.
* Raised cookie banner/backdrop z-index to stay actionable above booking overlay.
* Added JS unit test coverage for cookie consent booking guard and test hooks.

= 1.0.14 =
* Hotfix rebuild without version bump: replaced defective 1.0.14 release package.
* Added theme-safe shared runtime resolver for `PluginState` using versioned central path and legacy fallback.
* Added packaged shared install payload in release ZIP to ensure legal notice shared dependency remains resolvable during installs.

= 1.0.13 =
* Added JS unit test baseline for shared link utility behavior.
* Added repository-level AGENTS and Copilot instruction files linked to central shared guidance.
* Updated release docs/wiki for the current maintenance release.

= 1.0.12 =
* Added a dedicated Restatify Blog block-pattern category.
* Added core-based blog starter patterns for Blog Hero, Blog Post Grid, and Related Posts.
* Added global query-loop card styling for a consistent Restatify blog card system.
* Updated theme/readme/wiki descriptions to explicitly document the standard WordPress mechanism approach (core blocks, patterns, templates, theme.json).

= 1.0.11 =
* Changed header primary navigation to use an assigned WordPress menu location instead of auto-listing top-level pages.
* Added a dedicated primary header menu location (Header Hauptmenue) in theme setup.
* Added admin warning notice when no menu is assigned to the primary header menu location.
* Updated navigation walker from page-based to nav-menu-based rendering for robust menu item/dropdown handling.
* Extended Contact block editor with flexible details list (text/link/button), reordering, optional body text, and legacy field migration support.
* Improved Contact block frontend/editor styles for optional links, button rendering, and multiline text.
* Improved Oracle block word fitting to keep long words on one line with responsive font fitting on initial render and window resize.

= 1.0.10 =
* Fixed Metrics block fullscreen/parallax behavior so layout modes apply reliably in frontend output.
* Added configurable Metrics number animation modes (none or count-up) with adjustable duration (default: 1500 ms).
* Added viewport-aware one-time Metrics count-up trigger that starts only when all metric numbers are visible.
* Added Mission block compatibility improvements for subtitle hierarchy and preserved line breaks.

= 1.0.9 =
* Fixed icon placeholder squares for anonymous users by adding cache-busting versions to icon font stylesheets.
* Fixed typo in IconsMind EOT font filename to match shipped font assets.

= 1.0.8 =
* Fixed maintenance-mode no-scroll regression for anonymous users when the cookie banner is intentionally hidden.
* Fixed Gutenberg default button rendering for multi-line labels in frontend output.
* Added stronger final CSS overrides for button pseudo-elements to avoid legacy style collisions.

= 1.0.7 =
* Added new Timer custom block with target date-time picker.
* Added calendar-accurate countdown decomposition including years and months.
* Added automatic unit visibility (years to seconds) with animated unit reflow.
* Added German singular/plural labels for all countdown units.
* Improved block editor preview contrast for Timer in dark and light designer contexts.

= 1.0.6 =
* Added robust LightStart template integration to switch header/footer by maintenance status.
* Prevented duplicate head/localized script output on block themes when LightStart maintenance is disabled.
* Added maintenance-focused legal fallback pages and root-level compatibility redirects.
* Extended maintenance customizer options for legal/imprint links and improved documentation coverage.

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

