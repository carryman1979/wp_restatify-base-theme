# Release 1.0.6

Version 1.0.6 ist ein Integrations- und Stabilitaets-Release fuer das Restatify Base Theme mit Fokus auf den LightStart-Workflow, Wartungsseiten und robuste Ausgabe im Block-Theme-Kontext.

## Highlights

- Statusabhaengiger Header/Footer-Wechsel auf der LightStart-Seitenvorlage:
  - Wartung aktiv: Minimal-Header/-Footer
  - Wartung deaktiviert: vollstaendiger Theme-Header/-Footer (inkl. Theme-Wechsler, Sprachumschalter, Menues)
- Doppelte Head-/Localize-Ausgaben bei deaktivierter Wartung im Block-Theme-Kontext beseitigt
- Wartungsbezogene Rechtsseiten-Fallbacks im Theme finalisiert
- Dokumentation und Release-Dokumentation fuer den Wartungs- und Integrationsfluss nachgezogen

## Technische Einordnung

Die Anpassungen konzentrieren sich auf die Integrationsschicht zwischen Theme und LightStart:

- Klare Trennung zwischen echtem Wartungsmodus und LightStart-Template-Request
- Sichere Hook-Steuerung fuer Header/Footer-Ausgabe
- Deaktivierung der FSE-Style-Buffer-Hooks im Nicht-Wartungsfall zur Vermeidung doppelter `wp_head()`-Nebenwirkungen

## Kompatibilitaet

- Theme-Version: 1.0.6
- Ausgehend von 1.0.5
- Keine bewusst eingefuehrten Breaking Changes in der Block-Bibliothek

## Validierung

- Frontend-Pruefung auf der Startseite mit deaktivierter Wartung:
  - kein Minimal-Header/-Footer
  - voller Theme-Header/-Footer aktiv
- Pruefung auf doppelte Localize-Bloecke und `<title>`: jeweils nur einmal vorhanden
- Syntax-Checks fuer geaenderte PHP-Dateien ohne Fehler

## Fazit

Release 1.0.6 stabilisiert den Betriebsmodus rund um LightStart deutlich: Wartung und regularer Betrieb sind klar getrennt, die Ausgabe ist konsistent, und die Theme-Navigation steht im deaktivierten Wartungsmodus wieder vollstaendig zur Verfuegung.
