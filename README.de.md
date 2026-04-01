# Restatify Base - Dokumentation (DE)

Stand: Version 1.0.3, getestet bis WordPress 6.9.

Restatify Base ist das benutzerdefinierte WordPress-Basistheme der Restatify UG (haftungsbeschraenkt).

Das Theme bringt eine eigene Gutenberg-Blockbibliothek und abschnittsbasierte Layouts fuer moderne Business-Websites mit.

## Enthaltene Custom Blocks

- Hero
- Services
- Studies
- Testimonials
- Mission
- Metrics
- Insight
- Gallery
- FAQ
- Pricing
- Contact
- Clients
- Oracle

## Oracle Block

Oracle ist eine futuristische Wortpaar-Animation mit periodischem Logo-Overlay.

Konfigurierbar sind unter anderem:

- Wortpaare (pro Zeile ein Paar, getrennt mit "|").
- Zyklusdauer der Wortanimation.
- Intervall und Sichtdauer des Logo-Overlays.
- Drift-Range fuer die animierte Positionsverschiebung.
- Wortfarben, Glow-Farbe und Shell-Gradientfarben.
- Optionales Fallback-Logo (wenn kein Site-Logo erkannt wird).
- Standard-Background/Layout-Optionen (Bild, Overlay, Parallax, Fullscreen).

## Installation

1. Theme-Ordner nach /wp-content/themes/wp_restatify-base-theme kopieren.
2. In WordPress unter Design > Themes das Theme Restatify Base aktivieren.
3. Site Editor oeffnen und die Restatify-Blocks einfuegen.

Fuer externe Installationen sollte ein Release-ZIP mit kompilierten /build-Assets genutzt werden.

## Entwicklung

Das Theme nutzt Quellcode unter /src und gebaute Assets unter /build.

1. Im Theme-Ordner npm install ausfuehren.
2. Fuer Produktions-Assets npm run build ausfuehren.
3. Fuer Entwicklungsmodus mit Watch npm run start nutzen.

Die Block-Registrierung sowie Default-CSS-Variablen fuer Block-Hintergruende werden in /inc/blocks.php verwaltet.

## FAQ

### Welcher Editor wird unterstuetzt?

Das Theme ist auf den Block-Editor (Gutenberg) und Site Editing ausgelegt.

### Muss vor Produktion ein Build ausgefuehrt werden?

Ja. Nach Aenderungen unter /src sollten Assets mit npm run build neu erzeugt werden.

### Wo sind die Default-Hintergruende und Variablen definiert?

Die Standard-Hintergrundvariablen pro Block werden in /inc/blocks.php injiziert.

### Kann ich direkt ein GitHub-Source-ZIP installieren?

Beim Theme bitte bevorzugt ein Release-ZIP verwenden, das aus dem Workspace erzeugt wurde.
Ein reines Source-ZIP kann erforderliche kompilierte Block-Assets nicht enthalten.

Release-ZIP erzeugen:

1. npm install
2. npm run package

Das ZIP liegt danach unter /release.

## Changelog

### 1.0.3

- SVG/SVGZ-Upload fuer Administratoren um Sanitizing-Hardening erweitert.
- Upload- und Sideload-Prefilter fuer SVG-Dateien hinzugefuegt (inkl. WP-CLI/Import-Pfade).
- MIME- und Dateityp-Validierung fuer SVG in WordPress-Uploadchecks beibehalten.

### 1.0.2

- Neuer Oracle-Block mit futuristischer Wortpaar-Animation hinzugefuegt.
- Sequenzmodus (Reihenfolge/Zufall), Timing und Logo-Overlay-Intervall konfigurierbar gemacht.
- Farbtuning und Paar-Transitionsverhalten des Oracle-Blocks verbessert.

### 1.0.1

- Reproduzierbarer Release-ZIP-Workflow hinzugefuegt.
- Hinweise fuer externe Installation mit produktionsfaehigem Paket ergaenzt.

### 1.0.0

- Erste oeffentliche Version.
- Restatify Gutenberg-Blockbibliothek integriert.
- Gemeinsame Background/Layout-Steuerung fuer Sektionen eingebunden.

## Lizenz

Restatify Base WordPress Theme, (C) 2026 Thomas Hoffermann.

Veroeffentlicht unter GPL v2 oder spaeter.
