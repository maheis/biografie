import 'package:intl/intl.dart';

enum BiographyStatus {
  draft(0, 'in Bearbeitung'),
  saved(1, 'gespeichert'),
  deleted(-1, 'gelöscht');

  const BiographyStatus(this.value, this.label);

  final int value;
  final String label;

  static BiographyStatus fromValue(int value) {
    return BiographyStatus.values.firstWhere(
      (status) => status.value == value,
      orElse: () => BiographyStatus.draft,
    );
  }
}

class BiographyEntry {
  const BiographyEntry({
    required this.id,
    required this.status,
    required this.date,
    this.activity = '',
    this.comment = '',
    this.waterMl = 0,
    this.drinks = '',
    this.food = '',
    this.sys = 0,
    this.dia = 0,
    this.pulse = 0,
    this.pee = 0,
    this.poop = 0,
  });

  final String id;
  final BiographyStatus status;
  final String date;
  final String activity;
  final String comment;
  final double waterMl;
  final String drinks;
  final String food;
  final int sys;
  final int dia;
  final int pulse;
  final int pee;
  final int poop;

  bool get isDraft => status == BiographyStatus.draft;
  bool get isSaved => status == BiographyStatus.saved;

  BiographyEntry copyWith({
    String? id,
    BiographyStatus? status,
    String? date,
    String? activity,
    String? comment,
    double? waterMl,
    String? drinks,
    String? food,
    int? sys,
    int? dia,
    int? pulse,
    int? pee,
    int? poop,
  }) {
    return BiographyEntry(
      id: id ?? this.id,
      status: status ?? this.status,
      date: date ?? this.date,
      activity: activity ?? this.activity,
      comment: comment ?? this.comment,
      waterMl: waterMl ?? this.waterMl,
      drinks: drinks ?? this.drinks,
      food: food ?? this.food,
      sys: sys ?? this.sys,
      dia: dia ?? this.dia,
      pulse: pulse ?? this.pulse,
      pee: pee ?? this.pee,
      poop: poop ?? this.poop,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'status': status.value,
    'date': date,
    'activity': activity,
    'comment': comment,
    'waterMl': waterMl,
    'drinks': drinks,
    'food': food,
    'sys': sys,
    'dia': dia,
    'pulse': pulse,
    'pee': pee,
    'poop': poop,
  };

  factory BiographyEntry.fromJson(Map<String, dynamic> json) {
    return BiographyEntry(
      id: (json['id'] ?? '').toString(),
      status: BiographyStatus.fromValue((json['status'] as num?)?.toInt() ?? 0),
      date: (json['date'] ?? nowIso()).toString(),
      activity: (json['activity'] ?? '').toString(),
      comment: (json['comment'] ?? '').toString(),
      waterMl: (json['waterMl'] as num?)?.toDouble() ?? 0,
      drinks: (json['drinks'] ?? '').toString(),
      food: (json['food'] ?? '').toString(),
      sys: (json['sys'] as num?)?.toInt() ?? 0,
      dia: (json['dia'] as num?)?.toInt() ?? 0,
      pulse: (json['pulse'] as num?)?.toInt() ?? 0,
      pee: (json['pee'] as num?)?.toInt() ?? 0,
      poop: (json['poop'] as num?)?.toInt() ?? 0,
    );
  }
}

class BiographyHistory {
  const BiographyHistory({
    required this.id,
    required this.entryId,
    required this.date,
    required this.info,
  });

  final String id;
  final String entryId;
  final String date;
  final String info;

  Map<String, dynamic> toJson() => {
    'id': id,
    'entryId': entryId,
    'date': date,
    'info': info,
  };

  factory BiographyHistory.fromJson(Map<String, dynamic> json) {
    return BiographyHistory(
      id: (json['id'] ?? '').toString(),
      entryId: (json['entryId'] ?? '').toString(),
      date: (json['date'] ?? nowIso()).toString(),
      info: (json['info'] ?? '').toString(),
    );
  }
}

class BiographySuggestion {
  const BiographySuggestion({
    required this.id,
    required this.list,
    required this.entry,
    this.count = 0,
    required this.lastDate,
  });

  final String id;
  final String list;
  final String entry;
  final int count;
  final String lastDate;

  BiographySuggestion copyWith({int? count, String? lastDate}) {
    return BiographySuggestion(
      id: id,
      list: list,
      entry: entry,
      count: count ?? this.count,
      lastDate: lastDate ?? this.lastDate,
    );
  }

  Map<String, dynamic> toJson() => {
    'id': id,
    'list': list,
    'entry': entry,
    'count': count,
    'lastDate': lastDate,
  };

  factory BiographySuggestion.fromJson(Map<String, dynamic> json) {
    return BiographySuggestion(
      id: (json['id'] ?? '').toString(),
      list: (json['list'] ?? '').toString(),
      entry: (json['entry'] ?? '').toString(),
      count: (json['count'] as num?)?.toInt() ?? 0,
      lastDate: (json['lastDate'] ?? nowIso()).toString(),
    );
  }
}

class BiographySummary {
  const BiographySummary({
    required this.count,
    required this.waterMl,
    required this.pee,
    required this.poop,
    required this.averageSys,
    required this.averageDia,
    required this.averagePulse,
  });

  final int count;
  final double waterMl;
  final int pee;
  final int poop;
  final double averageSys;
  final double averageDia;
  final double averagePulse;
}

String nowIso() => DateFormat('yyyy-MM-dd HH:mm:ss').format(DateTime.now());

String shortDateTime(String isoDate) {
  final date = DateTime.tryParse(isoDate.replaceFirst(' ', 'T'));
  if (date == null) return isoDate;
  return DateFormat('dd.MM.yyyy HH:mm').format(date);
}

String shortDate(String isoDate) {
  final date = DateTime.tryParse(isoDate.replaceFirst(' ', 'T'));
  if (date == null) return isoDate;
  return DateFormat('dd.MM.yyyy').format(date);
}

String formatMl(double value) {
  if (value == value.roundToDouble()) return '${value.round()} ml';
  return '${NumberFormat('0.##', 'de_DE').format(value)} ml';
}
