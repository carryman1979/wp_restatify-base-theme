# Restatify Base - Dokumentation (DE)

Stand: Version 1.0.14, getestet bis WordPress 6.9.

Restatify Base ist das benutzerdefinierte WordPress-Basistheme der Restatify UG (haftungsbeschraenkt).

Das Theme folgt bewusst den Standard-WordPress-Mechanismen:

- Core-Blocks fuer Inhalte,
- Block-Patterns fuer wiederverwendbare Sektionen,
- Templates und Template Parts fuer die Seitenstruktur,
- theme.json fuer globale Styles und Design-Tokens.

Ergaenzend bringt das Theme eine gezielte eigene Gutenberg-Blockbibliothek fuer erweiterte, markenspezifische Sektionen mit.

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
- Timer

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

## Timer Block

Timer ist ein Zielzeitpunkt-Countdown mit automatischer Einheitenlogik und kalendergenauer Berechnung.

Konfigurierbar sind unter anderem:

- Zielzeitpunkt per Date-Time-Picker.
- Optionaler Titel, Untertitel und Beschreibung.
- Automatische Anzeige der passenden Einheiten je nach Restzeit (Jahre bis Sekunden).
- Weiche Reflow-Animation beim Wegfall einer fuehrenden Einheit.
- Singular/Plural je Einheit (z. B. 1 Jahr vs. 2 Jahre).
- CI-konforme Farben inkl. verbesserter Lesbarkeit im Block-Editor.

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

### Wo finde ich wichtige und optionale Footer-Einstellungen?

Im Customizer sind Footer-Einstellungen jetzt getrennt:

- Footer Core (Recommended): zentrale Slogan- und Kontaktfelder.
- Footer Expert Settings (Optional): soziale Links, Trust-Badges, vCard.

### Kann ich direkt ein GitHub-Source-ZIP installieren?

Beim Theme bitte bevorzugt ein Release-ZIP verwenden, das aus dem Workspace erzeugt wurde.
Ein reines Source-ZIP kann erforderliche kompilierte Block-Assets nicht enthalten.

Release-ZIP erzeugen:

1. npm install
2. npm run package

Das ZIP liegt danach unter /release.

## Changelog

### 1.0.14

- Hotfix-Rebuild ohne Versionssprung: defektes 1.0.14-Release-Paket ersetzt.
- Shared-Runtime-Resolver fuer Theme-Zugriff auf `PluginState` mit versionspfadbasiertem und Legacy-Fallback hinzugefuegt.
- Release-ZIP enthaelt nun die Shared-Install-Payload fuer stabile Aufloesung der Legal-Notice-Abhaengigkeit.

### 1.0.12

- Neue Pattern-Kategorie `Restatify Blog` fuer den Blog-Workflow hinzugefuegt.
- Neue Core-basierte Blog-Starter-Patterns bereitgestellt: Blog Hero, Blog Post Grid und Related Posts.
- Einheitliches Query-Loop-Card-Styling im Restatify-Stil fuer Blog-Listen und Related-Posts ergaenzt.
- Theme-/Readme-/Wiki-Beschreibungen auf den Standard-WordPress-Ansatz (Core-Blocks, Patterns, Templates, theme.json) konkretisiert.

### 1.0.11

- Header-Navigation auf ein zuweisbares WordPress-Menue umgestellt, statt Seiten automatisch aus der Top-Level-Struktur zu ziehen.
- Neue Menueposition fuer das Hauptmenue hinzugefuegt (`Header Hauptmenue`).
- Admin-Hinweis ergaenzt, wenn fuer das Hauptmenue noch kein Menue zugewiesen ist.
- Navigation-Walker von `Walker_Page` auf `Walker_Nav_Menu` umgestellt fuer saubere Menue- und Dropdown-Ausgabe.
- Contact-Block erweitert: flexible Detailliste (Text/Link/Button), Reihenfolge-Aenderung, optionaler Beschreibungstext und Legacy-Migration alter Kontaktfelder.
- Contact-Block-Styling fuer Links/Buttons und mehrzeilige Texte verbessert.
- Oracle-Block verbessert: responsive Wortgroessen-Anpassung, damit lange Woerter stabil einzeilig bleiben (inklusive Resize-Reflow).

### 1.0.10

- Metrics-Block korrigiert: Fullscreen- und Parallax-Modus greifen im Frontend jetzt zuverlaessig.
- Konfigurierbare Zahlenanimation fuer Metrics hinzugefuegt: kein Effekt oder Count-Up.
- Animationsdauer fuer Count-Up einstellbar gemacht (Standard: 1500 ms).
- Count-Up startet genau einmal und erst dann, wenn alle Zahlen gleichzeitig im sichtbaren Bereich sind.
- Mission-Block-Verbesserungen fuer Subheading-Hierarchie und Zeilenumbruch-Darstellung ergaenzt.

### 1.0.9

- Platzhalter-Quadrate bei Icon-Fonts fuer anonyme Nutzer behoben, indem Cache-Busting-Versionen fuer Icon-Stylesheets gesetzt wurden.
- Tippfehler im IconsMind-EOT-Fontpfad korrigiert, damit Dateiname und ausgelieferte Assets uebereinstimmen.

### 1.0.8

- Bugfix fuer Scroll-Lock im Wartungsmodus: anonyme Nutzer werden nicht mehr in einem Consent-Pending-Lock festgehalten, wenn der Cookie-Banner absichtlich ausgeblendet ist.
- Darstellungsfehler bei Gutenberg-Standardbuttons mit mehrzeiligem Label im Frontend behoben.
- Robuste finale CSS-Overrides fuer Button-Pseudo-Elemente ergaenzt, um Kollisionen mit Legacy-Styles zu vermeiden.

### 1.0.7

- Neuer Timer-Block mit Zielzeitpunkt per Date-Time-Picker hinzugefuegt.
- Kalendergenaue Countdown-Berechnung inkl. Jahre und Monate umgesetzt.
- Automatische Sichtbarkeitslogik fuer Einheiten und Reflow-Animation beim Wechsel ergaenzt.
- Singular-/Plural-Labels je Einheit (DE) in Editor und Frontend hinzugefuegt.
- Lesbarkeit des Timer-Blocks im Designer fuer Light-/Dark-Kontext verbessert.

### 1.0.6

- LightStart-Integration fuer Wartungsseiten verfeinert: Header/Footer wechseln jetzt statusabhaengig zwischen Minimal-Chrome (Wartung aktiv) und vollem Theme-Chrome (Wartung deaktiviert).
- Doppelte Head-Ausgaben auf Block-Themes bei deaktiviertem Wartungsmodus beseitigt.
- Wartungsbezogene Rechtsseiten-Fallbacks und Kompatibilitaetsweiterleitungen ergaenzt.
- Dokumentation und Konfigurationspfade fuer Wartungs-/Rechtslinks erweitert.

### 1.0.4

- Customizer-UX verbessert: Trennung zwischen Footer-Core und optionalen Expert-Einstellungen.
- Wichtige Footer-Kontaktfelder prominenter positioniert, inkl. Pflicht-Attribut fuer E-Mail.
- Dokumentation zur neuen Einstellungsstruktur aktualisiert.

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
