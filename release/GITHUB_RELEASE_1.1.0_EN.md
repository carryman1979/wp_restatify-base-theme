# Restatify Base Theme 1.1.0

## What's new

- Bundled header and consent hotfixes into one consistent release.
- Extended the header pattern with `navbar-actions` to stabilize alignment of social links, CTA, theme switch, and language switch.
- Fixed tagline behavior across breakpoints: desktop wrapping, compact typography on tighter widths, hidden tagline on mobile header.
- Added compact mode (992-1220px): earlier switch to hamburger/collapse prevents tagline/menu overlap.
- Hardened hamburger icon rendering for Opera desktop/mobile (pseudo-element icon + visibility/layering fallbacks).
- Fixed cookie consent + booking interaction: no forced reload while booking overlay/hash booking is active.
- Added integration event `restatify:cookie-consent-changed`.
- Raised cookie banner z-index above booking overlay.
- Added JS unit test coverage for consent booking guard and test hooks.

## Compatibility

- Theme version: `1.1.0`
- No intended breaking changes.

## Artifact

- `wp_restatify-base-theme-1.1.0.zip`
