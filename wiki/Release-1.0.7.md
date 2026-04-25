# Release 1.0.7

Version 1.0.7 ist ein Feature- und UX-Release fuer das Restatify Base Theme mit Fokus auf den neuen Timer-Block und eine robuste Countdown-Darstellung in Editor und Frontend.

## Highlights

- Neuer **Timer**-Custom-Block zur Gutenberg-Bibliothek hinzugefuegt.
- Countdown basiert auf einem festen **Zielzeitpunkt** (Date-Time-Picker), kein Reset bei Refresh.
- **Kalendergenaue** Restzeitberechnung inklusive Jahre und Monate.
- Automatische Einheitenlogik je nach Restzeit (Jahre bis Sekunden).
- Weiche Reflow-Animation beim Wegfall einer fuehrenden Einheit.
- Singular/Plural-Labels je Einheit in Deutsch.
- Verbesserte Lesbarkeit des Timer-Blocks im Block-Editor (Designer), auch bei dunklem Editor-Umfeld.

## Technische Einordnung

Der Timer-Block wurde als normaler Theme-Block unter `/src/timer` implementiert und mit `view.js` um eine persistente Frontend-Countdown-Logik erweitert.

Wesentliche Punkte:

- Block-Attributmodell auf `targetDateTime` als zentrale Quelle ausgerichtet.
- Kalenderbasierte Dekompensation in Jahre/Monate/Tage/Stunden/Minuten/Sekunden statt pauschaler Monatssekunden.
- CI-/Theme-Token-basierte Farbsteuerung fuer Light/Dark im Frontend.
- Editor-spezifische Kontrast-Absicherung fuer konsistente Designer-Lesbarkeit.

## Kompatibilitaet

- Theme-Version: 1.0.7
- Ausgehend von: 1.0.6
- Keine beabsichtigten Breaking Changes fuer bestehende Blöcke.

## Validierung

- Build erfolgreich mit `npm run build`.
- Keine Fehler in den geaenderten Timer-Dateien.
- Frontend-Countdown bleibt bei Reload konsistent (zielzeitpunktbasiert).
- Einheitenspruenge (z. B. Tage -> Stunden) und Reflow visuell verifiziert.

## Installationsartefakt

Fuer WordPress-Installation ist ein neues Release-ZIP vorgesehen:

- `release/wp_restatify-base-theme-1.0.7.zip`

Erzeugung im Theme-Root mit:

```bash
npm run package
```

## Fazit

Release 1.0.7 erweitert die Blockbibliothek substanziell um einen produktionsfaehigen Timer-Block und verbessert gleichzeitig die Editor-UX. Damit steht ein konsistenter Countdown-Block fuer Launch-, Event- und Kampagnenseiten zur Verfuegung.
