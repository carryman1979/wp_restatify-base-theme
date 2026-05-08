# Release 1.0.12

Version 1.0.12 ist ein Blog-Starter-Release fuer das Restatify Base Theme auf Basis der WordPress-Core-Mechanismen.

## Highlights

- Neue Pattern-Kategorie `Restatify Blog` fuer redaktionelle Blog-Layouts.
- Neue Core-basierte Blog-Starter-Patterns:
  - Blog Hero
  - Blog Post Grid (Query Loop)
  - Related Posts
- Einheitliches Query-Loop-Card-System im Restatify-Stil (Kartenlayout, Meta, Pagination, responsive Breakpoints).
- Theme-/Readme-/Wiki-Beschreibungen auf den Standard-WordPress-Ansatz konkretisiert.

## Technische Einordnung

Aenderungen:

- `inc/theme-setup.php`
- `patterns/restatify-base-blog-hero.php`
- `patterns/restatify-base-blog-post-grid.php`
- `patterns/restatify-base-blog-related-posts.php`
- `style.css`
- `readme.txt`
- `README.de.md`
- `wiki/Home.md`
- `wiki/_Sidebar.md`
- `package.json`

## Kompatibilitaet

- Theme-Version: 1.0.12
- Ausgehend von: 1.0.11
- Keine beabsichtigten Breaking Changes.

## Validierung

- Pattern-Dateien auf PHP-Fehler geprueft.
- Build erfolgreich (`npm run build`).
- Release-Paket erfolgreich erzeugt (`npm run package`).
- Artefakt vorhanden: `release/wp_restatify-base-theme-1.0.12.zip`.

## Fazit

Release 1.0.12 liefert ein unmittelbar nutzbares Blog-Starter-Kit im Restatify-Stil, ohne neue Plugin-Abhaengigkeiten und voll auf Standard-WordPress-Workflows ausgerichtet.
