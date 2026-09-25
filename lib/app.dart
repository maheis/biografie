import 'package:flutter/material.dart';

import 'app_controller.dart';
import 'pages/history_page.dart';
import 'pages/reports_page.dart';
import 'pages/today_page.dart';

class BiografieApp extends StatelessWidget {
  const BiografieApp({super.key, required this.controller});

  final AppController controller;

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: controller,
      builder: (context, _) {
        return MaterialApp(
          debugShowCheckedModeBanner: false,
          title: 'Biografie',
          theme: ThemeData(
            useMaterial3: true,
            colorSchemeSeed: const Color(0xFF2E7D6B),
            brightness: Brightness.light,
          ),
          darkTheme: ThemeData(
            useMaterial3: true,
            colorSchemeSeed: const Color(0xFF80CBC4),
            brightness: Brightness.dark,
          ),
          home: BiografieHomePage(controller: controller),
        );
      },
    );
  }
}

class BiografieHomePage extends StatefulWidget {
  const BiografieHomePage({super.key, required this.controller});

  final AppController controller;

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
      appBar: AppBar(title: const Text('Biografie'), centerTitle: false),
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
