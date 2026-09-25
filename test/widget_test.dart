import 'package:flutter_test/flutter_test.dart';

import 'package:biografie/app.dart';
import 'package:biografie/app_controller.dart';
import 'package:biografie/models.dart';
import 'package:biografie/repository/app_repository.dart';
import 'package:biografie/ui_settings.dart';

void main() {
  testWidgets('Biografie app loads today page', (tester) async {
    final controller = AppController(_MemoryAppRepository());
    await controller.load();

    await tester.pumpWidget(
      BiografieApp(
        controller: controller,
        settingsController: UiSettingsController.memory(),
      ),
    );

    expect(find.text('Biografie'), findsOneWidget);
    expect(find.text('Schnell erfassen'), findsOneWidget);
  });

  test('draft updates merge water and repeated drinks', () async {
    final controller = AppController(_MemoryAppRepository());
    await controller.load();

    await controller.updateDraft(waterMl: 250, drinks: 'Kaffee');
    await controller.updateDraft(waterMl: 500, drinks: 'Kaffee', pee: 1);

    expect(controller.draft.waterMl, 750);
    expect(controller.draft.drinks, '2 Kaffee');
    expect(controller.draft.pee, 1);
  });

  test('saving draft creates saved entry and summary', () async {
    final controller = AppController(_MemoryAppRepository());
    await controller.load();

    await controller.updateDraft(activity: 'Spaziergang', waterMl: 500, pee: 1);
    await controller.saveDraft();

    final summary = controller.summaryFor(const Duration(days: 7));

    expect(controller.savedEntries.first.activity, 'Spaziergang');
    expect(summary.waterMl, greaterThanOrEqualTo(500));
    expect(summary.pee, greaterThanOrEqualTo(1));
    expect(controller.draft.activity, isEmpty);
  });
}

class _MemoryAppRepository implements AppRepository {
  List<BiographyEntry> _entries = const [
    BiographyEntry(
      id: 'entry_draft',
      status: BiographyStatus.draft,
      date: '2026-01-01 10:00:00',
    ),
    BiographyEntry(
      id: 'entry_saved',
      status: BiographyStatus.saved,
      date: '2026-01-01 09:00:00',
      activity: 'Frühstück',
      waterMl: 250,
      pee: 1,
    ),
  ];
  List<BiographyHistory> _history = const [];
  List<BiographySuggestion> _suggestions = const [];

  @override
  Future<List<BiographyEntry>> loadEntries() async => _entries;

  @override
  Future<List<BiographyHistory>> loadHistory() async => _history;

  @override
  Future<List<BiographySuggestion>> loadSuggestions() async => _suggestions;

  @override
  Future<void> saveEntries(List<BiographyEntry> entries) async {
    _entries = entries;
  }

  @override
  Future<void> saveHistory(List<BiographyHistory> history) async {
    _history = history;
  }

  @override
  Future<void> saveSuggestions(List<BiographySuggestion> suggestions) async {
    _suggestions = suggestions;
  }
}
