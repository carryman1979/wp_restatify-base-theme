# Release 1.0.9

Version 1.0.9 ist ein Hotfix-Release fuer das Restatify Base Theme mit Fokus auf stabile Symboldarstellung fuer nicht eingeloggte Nutzer.

## Highlights

- Platzhalter-Quadrate bei Symbolen fuer anonyme Nutzer behoben.
- Cache-Busting-Versionierung fuer Icon-Stylesheets hinzugefuegt.
- Tippfehler im IconsMind-EOT-Fontpfad korrigiert.

## Technische Einordnung

Der Fix adressiert zwei typische Ursachen fuer Icon-Font-Ausfaelle im Frontend:

- Veraltete, gecachte CSS-Auslieferung bei anonymen Requests.
- Inkonsistente Font-Dateireferenz in `assets/iconsMind/style.css`.

Aenderungen:

- `inc/assets.php`
- `assets/iconsMind/style.css`
- `style.css` (Version auf 1.0.9)
- `readme.txt`
- `README.de.md`

## Kompatibilitaet

- Theme-Version: 1.0.9
- Ausgehend von: 1.0.8
- Keine beabsichtigten Breaking Changes.

## Validierung

- Frontend-Asset-Einbindung fuer Icon-CSS verifiziert.
- Font-Dateien oeffentlich erreichbar (inkl. korrekter MIME-Typen) verifiziert.
- Release-Tag `v1.0.9` und GitHub Release veroeffentlicht.

## Fazit

Release 1.0.9 stabilisiert die Symboldarstellung im anonymen Frontend ohne funktionale Nebenwirkungen und ist als direkter Hotfix fuer produktive Seiten gedacht.
