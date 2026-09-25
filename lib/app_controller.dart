import 'package:flutter/foundation.dart';

import 'models.dart';
import 'repository/app_repository.dart';

class AppController extends ChangeNotifier {
  AppController(this._repository);

  final AppRepository _repository;

  List<BiographyEntry> entries = const [];
  List<BiographyHistory> history = const [];
  List<BiographySuggestion> suggestions = const [];
  bool isLoaded = false;

  Future<void> load() async {
    entries = [...await _repository.loadEntries()];
    history = [...await _repository.loadHistory()];
    suggestions = [...await _repository.loadSuggestions()];
    await _ensureDraft();
    _sortAll();
    isLoaded = true;
    notifyListeners();
  }

  BiographyEntry get draft => entries.firstWhere(
    (entry) => entry.status == BiographyStatus.draft,
    orElse: _newDraft,
  );

  List<BiographyEntry> get savedEntries =>
      entries.where((entry) => entry.status == BiographyStatus.saved).toList()
        ..sort((a, b) => b.date.compareTo(a.date));

  BiographyEntry? get lastSaved => savedEntries.firstOrNull;

  Future<void> updateDraft({
    String activity = '',
    String comment = '',
    double waterMl = 0,
    String drinks = '',
    String food = '',
    int sys = 0,
    int dia = 0,
    int pulse = 0,
    int pee = 0,
    int poop = 0,
  }) async {
    final current = draft;
    final updated = current.copyWith(
      date: nowIso(),
      activity: _addText(current.activity, activity),
      comment: _addText(current.comment, comment),
      waterMl: current.waterMl + waterMl,
      drinks: _sumText(current.drinks, drinks),
      food: _addText(current.food, food),
      sys: sys > 0 ? sys : current.sys,
      dia: dia > 0 ? dia : current.dia,
      pulse: pulse > 0 ? pulse : current.pulse,
      pee: current.pee + pee,
      poop: current.poop + poop,
    );

    entries = entries
        .map((entry) => entry.id == current.id ? updated : entry)
        .toList();
    _touchSuggestion('activity', activity);
    _touchSuggestion('drinks', drinks);
    _touchSuggestion('food', food);
    await _repository.saveEntries(entries);
    await _repository.saveSuggestions(suggestions);
    notifyListeners();
  }

  Future<void> saveDraft() async {
    final current = draft;
    if (_isEmpty(current)) return;

    final saved = current.copyWith(
      status: BiographyStatus.saved,
      date: nowIso(),
    );
    entries = entries
        .map((entry) => entry.id == current.id ? saved : entry)
        .toList();
    history = [
      BiographyHistory(
        id: 'history_${DateTime.now().microsecondsSinceEpoch}',
        entryId: saved.id,
        date: nowIso(),
        info: 'gespeichert',
      ),
      ...history,
    ];
    entries = [_newDraft(), ...entries];
    _sortAll();
    await _repository.saveEntries(entries);
    await _repository.saveHistory(history);
    notifyListeners();
  }

  List<BiographyEntry> entriesSince(Duration duration, {DateTime? now}) {
    final end = now ?? DateTime.now();
    final start = end.subtract(duration);
    return savedEntries.where((entry) {
      final date = _parse(entry.date);
      return date != null && !date.isBefore(start) && !date.isAfter(end);
    }).toList();
  }

  BiographySummary summaryFor(Duration duration, {DateTime? now}) {
    final values = entriesSince(duration, now: now);
    final pressure = values
        .where((entry) => entry.sys > 0 && entry.dia > 0)
        .toList();
    final pulseValues = values.where((entry) => entry.pulse > 0).toList();

    return BiographySummary(
      count: values.length,
      waterMl: values.fold<double>(0, (sum, entry) => sum + entry.waterMl),
      pee: values.fold<int>(0, (sum, entry) => sum + entry.pee),
      poop: values.fold<int>(0, (sum, entry) => sum + entry.poop),
      averageSys: pressure.isEmpty
          ? 0
          : pressure.fold<double>(0, (sum, entry) => sum + entry.sys) /
                pressure.length,
      averageDia: pressure.isEmpty
          ? 0
          : pressure.fold<double>(0, (sum, entry) => sum + entry.dia) /
                pressure.length,
      averagePulse: pulseValues.isEmpty
          ? 0
          : pulseValues.fold<double>(0, (sum, entry) => sum + entry.pulse) /
                pulseValues.length,
    );
  }

  List<BiographyHistory> historyForEntry(String entryId) {
    return history.where((item) => item.entryId == entryId).toList()
      ..sort((a, b) => b.date.compareTo(a.date));
  }

  Future<void> _ensureDraft() async {
    if (entries.any((entry) => entry.status == BiographyStatus.draft)) return;
    entries = [_newDraft(), ...entries];
    await _repository.saveEntries(entries);
  }

  BiographyEntry _newDraft() {
    return BiographyEntry(
      id: 'entry_${DateTime.now().microsecondsSinceEpoch}',
      status: BiographyStatus.draft,
      date: nowIso(),
    );
  }

  bool _isEmpty(BiographyEntry entry) {
    return entry.activity.isEmpty &&
        entry.comment.isEmpty &&
        entry.waterMl == 0 &&
        entry.drinks.isEmpty &&
        entry.food.isEmpty &&
        entry.sys == 0 &&
        entry.dia == 0 &&
        entry.pulse == 0 &&
        entry.pee == 0 &&
        entry.poop == 0;
  }

  void _touchSuggestion(String list, String entry) {
    final cleaned = entry.trim();
    if (cleaned.isEmpty) return;
    final existing = suggestions.firstWhereOrNull(
      (item) => item.list == list && item.entry == cleaned,
    );
    if (existing == null) {
      suggestions = [
        BiographySuggestion(
          id: 'suggestion_${DateTime.now().microsecondsSinceEpoch}',
          list: list,
          entry: cleaned,
          count: 1,
          lastDate: nowIso(),
        ),
        ...suggestions,
      ];
    } else {
      suggestions = suggestions
          .map(
            (item) => item.id == existing.id
                ? item.copyWith(count: item.count + 1, lastDate: nowIso())
                : item,
          )
          .toList();
    }
  }

  String _addText(String oldValue, String newValue) {
    final cleaned = newValue.trim();
    if (cleaned.isEmpty) return oldValue;
    if (oldValue.isEmpty) return cleaned;
    return '$oldValue, $cleaned';
  }

  String _sumText(String oldValue, String newValue) {
    final cleaned = newValue.trim();
    if (cleaned.isEmpty) return oldValue;
    if (oldValue.isEmpty) return cleaned;

    final parts = oldValue.split(',').map((part) => part.trim()).toList();
    final index = parts.indexWhere((part) {
      final normalized = part.replaceFirst(RegExp(r'^\d+\s+'), '');
      return normalized == cleaned;
    });
    if (index < 0) return '$oldValue, $cleaned';

    final current = parts[index];
    final match = RegExp(r'^(\d+)\s+(.+)$').firstMatch(current);
    final count = match == null ? 2 : int.parse(match.group(1)!) + 1;
    parts[index] = '$count $cleaned';
    return parts.join(', ');
  }

  DateTime? _parse(String value) =>
      DateTime.tryParse(value.replaceFirst(' ', 'T'));

  void _sortAll() {
    entries.sort((a, b) => b.date.compareTo(a.date));
    history.sort((a, b) => b.date.compareTo(a.date));
    suggestions.sort((a, b) {
      final listCompare = a.list.compareTo(b.list);
      if (listCompare != 0) return listCompare;
      final countCompare = b.count.compareTo(a.count);
      if (countCompare != 0) return countCompare;
      return b.lastDate.compareTo(a.lastDate);
    });
  }
}

extension IterableX<T> on Iterable<T> {
  T? get firstOrNull => isEmpty ? null : first;

  T? firstWhereOrNull(bool Function(T element) test) {
    try {
      return firstWhere(test);
    } catch (_) {
      return null;
    }
  }
}
