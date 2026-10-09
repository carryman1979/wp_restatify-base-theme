# Restatify Base Theme - Wiki (DE)

Stand: Version 1.2.0, Release-Metadaten fuer WordPress 6.9, PHP 8.0+.

Shared-Abhaengigkeit: exakt `1.1.0`. Das Theme installiert den enthaltenen
PHP-Payload auch ohne aktive Plugins unter
`wp-content/plugins/wp_restatify-shared/versions/1.1.0`.
Lokales Root-Shared hat Vorrang; vorhandene andere Versionen werden nicht entfernt.

Diese Seite ist als zentrale One-Page-Dokumentation für Installation, Entwicklung, Betrieb und Release des Themes gedacht.

## Inhaltsverzeichnis

- Zweck und Architektur
- Enthaltene Blöcke
- Detaillierte Block-Referenz
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

Restatify Base ist ein Custom-WordPress-Theme mit Fokus auf Gutenberg und Site Editor. Die redaktionelle Basis nutzt Standard-WordPress-Mechanismen (Core-Blocks, Patterns, Templates/Template Parts, theme.json) und wird fuer Spezialfaelle durch eigene Blocks ergaenzt.

Technische Eckpunkte:

- Blockbasierte Architektur über `/src` für Quellen und `/build` für kompilierte Assets
- Core-Block-first-Ansatz fuer typische Seiten- und Blog-Inhalte
- Wiederverwendbare Block-Patterns fuer standardisierte Sektionen im Restatify-Stil
- Template- und Template-Part-Struktur fuer Blog- und Seitentypen
- Globale Designsteuerung ueber `theme.json` (Farben, Typografie, Spacing, Tokens)
- Automatische Block-Registrierung über `inc/blocks.php`
- Einheitliche Background- und Layout-Steuerung über gemeinsame Block-Controls
- Theme-kompatible CSS-Variablen für Light- und Dark-Mode sowie Oberflächen und Sektionen
- Klare Trennung zwischen inhaltlicher Block-Entwicklung und deploybaren Build-Artefakten

## Blog-Lesebereich und Artikelnavigation

Einzelne Blogbeiträge verwenden `templates/single.html`. Die Vorlage stellt Titel und Metadaten vor dem optionalen Beitragsbild dar und setzt den Inhalt in einen begrenzten, responsiven Lesebereich mit theme-abhängiger Oberfläche. Unter dem Artikel erscheinen Links zum vorherigen und nächsten Beitrag, danach der WordPress-Kommentarbereich und anschließend Related Articles. Kommentare müssen für den Beitrag beziehungsweise in den Diskussionseinstellungen aktiviert sein. Für öffentliche Diskussionen unter **Einstellungen > Diskussion** die Registrierungspflicht deaktivieren und **„Der Kommentarautor muss Name und E-Mail-Adresse ausfüllen“** aktivieren. WordPress prüft die E-Mail-Adresse auf ein gültiges Format, bestätigt damit aber nicht, dass sie dem Kommentierenden gehört. Die Schutzoptionen liegen unter **Einstellungen > Kommentarsicherheit**: Honeypot kann allein oder zusammen mit Google reCAPTCHA v3 beziehungsweise Cloudflare Turnstile genutzt werden. Die CAPTCHA-Schlüssel sind eigene Theme-Einstellungen und unabhängig von Restatify Forms. Fehlende Schlüssel, ein fehlendes Shared-Verifier-Modul, ungültige Tokens und nicht erreichbare Prüfserver blockieren den Kommentar. Die Datenschutzhinweise müssen die Übertragung von IP-Adresse und Prüfdaten an den gewählten Anbieter abdecken. Über den dynamischen Block `restatify/blog-navigation` führt der Übersichtslink zur in WordPress unter **Einstellungen > Lesen** festgelegten Beitragsseite. Ein schwebender Symbolbutton am linken Bildschirmrand öffnet eine seitliche Artikelliste mit veröffentlichten Beiträgen der aktiven Polylang-Sprache. Bei mehr als zehn Artikeln ist die Liste seitenweise navigierbar; der Dialog lässt sich per Schaltfläche, Escape-Taste und Klick auf den Hintergrund schließen. Die Blog-Grid-Vorlage nutzt Beitragsbilder, zentriert die Karten in einem begrenzten Raster mit höchstens drei Spalten und hält größere Abstände zwischen ihnen.

### Endloses Scrollen im Restatify Blog Post Grid

Nur das Pattern **Restatify Blog Post Grid** (`restatify-blog-query--grid`) lädt beim Scrollen weitere Artikel nach; die WordPress-Beitragsseite und Related Articles werden nicht verändert. Es gibt keine sichtbare Pagination. Der WordPress-Block „Abfrage-Paginierung > Nächste Seite“ bleibt als unsichtbarer technischer Seitenzeiger im Grid erhalten und darf im Editor nicht entfernt werden. Bestehende Grids mit der bisherigen Pagination werden ebenfalls automatisch umgestellt, ohne die gespeicherten Blockattribute zu verändern.

Die erste Gruppe enthält standardmäßig sechs Artikel. Innerhalb von zwei Bildschirmhöhen vor dem Grid-Ende wird eine weitere Gruppe samt Bildern vorgeladen und ab 300 Pixeln vor dem Ende angehängt. Danach wird jeweils die nächste Gruppe vorgeladen, nicht die gesamte Artikelsammlung. Reihenfolge, Filter, Kartenvorlage und Polylang-Sprache bleiben durch die von WordPress erzeugten Folgeseiten erhalten. Bereits vorhandene Artikel werden nicht doppelt angehängt.

Ein laufender beziehungsweise vorgeladener Abruf wird im Arbeitsspeicher wiederverwendet. Zusätzlich speichert `sessionStorage` bis zu sechs Kartengruppen für fünf Minuten im aktuellen Browser-Tab; es werden keine vollständigen Seiten oder Formulare gespeichert. Bilder nutzen den normalen Browser-HTTP-Cache. Bei gesperrtem Browser-Speicher funktioniert das Vorladen weiterhin im Arbeitsspeicher. Bei Ladefehlern bleiben vorhandene Karten erhalten und eine Fehlermeldung mit „Erneut versuchen“ erscheint. Am Ende werden keine weiteren Requests gestellt. Ohne JavaScript bleiben die initialen Artikel sichtbar und ein Hinweis erklärt, dass JavaScript für weitere Artikel benötigt wird; es gibt auch dann keine Pagination.

Manuelle Integrationstests nach Änderungen am Blog-Styling:

- Einzelbeitrag mit Titel, Metadaten, Beitragsbild und langen Absätzen auf Desktop und Mobilgeräten prüfen; auch einen Beitrag ohne Beitragsbild kontrollieren.
- Übersichtslink auf die konfigurierte Beitragsseite und seine Tastaturbedienbarkeit prüfen.
- Dialog mit Maus und Tastatur öffnen, per Escape und Hintergrundklick schließen sowie Fokus-Rückgabe und Tastaturbedienung der Artikellinks prüfen.
- Schwebenden Symbolbutton am linken Bildschirmrand während des Scrollens und außerhalb des Chat-Popups prüfen; die Artikelliste mit aktuellem Beitrag und bei mehr als zehn Einträgen die Pagination kontrollieren; aktives Polylang berücksichtigen.
- Einzelbeitrag mit Kommentaren geöffnet und geschlossen prüfen; Vor-/Nächster-Links am ältesten und neuesten Beitrag sowie Kommentarformular und Antworten kontrollieren.
- In **Einstellungen > Kommentarsicherheit** Honeypot und beide Provider mit gültigen und fehlenden Schlüsseln testen; leere/ungültige Tokens und simulierte Provider-Ausfälle müssen Kommentare zuverlässig blockieren. Formular im eingeloggten und ausgeloggten Zustand prüfen.
- Blog-Grid bei sechs und bei weniger Beiträgen auf zentrierte Darstellung, maximal drei Spalten, responsive Abstände sowie mit und ohne Beitragsbild prüfen.
- Blog-Grid mit mindestens 13 Beiträgen: keine sichtbare Pagination, Vorladen ohne Anhängen, Anhängen beim Scrollen, genau ein paralleler Abruf je Grid und Ende ohne zusätzliche Abrufe prüfen. Bei erneuter Navigation im selben Tab muss der Fünf-Minuten-Cache verwendet werden; Cache-Grenze von sechs Gruppen, Fehler/Retry, mehrere Grids, Sprachfilter und deaktiviertes JavaScript prüfen.
- Lesebereich, Artikelliste und Karten in Light-/Dark-Mode auf Kontrast prüfen; längere Übersetzungen und RTL-Darstellung mit Polylang testen.

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

Feldgenaue Dokumentation fuer alle Inspector-Einstellungen, Textfelder, Links und Button-Beschriftungen:

- Siehe [Custom Blocks Referenz](Custom-Blocks-Referenz)

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

## Letzte Aenderungen (1.1.0)

- Header-Hotfixs als zusammenhaengendes Release gebuendelt: verbesserte Brand/Nav/Actions-Aufteilung mit `navbar-actions` im Header-Pattern.
- Tagline-Responsiveness korrigiert: sauberer Umbruch auf Desktop, kompaktere Typografie im Zwischenbereich und Ausblendung im Mobile-Header.
- Kompaktmodus fuer 992-1220px eingefuehrt, damit Navigation/Social frueher in den Collapse-Kontext wechseln und kein Ueberlappen mehr entsteht.
- Opera-Hamburger-Rendering abgesichert (Desktop und Mobile) durch robustes Pseudo-Element-Icon plus Sichtbarkeits-/Layering-Fallbacks.
- Cookie-Consent + Booking-Overlay-Fix: kein erzwungener Reload bei offenem/angefordertem Booking; neues Event `restatify:cookie-consent-changed` fuer Integrationen.
- Cookie-Banner-Z-Index ueber Booking-Overlay angehoben.
- JS-Unit-Test fuer Cookie-Consent-Booking-Guard ergänzt.

## Release-Prep Status (2026-07-21)

- Fremd-PR auf `main` vor Release-Vorbereitung gemerged und Hotfix-Branch auf aktualisierter Basis erstellt.
- Versionsstand auf `1.1.0` in Theme-Metadaten und Paket-Metadaten synchronisiert.
- README, Wiki und GitHub-Release-Notes fuer den gebuendelten Hotfix-Stand aktualisiert.
- Dokumentation und Release-Notizen auf Version 1.0.16 aktualisiert.
- Footer-/Customizer-Refactorings und Look-and-Feel-Polish fuer den finalen Rollout konsolidiert.
- Release-ZIP fuer 1.0.16 wurde neu gebaut und als aktueller Stand veroeffentlicht.

- Offene lokale Theme- und Wiki-Aenderungen als Maintenance-Release konsolidiert.
- Versionsstand auf `1.1.1` in Theme-Metadaten und Paket-Metadaten synchronisiert.
- README, Wiki und Release-Workflow fuer den koordinierten Mehr-Repo-Stand aktualisiert.

## Letzte Aenderungen (1.0.13)

- Finale Konsolidierung unveroeffentlichter lokaler Zwischenstaende seit 1.0.12.
- Release-Artefakte oberhalb der letzten GitHub-Release-Version lokal bereinigt.
- Release- und Wiki-Dokumentation fuer den finalen 1.0.13-Stand synchronisiert.
- Packaging-Workflow fuer reproduzierbare finale Release-Builds verifiziert.

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

- [Release 1.1.1](Release-1.1.1)
- [Release 1.0.16](Release-1.0.16)
- [Release 1.0.12](Release-1.0.12)
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
