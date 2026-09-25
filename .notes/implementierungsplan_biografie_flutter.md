# Implementierungsplan Biografie Flutter

## Ziel

Biografie wird eine lokale Flutter-App zum schnellen Erfassen persönlicher Tages- und Gesundheitsnotizen. Die App soll Android nativ laufen und zugleich als Linux-Desktop-App startbar sein. Stil und technische Linie folgen SimplePresent, VolleyAce, Playsheet, Fibu und Bloemcher: Material 3, lokale Datenhaltung, klare Cards, schnelle Aktionen, kein Serverzwang.

## PHP-Referenz

Quelle: `.notes/biography`

Wesentliche Dateien:

- `index_.php`: aktueller Eintrag in Bearbeitung, Schnellbuttons für Wasser/Getränke/Toilette, Speichern.
- `biography_list.php`: Tages-/Wochen-/Monats-/Jahreslisten, Summen und Graph-Umschaltung.
- `biography_history.php`: Änderungsverlauf eines Eintrags.
- `database_t_biography_list.php`: Hauptmodell für Biografie-Einträge.
- `database_t_biography_history.php`: Historie je Eintrag.
- `database_t_biography_datalists.php`: Vorschlagslisten für Aktivität, Getränke, Nahrung.
- `database_i_biography_list.php`: Merge-/Summenlogik für bestehende Entwürfe.

## Datenmodell

### BiographyEntry

- `id`
- `status`: draft, saved, deleted
- `date`
- `activity`
- `comment`
- `waterMl`
- `drinks`
- `food`
- `sys`
- `dia`
- `pulse`
- `pee`
- `poop`

### BiographyHistory

- `id`
- `entryId`
- `date`
- `info`

### BiographySuggestion

- `id`
- `list`: activity, drinks, food
- `entry`
- `count`
- `lastDate`

## Architektur

- `lib/main.dart`: App-Start, Datenbankpfad, Repository und Controller.
- `lib/app.dart`: Material-App, Navigation und Shell.
- `lib/models.dart`: Domainmodelle, Status, Formatierung.
- `lib/app_controller.dart`: Business-Logik, Summen, Draft-Merge, History.
- `lib/repository/app_repository.dart`: Sembast-Speicherung und Seed-Daten.
- `lib/pages/*`: Heute/Draft, Verlauf, Auswertung, Editor.

## Kernlogik Phase 1

1. Flutter-Projekt für Android und Linux anlegen.
2. Modelle und Sembast-Repository implementieren.
3. Controller für Draft, Speichern, Schnellaktionen und Tageszusammenfassung.
4. Heute-Seite mit Eingabefeldern und Schnellbuttons.
5. Verlaufsliste mit 1/7/30 Tage Filter.
6. Auswertung mit Wasser/Pee/Poop und Blutdruck-Kennzahlen.
7. Änderungshistorie beim Speichern/Ändern anlegen.
8. Tests für Draft-Merge, Speichern und Summen.

## UI-Richtung

- Startscreen ist direkt der aktuelle Eintrag, keine Landingpage.
- Bottom Navigation: Heute, Verlauf, Auswertung.
- Ruhige Material-3-Cards wie Fibu/Bloemcher.
- Farbwelt: Grün/Teal als Basis, Blau für Wasser, Rot für Blutdruck, Braun/Gelb für Getränke/Nahrung.
- Große, berührbare Schnellaktionen: 250 ml, 500 ml, Kaffee, Apfelschorle, Bier, Pee, Poop.

## Phase 2

- Bearbeiten bestehender Einträge.
- Vollständige History-Seite pro Eintrag.
- Vorschlagslisten aus Nutzungsverhalten.
- Backup/Export analog Fibu/Bloemcher.
- Diagramme für Blutdruck/Puls/Wasser.
- Release-Build Android nach JDK-Fix.

## Erste Umsetzung

Start mit Phase 1: Scaffold, lokale Datenhaltung, Dashboard/Heute-Seite, Verlauf, Auswertung und Tests.
