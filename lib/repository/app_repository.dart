import 'package:sembast/sembast.dart';

import '../models.dart';

abstract interface class AppRepository {
  Future<List<BiographyEntry>> loadEntries();
  Future<List<BiographyHistory>> loadHistory();
  Future<List<BiographySuggestion>> loadSuggestions();
  Future<void> saveEntries(List<BiographyEntry> entries);
  Future<void> saveHistory(List<BiographyHistory> history);
  Future<void> saveSuggestions(List<BiographySuggestion> suggestions);
}

class LocalAppRepository implements AppRepository {
  LocalAppRepository(this._database);

  final Database _database;
  final _entriesStore = stringMapStoreFactory.store('biography_entries');
  final _historyStore = stringMapStoreFactory.store('biography_history');
  final _suggestionsStore = stringMapStoreFactory.store(
    'biography_suggestions',
  );

  @override
  Future<List<BiographyEntry>> loadEntries() async {
    final values = (await _entriesStore.find(_database))
        .map((entry) => BiographyEntry.fromJson(entry.value))
        .toList();

    if (values.isEmpty) {
      final seed = _defaultEntries();
      await saveEntries(seed);
      return seed;
    }

    return values;
  }

  @override
  Future<List<BiographyHistory>> loadHistory() async {
    final values = (await _historyStore.find(_database))
        .map((entry) => BiographyHistory.fromJson(entry.value))
        .toList();

    if (values.isEmpty) {
      final seed = _defaultHistory();
      await saveHistory(seed);
      return seed;
    }

    return values;
  }

  @override
  Future<List<BiographySuggestion>> loadSuggestions() async {
    final values = (await _suggestionsStore.find(_database))
        .map((entry) => BiographySuggestion.fromJson(entry.value))
        .toList();

    if (values.isEmpty) {
      final seed = _defaultSuggestions();
      await saveSuggestions(seed);
      return seed;
    }

    return values;
  }

  @override
  Future<void> saveEntries(List<BiographyEntry> entries) async {
    await _database.transaction((txn) async {
      await _entriesStore.delete(txn);
      for (final entry in entries) {
        await _entriesStore.record(entry.id).put(txn, entry.toJson());
      }
    });
  }

  @override
  Future<void> saveHistory(List<BiographyHistory> history) async {
    await _database.transaction((txn) async {
      await _historyStore.delete(txn);
      for (final item in history) {
        await _historyStore.record(item.id).put(txn, item.toJson());
      }
    });
  }

  @override
  Future<void> saveSuggestions(List<BiographySuggestion> suggestions) async {
    await _database.transaction((txn) async {
      await _suggestionsStore.delete(txn);
      for (final item in suggestions) {
        await _suggestionsStore.record(item.id).put(txn, item.toJson());
      }
    });
  }

  List<BiographyEntry> _defaultEntries() {
    final now = DateTime.now();
    final yesterday = now.subtract(const Duration(days: 1));
    return [
      BiographyEntry(
        id: 'entry_draft',
        status: BiographyStatus.draft,
        date: _formatDate(now),
      ),
      BiographyEntry(
        id: 'entry_1',
        status: BiographyStatus.saved,
        date: _formatDate(yesterday),
        activity: 'Spaziergang',
        comment: 'Ruhiger Tag',
        waterMl: 750,
        drinks: 'Kaffee, Apfelschorle',
        food: 'Brotzeit',
        sys: 126,
        dia: 82,
        pulse: 72,
        pee: 3,
        poop: 1,
      ),
    ];
  }

  List<BiographyHistory> _defaultHistory() => const [
    BiographyHistory(
      id: 'history_1',
      entryId: 'entry_1',
      date: '2026-01-01 09:00:00',
      info: 'add ! activity:  ↦ Spaziergang',
    ),
  ];

  List<BiographySuggestion> _defaultSuggestions() => const [
    BiographySuggestion(
      id: 'suggestion_activity_walk',
      list: 'activity',
      entry: 'Spaziergang',
      count: 3,
      lastDate: '2026-01-01 09:00:00',
    ),
    BiographySuggestion(
      id: 'suggestion_drinks_coffee',
      list: 'drinks',
      entry: 'Kaffee',
      count: 5,
      lastDate: '2026-01-01 09:00:00',
    ),
    BiographySuggestion(
      id: 'suggestion_food_bread',
      list: 'food',
      entry: 'Brotzeit',
      count: 2,
      lastDate: '2026-01-01 09:00:00',
    ),
  ];

  String _formatDate(DateTime date) {
    final year = date.year.toString().padLeft(4, '0');
    final month = date.month.toString().padLeft(2, '0');
    final day = date.day.toString().padLeft(2, '0');
    final hour = date.hour.toString().padLeft(2, '0');
    final minute = date.minute.toString().padLeft(2, '0');
    final second = date.second.toString().padLeft(2, '0');
    return '$year-$month-$day $hour:$minute:$second';
  }
}
