# Release 1.2.2

Theme-Patch-Release mit einem konfigurierbaren Ladebild fuer YouTube-Hintergruende.

## Video-Ladebild

In allen 14 Custom-Sektionsbloecken kann im Video-Modus unter **Background &
layout > Video loading image** ein Bild aus der WordPress-Mediathek ausgewaehlt,
ersetzt oder entfernt werden. Das optionale Attribut
`backgroundVideoPosterUrl` hat als Standard den leeren String.

Das Bild ist schon vor Cookie-Zustimmung sichtbar. Auf der Website bleibt es
ueber dem Video, bis der Player tatsaechlich `is-playing` meldet; bei Fehlern
und fuer reduzierte Bewegung bleibt es als Fallback sichtbar. Das bestehende
Farb-Overlay wird weiterhin auf die Hintergrundebene angewendet. Im Editor
erscheint dasselbe Bild lokal und ohne YouTube-Verbindung.

Ohne Ladebild bleibt das bisherige Videoverhalten erhalten. Vorhandene Bild-
Hintergruende und deren gespeicherte Medienauswahl werden nicht veraendert.

## Kompatibilitaet und Hinweise

- Theme-Version: `1.2.2`
- Getestet bis WordPress `6.9`; PHP `8.0+`
- Exakte Shared-Abhaengigkeit: `1.1.0`
- Keine beabsichtigten Breaking Changes.
- YouTube laedt weiterhin erst nach Cookie-Zustimmung; Datenschutzerklaerung
  und Cookie-Hinweise muessen die Einbettung abdecken.

## Artefakt

- `release/wp_restatify-base-theme-1.2.2.zip`
