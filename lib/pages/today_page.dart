import 'package:flutter/material.dart';

import '../app_controller.dart';
import '../models.dart';

class TodayPage extends StatefulWidget {
  const TodayPage({super.key, required this.controller});

  final AppController controller;

  @override
  State<TodayPage> createState() => _TodayPageState();
}

class _TodayPageState extends State<TodayPage> {
  final _activityController = TextEditingController();
  final _commentController = TextEditingController();
  final _foodController = TextEditingController();
  final _sysController = TextEditingController();
  final _diaController = TextEditingController();
  final _pulseController = TextEditingController();

  Future<void> _applyTextFields() async {
    await widget.controller.updateDraft(
      activity: _activityController.text,
      comment: _commentController.text,
      food: _foodController.text,
      sys: int.tryParse(_sysController.text) ?? 0,
      dia: int.tryParse(_diaController.text) ?? 0,
      pulse: int.tryParse(_pulseController.text) ?? 0,
    );
    _activityController.clear();
    _commentController.clear();
    _foodController.clear();
    _sysController.clear();
    _diaController.clear();
    _pulseController.clear();
  }

  Future<void> _saveDraft() async {
    await _applyTextFields();
    await widget.controller.saveDraft();
    if (!mounted) return;
    ScaffoldMessenger.of(context)
        .showSnackBar(const SnackBar(content: Text('Eintrag gespeichert.')));
  }

  @override
  Widget build(BuildContext context) {
    final draft = widget.controller.draft;
    final lastSaved = widget.controller.lastSaved;

    return ListView(
      padding: const EdgeInsets.all(16),
      children: [
        _DraftCard(draft: draft),
        const SizedBox(height: 16),
        Text('Schnell erfassen', style: Theme.of(context).textTheme.titleLarge),
        const SizedBox(height: 12),
        Wrap(
          spacing: 8,
          runSpacing: 8,
          children: [
            _QuickButton(
              icon: Icons.water_drop,
              label: '250 ml',
              color: Colors.blue,
              onPressed: () => widget.controller.updateDraft(waterMl: 250),
            ),
            _QuickButton(
              icon: Icons.water_drop,
              label: '500 ml',
              color: Colors.blue,
              onPressed: () => widget.controller.updateDraft(waterMl: 500),
            ),
            _QuickButton(
              icon: Icons.coffee,
              label: 'Kaffee',
              color: Colors.brown,
              onPressed: () => widget.controller.updateDraft(drinks: 'Kaffee'),
            ),
            _QuickButton(
              icon: Icons.local_bar,
              label: 'Apfelschorle',
              color: Colors.green,
              onPressed: () =>
                  widget.controller.updateDraft(drinks: 'Apfelschorle'),
            ),
            _QuickButton(
              icon: Icons.sports_bar,
              label: 'Bier',
              color: Colors.amber,
              onPressed: () => widget.controller.updateDraft(drinks: 'Bier'),
            ),
            _QuickButton(
              icon: Icons.wc,
              label: 'Pee',
              color: Colors.yellow.shade700,
              onPressed: () => widget.controller.updateDraft(pee: 1),
            ),
            _QuickButton(
              icon: Icons.eco,
              label: 'Poop',
              color: Colors.brown,
              onPressed: () => widget.controller.updateDraft(poop: 1),
            ),
          ],
        ),
        const SizedBox(height: 20),
        Text('Details', style: Theme.of(context).textTheme.titleLarge),
        const SizedBox(height: 12),
        TextField(
          controller: _activityController,
          decoration: const InputDecoration(labelText: 'Aktivität'),
        ),
        const SizedBox(height: 12),
        TextField(
          controller: _foodController,
          decoration: const InputDecoration(labelText: 'Nahrung'),
        ),
        const SizedBox(height: 12),
        TextField(
          controller: _commentController,
          decoration: const InputDecoration(labelText: 'Kommentar'),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: TextField(
                controller: _sysController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(labelText: 'SYS'),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: TextField(
                controller: _diaController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(labelText: 'DIA'),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: TextField(
                controller: _pulseController,
                keyboardType: TextInputType.number,
                decoration: const InputDecoration(labelText: 'Puls'),
              ),
            ),
          ],
        ),
        const SizedBox(height: 20),
        Row(
          children: [
            Expanded(
              child: FilledButton.tonalIcon(
                onPressed: _applyTextFields,
                icon: const Icon(Icons.add),
                label: const Text('Zum Entwurf'),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: FilledButton.icon(
                onPressed: _saveDraft,
                icon: const Icon(Icons.save),
                label: const Text('Speichern'),
              ),
            ),
          ],
        ),
        if (lastSaved != null) ...[
          const SizedBox(height: 24),
          Text(
            'Letzter Eintrag',
            style: Theme.of(context).textTheme.titleLarge,
          ),
          const SizedBox(height: 12),
          _EntryCard(entry: lastSaved),
        ],
      ],
    );
  }

  @override
  void dispose() {
    _activityController.dispose();
    _commentController.dispose();
    _foodController.dispose();
    _sysController.dispose();
    _diaController.dispose();
    _pulseController.dispose();
    super.dispose();
  }
}

class _DraftCard extends StatelessWidget {
  const _DraftCard({required this.draft});

  final BiographyEntry draft;

  @override
  Widget build(BuildContext context) {
    return Card(
      color: Theme.of(context).colorScheme.primaryContainer.withAlpha(120),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              'In Bearbeitung',
              style: Theme.of(context).textTheme.titleMedium,
            ),
            const SizedBox(height: 8),
            Text(shortDateTime(draft.date)),
            const SizedBox(height: 12),
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                Chip(label: Text(formatMl(draft.waterMl))),
                Chip(label: Text('Pee ${draft.pee}')),
                Chip(label: Text('Poop ${draft.poop}')),
                if (draft.sys > 0 && draft.dia > 0)
                  Chip(label: Text('${draft.sys}/${draft.dia}')),
                if (draft.pulse > 0) Chip(label: Text('${draft.pulse} /min')),
              ],
            ),
            if (draft.activity.isNotEmpty) Text('Aktivität: ${draft.activity}'),
            if (draft.drinks.isNotEmpty) Text('Getränke: ${draft.drinks}'),
            if (draft.food.isNotEmpty) Text('Nahrung: ${draft.food}'),
            if (draft.comment.isNotEmpty) Text('Kommentar: ${draft.comment}'),
          ],
        ),
      ),
    );
  }
}

class _EntryCard extends StatelessWidget {
  const _EntryCard({required this.entry});

  final BiographyEntry entry;

  @override
  Widget build(BuildContext context) {
    return Card(
      child: ListTile(
        leading: const Icon(Icons.event_note),
        title: Text(
          entry.activity.isEmpty ? shortDateTime(entry.date) : entry.activity,
        ),
        subtitle: Text(
          '${formatMl(entry.waterMl)} · ${entry.drinks} · ${entry.food}',
        ),
      ),
    );
  }
}

class _QuickButton extends StatelessWidget {
  const _QuickButton({
    required this.icon,
    required this.label,
    required this.color,
    required this.onPressed,
  });

  final IconData icon;
  final String label;
  final Color color;
  final VoidCallback onPressed;

  @override
  Widget build(BuildContext context) {
    return FilledButton.tonalIcon(
      onPressed: onPressed,
      icon: Icon(icon, color: color),
      label: Text(label),
    );
  }
}
