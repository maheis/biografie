import 'package:flutter/material.dart';

import 'app_controller.dart';
import 'pages/history_page.dart';
import 'pages/reports_page.dart';
import 'pages/today_page.dart';
import 'ui_settings.dart';

class BiografieApp extends StatelessWidget {
  const BiografieApp({
    super.key,
    required this.controller,
    required this.settingsController,
  });

  final AppController controller;
  final UiSettingsController settingsController;

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: Listenable.merge([controller, settingsController]),
      builder: (context, _) {
        final settings = settingsController.settings;
        return MaterialApp(
          debugShowCheckedModeBanner: false,
          title: 'Biografie',
          theme: buildUnifiedTheme(settings, Brightness.light),
          darkTheme: buildUnifiedTheme(settings, Brightness.dark),
          themeMode: settings.useLightTheme ? ThemeMode.light : ThemeMode.dark,
          builder: (context, child) => MediaQuery(
            data: MediaQuery.of(
              context,
            ).copyWith(textScaler: TextScaler.linear(settings.textScaleFactor)),
            child: child ?? const SizedBox.shrink(),
          ),
          home: BiografieHomePage(
            controller: controller,
            settingsController: settingsController,
          ),
        );
      },
    );
  }
}

class BiografieHomePage extends StatefulWidget {
  const BiografieHomePage({
    super.key,
    required this.controller,
    required this.settingsController,
  });

  final AppController controller;
  final UiSettingsController settingsController;

  @override
  State<BiografieHomePage> createState() => _BiografieHomePageState();
}

class _BiografieHomePageState extends State<BiografieHomePage> {
  int _index = 0;

  @override
  Widget build(BuildContext context) {
    final pages = <Widget>[
      TodayPage(controller: widget.controller),
      HistoryPage(controller: widget.controller),
      ReportsPage(controller: widget.controller),
    ];

    return Scaffold(
      appBar: AppBar(
        title: const Text('Biografie'),
        centerTitle: false,
        actions: [
          IconButton(
            tooltip: 'Einstellungen',
            onPressed: () async {
              final result = await Navigator.of(context).push<AppUiSettings>(
                MaterialPageRoute(
                  builder: (_) => UiSettingsPage(
                    initial: widget.settingsController.settings,
                  ),
                ),
              );
              if (result != null) {
                await widget.settingsController.update(result);
              }
            },
            icon: const Icon(Icons.settings_outlined),
          ),
        ],
      ),
      body: pages[_index],
      bottomNavigationBar: NavigationBar(
        selectedIndex: _index,
        onDestinationSelected: (value) => setState(() => _index = value),
        destinations: const [
          NavigationDestination(
            icon: Icon(Icons.edit_note_outlined),
            selectedIcon: Icon(Icons.edit_note),
            label: 'Heute',
          ),
          NavigationDestination(
            icon: Icon(Icons.history_outlined),
            selectedIcon: Icon(Icons.history),
            label: 'Verlauf',
          ),
          NavigationDestination(
            icon: Icon(Icons.monitor_heart_outlined),
            selectedIcon: Icon(Icons.monitor_heart),
            label: 'Auswertung',
          ),
        ],
      ),
    );
  }
}
