# Restatify Base Theme - Wiki (DE)

Stand: Version 1.0.11, getestet bis WordPress 6.9.

Diese Seite ist als zentrale One-Page-Dokumentation für Installation, Entwicklung, Betrieb und Release des Themes gedacht.

## Inhaltsverzeichnis

- Zweck und Architektur
- Enthaltene Blöcke
- Schnellstart Installation
- Externe Installation mit Release-ZIP
- Entwicklung lokal
- Build und Assets
- Block-Hintergründe und Variablen
- Customizer und Footer-Struktur
- Troubleshooting
- Release-Workflow
- Verlinkte Projektdokumente

---

## Zweck und Architektur

Restatify Base ist ein Custom-WordPress-Theme mit Fokus auf Gutenberg, Site Editor und einer eigenen section-basierten Blockbibliothek.

Technische Eckpunkte:

- Blockbasierte Architektur über `/src` für Quellen und `/build` für kompilierte Assets
- Automatische Block-Registrierung über `inc/blocks.php`
- Einheitliche Background- und Layout-Steuerung über gemeinsame Block-Controls
- Theme-kompatible CSS-Variablen für Light- und Dark-Mode sowie Oberflächen und Sektionen
- Klare Trennung zwischen inhaltlicher Block-Entwicklung und deploybaren Build-Artefakten

---

## Enthaltene Blöcke

Das Theme enthält aktuell:

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

Der Oracle-Block ergaenzt die Bibliothek um eine futuristische Wortpaar-Animation mit periodischem Logo-Overlay und erweiterten Layout-Optionen.
Der Timer-Block ergaenzt die Bibliothek um einen zielzeitpunktbasierten Countdown mit kalendergenauer Anzeige (Jahre bis Sekunden).

---

## Schnellstart Installation

### Option A: Theme manuell installieren

1. Theme als ZIP in WordPress hochladen.
2. Unter Design > Themes aktivieren.
3. Site Editor öffnen und Restatify-Blöcke einsetzen.

### Option B: Theme-Ordner kopieren

1. Theme nach `wp-content/themes/wp_restatify-base-theme` kopieren.
2. Theme aktivieren.
3. Site Editor verwenden.

---

## Externe Installation mit Release-ZIP

Für externe Installationen sollte ein Release-ZIP mit kompilierten Assets verwendet werden.

### ZIP erzeugen

1. Im Theme-Ordner Abhängigkeiten installieren:

```bash
npm install
```

2. Paket bauen:

```bash
npm run package
```

3. Das Ergebnis liegt unter `release/wp_restatify-base-theme-<version>.zip`.

Hinweis:

- Für dieses Theme nicht auf ein reines Source-ZIP verlassen, da für Gutenberg die kompilierten Assets aus `/build` benötigt werden.

---

## Entwicklung lokal

1. Abhängigkeiten installieren:

```bash
npm install
```

2. Entwicklung mit Watch:

```bash
npm run start
```

3. Produktionsbuild erzeugen:

```bash
npm run build
```

4. Paket für Deployment bauen:

```bash
npm run package
```

---

## Build und Assets

Struktur:

- `/src` enthält Block-Quellcode, Styles und Block-Metadaten
- `/build` enthält kompilierte, deploybare Assets
- `inc/blocks.php` registriert Blöcke aus dem Build-Verzeichnis

Wichtig:

- Ohne gültigen Build können Blöcke in externer Installation nicht korrekt geladen werden.
- Änderungen unter `/src` sollten vor Tests außerhalb des lokalen Setups immer neu gebaut werden.

---

## Block-Hintergründe und Variablen

Das Theme injiziert Default-Hintergrundvariablen für Blöcke zur Laufzeit.

Relevant:

- Registrierung und Variablen-Handling in `inc/blocks.php`
- Theme-Tokens in `style.css`, zum Beispiel Farben, Oberflächen und Zustände
- Gemeinsame Background- und Layout-Logik in `src/shared/*`

Empfehlung:

- Für neue Blöcke denselben Variablen- und Naming-Standard beibehalten, damit sich die Blöcke konsistent in das Theme-System einfügen.

---

## Customizer und Footer-Struktur

Die jüngeren Theme-Anpassungen haben die Footer-Konfiguration klarer gegliedert.

Aufteilung:

- Footer Core: wichtige Slogan-, Kontakt- und Basisangaben für die tägliche Pflege
- Footer Expert Settings: optionale soziale Links, Trust-Badges, vCard und erweiterte Details

Ziel dieser Struktur:

- schnellere Pflege der wichtigsten Inhalte
- weniger Unordnung im Customizer
- bessere deutsche Beschriftungen und klarere Orientierung im Backend

---

## Troubleshooting

### Blöcke erscheinen nicht im Editor

Prüfen:

- Theme aktiv?
- Build vorhanden unter `/build/*/block.json`?
- Nach Änderungen `npm run build` ausgeführt?

### Styles fehlen im Frontend

Prüfen:

- Kompilierte Assets in `/build` aktuell?
- Block korrekt in `inc/blocks.php` registriert?

### Hintergrundbild oder Overlay verhält sich unerwartet

Prüfen:

- Block-Attribute für Background und Overlay gesetzt?
- CSS-Variablen in den Theme-Styles verfügbar?
- Theme-Modus light oder dark korrekt gesetzt?

### Externe Installation zeigt Fehler

Prüfen:

- Wurde ein Release-ZIP aus `npm run package` verwendet?
- Wurde nicht versehentlich ein Source-ZIP ohne Build genutzt?

---

## Release-Workflow

1. Versionsstände aktualisieren:
	- `style.css`
	- `package.json`
	- `readme.txt`
	- optional `README.de.md`
2. Changelog pflegen.
3. Produktionsbuild validieren:

```bash
npm run build
```

4. Release-ZIP erzeugen:

```bash
npm run package
```

5. Smoke-Test in externer WordPress-Testinstanz durchfuehren.
6. Commit, Tag und Push ausfuehren.
7. GitHub Release Notes ergaenzen.

---

## Letzte Aenderungen (1.0.11)

- Header-Hauptnavigation auf zuweisbares WordPress-Menue umgestellt (`primary_menu`) statt automatischer Top-Level-Seitenliste.
- Neue Menueposition `Header Hauptmenue` in den Theme-Menuepositionen registriert.
- Admin-Hinweis integriert, falls fuer `Header Hauptmenue` noch kein Menue zugewiesen ist.
- Navigation-Walker auf `Walker_Nav_Menu` migriert, damit Menueeintraege, aktive Zustande und Dropdowns robust verarbeitet werden.
- Contact-Block erweitert um flexible Detailelemente mit Modus Text/Link/Button, inkl. Reihenfolge und Legacy-Migration.
- Oracle-Block verbessert mit dynamischer Wortgroessen-Anpassung und Resize-Reflow fuer lange Begriffe.

## Letzte Aenderungen (1.0.10)

- Metrics-Block: Fullscreen- und Parallax-Modus im Frontend stabilisiert.
- Neue konfigurierbare Zahlenanimation im Metrics-Block: kein Effekt oder Count-Up.
- Count-Up-Dauer konfigurierbar (Default 1500 ms).
- Count-Up startet nur einmal und erst wenn alle Zahlen gleichzeitig sichtbar sind.
- Konsistente Release-Versionierung auf 1.0.10 inklusive neuem ZIP-Artefakt.

## Letzte Aenderungen (1.0.9)

- Platzhalter-Quadrate bei Symbolen fuer anonyme Nutzer behoben.
- Cache-Busting-Versionen fuer Icon-Stylesheets (`mobirise2.css`, `icon54-v2/style.css`, `socicon/css/styles.css`) ergaenzt.
- Tippfehler im IconsMind-EOT-Fontpfad korrigiert (`icons-mind.eot`).

## Letzte Aenderungen (1.0.7)

- Neuer Timer-Block mit Date-Time-Picker fuer den Zielzeitpunkt.
- Kalendergenaue Differenzlogik fuer Jahre/Monate/Tage/Stunden/Minuten/Sekunden.
- Automatische Einheiten-Sichtbarkeit mit Reflow-Animation beim Wegfall einer fuehrenden Einheit.
- Singular-/Plural-Labels je Einheit in Deutsch ergaenzt.
- Verbesserte Lesbarkeit des Timer-Blocks im Block-Editor (Designer) bei dunklen Hintergruenden.

## Letzte Aenderungen (1.0.6)

- LightStart-Wartungsvorlage schaltet jetzt korrekt zwischen Minimal- und Voll-Theme-Chrome.
- Doppelte Head-/Localize-Ausgaben in Block-Themes bei deaktivierter Wartung wurden entfernt.
- Wartungsbezogene Rechtsseiten/Fallbacks wurden in Theme und Doku vereinheitlicht.
- Release- und Wiki-Dokumentation fuer den Wartungs-Workflow wurde erweitert.

Aktuelle release-spezifische Zusammenfassung:

- [Release 1.0.11](Release-1.0.11)
- [Release 1.0.10](Release-1.0.10)
- [Release 1.0.9](Release-1.0.9)
- [Release 1.0.7](Release-1.0.7)
- [Release 1.0.6](Release-1.0.6)
- [Release 1.0.5](Release-1.0.5)

---

## Verlinkte Projektdokumente

- EN Readme
- DE Readme
- Release-Tags in GitHub
- Packaging-Skript `scripts/create-release-zip.ps1`