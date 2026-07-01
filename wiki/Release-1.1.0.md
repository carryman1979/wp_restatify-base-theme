# Release 1.1.0

Version 1.1.0 bündelt die Header-/Consent-Hotfixes in ein konsistentes Release-Artefakt.

## Highlights

- Header-Layout im Theme stabilisiert: neue `navbar-actions`-Gruppierung im Pattern fuer konsistente Ausrichtung von Social, CTA, Theme- und Language-Switcher.
- Responsive Tagline-Verhalten ueberarbeitet: Umbruch auf Desktop, kompaktere Typografie im Zwischenbereich und Ausblendung im Mobile-Header.
- Kompaktmodus fuer 992-1220px hinzugefuegt, damit Navigation frueher in den Collapse-/Hamburger-Kontext wechselt und nicht mit der Tagline kollidiert.
- Opera-Hamburger-Robustheit verbessert (Desktop/Mobile) durch Pseudo-Element-Rendering und zusaetzliche Sichtbarkeits-/Layering-Sicherungen.
- Cookie-Consent/Booking-Hotfix: Kein erzwungener Reload bei offenem bzw. per Hash angefordertem Booking-Overlay.
- Neues Integrations-Event `restatify:cookie-consent-changed` fuer Listener.
- Cookie-Banner-Z-Index oberhalb des Booking-Overlays angehoben.
- JS-Unit-Test fuer Cookie-Consent-Test-Hooks und Booking-Guard ergaenzt.

## Kompatibilitaet

- Theme-Version: `1.1.0`
- Keine beabsichtigten Breaking Changes

## Artefakt

- `wp_restatify-base-theme-1.1.0.zip`
