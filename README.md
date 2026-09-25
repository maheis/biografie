# Biografie

Biografie ist eine lokale App zum Erfassen persoenlicher Tages- und Gesundheitsnotizen. Sie eignet sich fuer Aktivitaeten, Kommentare, Wasser, Getraenke, Nahrung, Blutdruck, Puls und weitere Tageswerte.

## Funktionen

- aktuellen Tagesentwurf fuehren
- Wasser per Schnellbutton erfassen
- Getraenke zusammenfassen
- Aktivitaet, Nahrung und Kommentar erfassen
- Blutdruck und Puls speichern
- Pee/Poop zaehlen
- Entwurf speichern und Verlauf anzeigen
- 1/7/30-Tage-Filter und Auswertungen nutzen
- Gemeinsame UI-Settings fuer Schrift, Textgroesse, Akzentfarbe, Highlight-Farbe und hell/dunkel

## Plattformen

- Android
- Linux Desktop

## Technik

- Flutter / Dart
- Sembast fuer lokale Persistenz
- `path_provider` fuer App-Dokumentordner
- `intl` fuer Datum und Formatierung

## Entwicklung

```bash
flutter pub get
flutter analyze
flutter test test/widget_test.dart --reporter compact
flutter build linux --debug
```

Android-Builds benoetigen lokal ein vollstaendiges JDK mit `javac`.

## Datenschutz

Biografie kann besonders sensible persoenliche und gesundheitliche Daten enthalten. Die Daten werden lokal auf dem Geraet gespeichert. Es gibt derzeit keine Cloud-Synchronisierung und keine automatische Serveruebertragung. Details stehen in `PRIVACY.md`.

## Rechtliches

Der Source Code steht unter MIT-Lizenz. Name, Logo, Icons und sonstige Brand Assets sind separat geschuetzt. Details stehen in `LICENSE`, `TRADEMARK.md` und `THIRD_PARTY_LICENSES.md`.

## footnote

Developed with the kind support of Copilot
