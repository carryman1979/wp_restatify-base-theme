# Restatify Base Theme

Restatify Base ist das benutzerdefinierte WordPress-Basistheme der Restatify UG (haftungsbeschränkt).

Das Theme bringt eine eigene Gutenberg-Blockbibliothek und abschnittsbasierte Layouts für moderne Business-Websites mit. Es ist auf den Block-Editor und Site Editing ausgelegt und dient als gestalterische Grundlage für die Restatify-Projekte.

## Kernfunktionen

- Full Site Editing auf Basis eines eigenen Block-Themes
- Eigene Gutenberg-Blockbibliothek für Marketing- und Business-Seiten
- Theme-weite Farb- und Layout-Variablen
- Eigene Sektionen für Hero, Services, Studies, Testimonials, FAQ, Pricing und weitere Inhalte
- Futuristischer Oracle-Block mit Wortpaar-Animation und Logo-Overlay
- Getrennte Footer-Konfiguration für wichtige Basiswerte und optionale Experteneinstellungen

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

## Theme-Ausrichtung

Das Theme ist auf moderne, abschnittsbasierte Unternehmensseiten ausgelegt. Inhaltliche Bausteine sollen sich im Site Editor schnell kombinieren lassen, ohne dass jedes Layout individuell per Custom Code gebaut werden muss.

## Customizer und Footer

Die Footer-Konfiguration ist bewusst in zwei Bereiche getrennt:

- Footer Core: zentrale und regelmäßig gepflegte Kontakt- und Basisangaben
- Footer Expert Settings: optionale soziale Links, Trust-Elemente und erweiterte Einstellungen

Dadurch bleiben die wichtigsten Felder im Alltag schneller erreichbar und weniger wichtige Optionen stören die Grundkonfiguration nicht.

## Entwicklung

Das Theme nutzt Quellcode unter `/src` und gebaute Assets unter `/build`.

Für Entwicklung und Release gilt in der Regel:

- `npm install`
- `npm run build` für Produktions-Assets
- `npm run start` für den Watch-Modus während der Entwicklung

Für externe Installationen sollte bevorzugt ein Release-ZIP mit kompilierten Assets verwendet werden.