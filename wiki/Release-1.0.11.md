# Release 1.0.11

Version 1.0.11 ist ein kombiniertes Feature- und Maintenance-Release fuer das Restatify Base Theme.

## Highlights

- Header-Hauptnavigation nutzt jetzt ein zuweisbares WordPress-Menue statt automatischer Top-Level-Seitenliste.
- Neue Menueposition `Header Hauptmenue` registriert.
- Admin-Warnhinweis hinzugefuegt, wenn fuer das Hauptmenue noch kein Menue zugewiesen ist.
- Navigation-Walker auf `Walker_Nav_Menu` migriert fuer robuste Menue-/Dropdown-Ausgabe.
- Contact-Block erweitert:
  - flexible Details als Liste
  - Modus je Detail: Text, Link oder Button
  - Reihenfolge im Editor aenderbar
  - optionaler Freitext-Abschnitt
  - Legacy-Migration bestehender Kontaktfelder
- Oracle-Block verbessert:
  - responsive Wortgroessen-Anpassung fuer lange Begriffe
  - Resize-Reflow fuer stabile einzeilige Darstellung

## Technische Einordnung

Aenderungen:

- `inc/theme-setup.php`
- `inc/navigation-walker.php`
- `patterns/restatify-base-header.php`
- `src/contact/block.json`
- `src/contact/index.js`
- `src/contact/editor.css`
- `src/contact/style.css`
- `src/oracle/style.css`
- `src/oracle/view.js`
- `style.css`
- `package.json`
- `readme.txt`
- `README.de.md`
- `wiki/Home.md`
- `wiki/_Sidebar.md`

## Kompatibilitaet

- Theme-Version: 1.0.11
- Ausgehend von: 1.0.10
- Keine beabsichtigten Breaking Changes.

## Validierung

- Build erfolgreich (`npm run build`).
- Release-Paket erfolgreich erzeugt (`npm run package`).
- Artefakt vorhanden: `release/wp_restatify-base-theme-1.0.11.zip`.

## Fazit

Release 1.0.11 verbessert die Menue-Steuerbarkeit im Header fuer reale Produktionsseiten, erweitert den Contact-Block erheblich in der redaktionellen Flexibilitaet und stabilisiert die Oracle-Typografie bei variabler Wortlaenge.
