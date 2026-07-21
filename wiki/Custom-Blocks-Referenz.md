# Custom Blocks Referenz (DE)

Stand: Version 1.1.0

Diese Seite dokumentiert alle vorhandenen Restatify-Custom-Blocks mit Fokus auf:

- Einstellmoeglichkeiten in den Inspector-Panels
- konfigurierbare Inhalte (Textfelder, RichText, Links, Medien)
- Schalter fuer Sichtbarkeit
- Button-Beschriftungen und Link-Felder
- block-spezifische Besonderheiten

Hinweis zur gemeinsamen Basis:
Alle Blocks nutzen das gemeinsame Panel fuer Hintergrund und Layout.
Damit sind pro Block verfuegbar:

- Hintergrundbild aktivieren/deaktivieren
- Hintergrundbild waehlen/ersetzen
- Overlay aktivieren/deaktivieren
- Overlay-Farbe
- Overlay-Deckkraft
- Parallax an/aus
- Fullscreen an/aus

## 1) Hero

Block-Name: restatify/hero
Quellen: src/hero/block.json, src/hero/index.js

Inspector Panels:
- Content
  - Tagline (Text)
  - Heading (Text)
  - Text (mehrzeilig)
  - Reset content (setzt Tagline, Heading, Text zurueck)
- Buttons
  - Button 1 label (Text)
  - Button 1 link (Link)
  - Button 2 label (Text)
  - Button 2 link (Link)
- Content visibility
  - Show tagline
  - Show heading
  - Show text
  - Show button 1
  - Show button 2

Inline editierbare Inhalte im Block:
- Tagline
- Heading
- Text

Buttons:
- Button 1: Beschriftung + URL
- Button 2: Beschriftung + URL

## 2) Services

Block-Name: restatify/services
Quellen: src/services/block.json, src/services/index.js

Inspector Panels:
- Content
  - Label (Text)
  - Heading (mehrzeilig)
- Feature items
  - Item 1 title
  - Item 1 text
  - Item 2 title
  - Item 2 text
  - Item 3 title
  - Item 3 text
- Image and button
  - Card image (Bild waehlen/ersetzen/entfernen)
  - Button label
  - Button link
- Button color overrides (optional)
  - Light: Normal color, Highlight color
  - Dark: Normal color, Highlight color
  - Reset button colors to default
- Content visibility
  - Show label
  - Show heading
  - Show item 1 title/text
  - Show item 2 title/text
  - Show item 3 title/text
  - Show button label

Inline editierbare Inhalte im Block:
- Label
- Heading

Buttons:
- ein CTA-Button mit Label und URL

## 3) Studies

Block-Name: restatify/studies
Quellen: src/studies/block.json, src/studies/index.js

Inspector Panels:
- Section content
  - Heading
  - Intro text
- Card 1
  - Card image
  - Title
  - Text
  - Button label
  - Card link
- Card 2
  - Card image
  - Title
  - Text
  - Button label
  - Card link
- Style overrides (optional)
  - Card background color
  - Card border color
  - Bottom line start color
  - Bottom line end color
  - Link color
  - Link hover color
  - Reset colors to default
- Content visibility
  - Show heading
  - Show intro text
  - Show card 1 title/text/button label
  - Show card 2 title/text/button label

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- Card 1 Button: Label + URL
- Card 2 Button: Label + URL

## 4) Testimonials

Block-Name: restatify/testimonials
Quellen: src/testimonials/block.json, src/testimonials/index.js

Inspector Panels:
- Content
  - Label
- Testimonial 1
  - Quote
  - Name
  - Role
  - Person image
- Testimonial 2
  - Quote
  - Name
  - Role
  - Person image
- Testimonial 3
  - Quote
  - Name
  - Role
  - Person image
- Content visibility
  - Show label
  - Show quote 1, 2, 3
  - Show person 1, 2, 3 name/role

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- keine CTA-Buttons in diesem Block

## 5) Mission

Block-Name: restatify/mission
Quellen: src/mission/block.json, src/mission/index.js

Inspector Panels:
- Content
  - Heading
  - Subheading
  - Text
- Image
  - Bild waehlen/ersetzen
  - Bild entfernen
- Content visibility
  - Show heading
  - Show subheading
  - Show text
  - Show image

Inline editierbare Inhalte im Block:
- Heading
- Subheading
- Text

Buttons:
- keine CTA-Buttons in diesem Block

## 6) Metrics

Block-Name: restatify/metrics
Quellen: src/metrics/block.json, src/metrics/index.js

Inspector Panels:
- Content
  - Heading
  - Text
- Metrics
  - Metric 1 number
  - Metric 1 label
  - Metric 2 number
  - Metric 2 label
  - Metric 3 number
  - Metric 3 label
  - Metric 4 number
  - Metric 4 label
- Number animation
  - Animation mode (No animation oder Count up)
  - Duration (ms)
- Content visibility
  - Show heading
  - Show text

Inline editierbare Inhalte im Block:
- Heading
- Text

Buttons:
- keine CTA-Buttons in diesem Block

## 7) Insight

Block-Name: restatify/insight
Quellen: src/insight/block.json, src/insight/index.js

Inspector Panels:
- Content
  - Label
  - Heading
  - Button label
  - Button link
- Image
  - Bild waehlen/ersetzen
  - Bild entfernen
- Content visibility
  - Show label
  - Show heading
  - Show button
  - Show image

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- ein Button mit Label und URL

## 8) Gallery

Block-Name: restatify/gallery
Quellen: src/gallery/block.json, src/gallery/index.js

Inspector Panels:
- Images
  - Image 1
  - Image 2
  - Image 3
  - Image 4
  - Image 5
  - Image 6
  - Image 7
  - Image 8
  - jeweils: waehlen/ersetzen/entfernen

Inline editierbare Inhalte im Block:
- keine Textinhalte

Buttons:
- keine CTA-Buttons in diesem Block

## 9) FAQ

Block-Name: restatify/faq
Quellen: src/faq/block.json, src/faq/index.js

Inspector Panels:
- Content
  - Heading
- Questions
  - Question 1
  - Answer 1
  - Question 2
  - Answer 2
  - Question 3
  - Answer 3
  - Question 4
  - Answer 4
- Content visibility
  - Show heading

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- keine CTA-Buttons in diesem Block

## 10) Pricing

Block-Name: restatify/pricing
Quellen: src/pricing/block.json, src/pricing/index.js

Inspector Panels:
- Content
  - Label
  - Heading
  - Text
- Plan 1
  - Title
  - Text
  - Price
  - Button label
  - Button link
- Plan 2
  - Title
  - Text
  - Price
  - Button label
  - Button link
- Plan 3
  - Title
  - Text
  - Price
  - Button label
  - Button link

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- Plan 1 Button: Label + URL
- Plan 2 Button: Label + URL
- Plan 3 Button: Label + URL

## 11) Contact

Block-Name: restatify/contact
Quellen: src/contact/block.json, src/contact/index.js

Inspector Panels:
- Content
  - Label
  - Heading
  - Text
  - Hinweis im Editor: Text wird im Block direkt bearbeitet
- Details
  - Add detail
  - pro Detail:
    - Label
    - Display mode
      - Text only (label plus text)
      - Text with hyperlink
      - Button (label as button text)
    - Text (bei Text oder Link-Modus)
    - Link (bei Link oder Button-Modus)
    - Move up
    - Move down
    - Remove
- Image
  - Bild waehlen/ersetzen
  - Bild entfernen

Inline editierbare Inhalte im Block:
- Label
- Heading
- Text

Detail-Konfiguration je Eintrag:
- title
- text
- mode
- url

Buttons:
- im Detail-Modus Button wird aus detail.title (Beschriftung) und detail.url erzeugt

## 12) Clients

Block-Name: restatify/clients
Quellen: src/clients/block.json, src/clients/index.js

Inspector Panels:
- Content
  - Label
- Logos
  - Logo 1
  - Logo 2
  - Logo 3
  - Logo 4
  - Logo 5
  - Logo 6
  - jeweils: waehlen/ersetzen/entfernen
- Content visibility
  - Show label

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- keine CTA-Buttons in diesem Block

## 13) Oracle

Block-Name: restatify/oracle
Quellen: src/oracle/block.json, src/oracle/index.js

Inspector Panels:
- Content
  - Label
  - Word pairs (ein Paar pro Zeile, getrennt mit senkrechtem Strich)
  - Random mode
  - Word cycle duration (ms)
  - Logo interval (ms)
  - Logo visible duration (ms)
  - Position drift range (Prozent)
- Style tuning
  - Word 1 color
  - Word 2 color
  - Glow color
  - Shell gradient start
  - Shell gradient end
- Logo overlay fallback (optional)
  - Fallback logo image
  - Remove fallback logo

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- keine CTA-Buttons in diesem Block

Konfigurierbare Ablaufsteuerung:
- Zufallsmodus an/aus
- Wortwechsel-Timing
- Logo-Einblendungsintervall
- Logo-Sichtdauer
- Drift-Intensitaet

## 14) Timer

Block-Name: restatify/timer
Quellen: src/timer/block.json, src/timer/index.js

Inspector Panels:
- Inhalt
  - Titel
  - Untertitel
  - Beschreibung
- Zielzeitpunkt
  - Datum und Uhrzeit setzen
  - Anzeige des gesetzten Zielzeitpunkts
  - Entfernen des Zielzeitpunkts
- Sichtbarkeit
  - Titel anzeigen
  - Untertitel anzeigen
  - Beschreibung anzeigen

Inline editierbare Inhalte im Block:
- keine direkten Canvas-Felder, Inhalte werden ueber Inspector gepflegt

Buttons:
- keine CTA-Buttons in diesem Block

Timer-spezifische Ausgabe:
- automatische Anzeige der Einheiten Jahre/Monate/Tage/Stunden/Minuten/Sekunden
- Singular/Plural wird sprachlich angepasst

## Standardwerte und technische Details

- Die konkreten Defaultwerte stehen je Block in den jeweiligen block.json Dateien.
- Animationslogik liegt bei Metrics und Oracle in den zugehoerigen view.js Dateien.
- Link-Verarbeitung erfolgt ueber gemeinsame Utilities in src/shared.
- Farboverrides werden in einzelnen Blocks ueber CSS-Variablen ausgesteuert.

## Wartungshinweis

Wenn ein Block neue Felder erhaelt, sollte diese Referenz parallel aktualisiert werden. Damit bleibt die Redaktionsdokumentation vollstaendig und support-tauglich.