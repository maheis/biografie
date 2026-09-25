import 'package:flutter/material.dart';

import '../app_controller.dart';
import '../models.dart';

class HistoryPage extends StatefulWidget {
  const HistoryPage({super.key, required this.controller});

  final AppController controller;

  @override
  State<HistoryPage> createState() => _HistoryPageState();
}

class _HistoryPageState extends State<HistoryPage> {
  Duration _range = const Duration(days: 7);

  @override
  Widget build(BuildContext context) {
    final entries = widget.controller.entriesSince(_range);

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        SegmentedButton<Duration>(
          segments: const [
            ButtonSegment(value: Duration(days: 1), label: Text('Tag')),
            ButtonSegment(value: Duration(days: 7), label: Text('7 Tage')),
            ButtonSegment(value: Duration(days: 30), label: Text('30 Tage')),
          ],
          selected: {_range},
          onSelectionChanged: (values) => setState(() => _range = values.first),
        ),
        const SizedBox(height: 16),
        if (entries.isEmpty)
          const Card(
            child: Padding(
              padding: EdgeInsets.all(16),
              child: Text('Keine Einträge im gewählten Zeitraum.'),
            ),
          )
        else
          ...entries.map((entry) => _HistoryEntryCard(entry: entry)),
      ],
    );
  }
}

class _HistoryEntryCard extends StatelessWidget {
  const _HistoryEntryCard({required this.entry});

  final BiographyEntry entry;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                const Icon(Icons.event_note),
                const SizedBox(width: 12),
                Expanded(
                  child: Text(
                    shortDateTime(entry.date),
                    style: Theme.of(context).textTheme.titleMedium,
                  ),
                ),
                Text(formatMl(entry.waterMl)),
              ],
            ),
            const SizedBox(height: 12),
            if (entry.activity.isNotEmpty) Text('Aktivität: ${entry.activity}'),
            if (entry.drinks.isNotEmpty) Text('Getränke: ${entry.drinks}'),
            if (entry.food.isNotEmpty) Text('Nahrung: ${entry.food}'),
            if (entry.sys > 0 && entry.dia > 0)
              Text('Blutdruck: ${entry.sys}/${entry.dia} mmHg'),
            if (entry.pulse > 0) Text('Puls: ${entry.pulse} /min'),
            Text('Toilette: Pee ${entry.pee}, Poop ${entry.poop}'),
            if (entry.comment.isNotEmpty) Text('Kommentar: ${entry.comment}'),
          ],
        ),
      ),
    );
  }
}
