# Release 1.0.10

Version 1.0.10 ist ein Bugfix- und Feature-Release fuer das Restatify Base Theme mit Fokus auf den Metrics-Block und konsistente Release-Artefakte.

## Highlights

- Metrics-Block Fullscreen- und Parallax-Verhalten im Frontend stabilisiert.
- Konfigurierbare Zahlenanimation im Metrics-Block hinzugefuegt:
  - `none` (keine Animation)
  - `count-up` (hochzaehlend)
- Animationsdauer konfigurierbar (Default: 1500 ms).
- Count-Up startet genau einmal und erst dann, wenn alle Metric-Zahlen sichtbar sind.
- Release-Versionierung auf 1.0.10 konsolidiert und neues ZIP-Artefakt erzeugt.

## Technische Einordnung

Aenderungen:

- `src/metrics/block.json`
- `src/metrics/index.js`
- `src/metrics/view.js`
- `src/metrics/style.css`
- `style.css`
- `package.json`
- `readme.txt`
- `README.de.md`

## Kompatibilitaet

- Theme-Version: 1.0.10
- Ausgehend von: 1.0.9
- Keine beabsichtigten Breaking Changes.

## Validierung

- Build erfolgreich (`npm run build`).
- Release-Paket erfolgreich erzeugt (`npm run package`).
- Artefakt vorhanden: `release/wp_restatify-base-theme-1.0.10.zip`.

## Fazit

Release 1.0.10 behebt offene Layoutprobleme im Metrics-Block und erweitert das Theme um eine performante, konfigurierbare Count-Up-Animation mit sauberem One-Time-Triggerverhalten.
