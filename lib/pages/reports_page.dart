import 'package:flutter/material.dart';

import '../app_controller.dart';
import '../models.dart';

class ReportsPage extends StatelessWidget {
  const ReportsPage({super.key, required this.controller});

  final AppController controller;

  @override
  Widget build(BuildContext context) {
    final week = controller.summaryFor(const Duration(days: 7));
    final month = controller.summaryFor(const Duration(days: 30));

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        Text('Auswertung', style: Theme.of(context).textTheme.titleLarge),
        const SizedBox(height: 16),
        _SummarySection(title: 'Letzte 7 Tage', summary: week),
        const SizedBox(height: 16),
        _SummarySection(title: 'Letzte 30 Tage', summary: month),
      ],
    );
  }
}

class _SummarySection extends StatelessWidget {
  const _SummarySection({required this.title, required this.summary});

  final String title;
  final BiographySummary summary;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(title, style: Theme.of(context).textTheme.titleMedium),
        const SizedBox(height: 12),
        GridView.count(
          crossAxisCount: 2,
          mainAxisSpacing: 12,
          crossAxisSpacing: 12,
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          childAspectRatio: 1.6,
          children: [
            _MetricCard(
              title: 'Einträge',
              value: summary.count.toString(),
              icon: Icons.event_note,
              color: Colors.teal,
            ),
            _MetricCard(
              title: 'Wasser',
              value: formatMl(summary.waterMl),
              icon: Icons.water_drop,
              color: Colors.blue,
            ),
            _MetricCard(
              title: 'Blutdruck',
              value: summary.averageSys == 0
                  ? '-'
                  : '${summary.averageSys.round()}/${summary.averageDia.round()}',
              icon: Icons.monitor_heart,
              color: Colors.red,
            ),
            _MetricCard(
              title: 'Puls',
              value: summary.averagePulse == 0
                  ? '-'
                  : '${summary.averagePulse.round()} /min',
              icon: Icons.favorite,
              color: Colors.pink,
            ),
            _MetricCard(
              title: 'Pee',
              value: summary.pee.toString(),
              icon: Icons.wc,
              color: Colors.amber,
            ),
            _MetricCard(
              title: 'Poop',
              value: summary.poop.toString(),
              icon: Icons.eco,
              color: Colors.brown,
            ),
          ],
        ),
      ],
    );
  }
}

class _MetricCard extends StatelessWidget {
  const _MetricCard({
    required this.title,
    required this.value,
    required this.icon,
    required this.color,
  });

  final String title;
  final String value;
  final IconData icon;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Card(
      color: color.withAlpha(28),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Icon(icon, color: color),
            const Spacer(),
            Text(title, style: Theme.of(context).textTheme.labelLarge),
            FittedBox(
              fit: BoxFit.scaleDown,
              alignment: Alignment.centerLeft,
              child: Text(
                value,
                style: Theme.of(context).textTheme.titleLarge
                    ?.copyWith(fontWeight: FontWeight.bold),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
