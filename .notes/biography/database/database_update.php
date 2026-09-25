<?php

$DatabaseVersion = intval(config('database_modul_version_biography', -99));
if ($DatabaseVersion == -99) {
    $DatabaseVersion = intval(config('database_modul_version_biography', -99));
    if ($DatabaseVersion >= 0) {
        database_insert($database_t_core_config, ['database_modul_version_biography', $DatabaseVersion, 'INT', 'main', '']);
        database_delete($database_t_core_config, 'key = :1', ['database_modul_version_biography']);
    } else {
        database_insert($database_t_core_config, ['database_modul_version_biography', '-1', 'INT', 'main', '']);
    }
}

if ($DatabaseVersion <= 0) {
    database_update($database_t_core_config, 'value = :1', ['biografie'], 'key = :2', ['title']);
    database_update($database_t_core_config, 'value = :1', ['&nbsp;&nbsp;&nbsp;mehr als du je aufzeichnen wolltest ...'], 'key = :2', ['title_subtitle']);
    database_update($database_t_core_config, 'value = :1', ['modules/biography/favicon.ico'], 'key = :2', ['favicon']);
    database_update($database_t_core_config, 'value = :1', ['modules/biography/biography.png'], 'key = :2', ['header_logo']);
    database_update($database_t_core_config, 'value = :1', ['1'], 'key = :2', ['with_global_auth']);

    database_update($database_t_core_config, 'value = :1', [1], 'key = :2', ['database_modul_version_biography']);
}

if ($DatabaseVersion <= 1) {
    // Altdatenübernahme aus Logbuch-Extension

    // database_update($database_t_biography_list, 'water = water * 1000', [], 'date <= :1', ['2026-01-14T23:49']);

    database_update($database_t_core_config, 'value = :1', [2], 'key = :2', ['database_modul_version_biography']);
}

if ($DatabaseVersion <= 2) {
    // database_exec('update biography_list set drinks = REPLACE(drinks, "1 ", "");');
    // database_exec('update biography_list set food = REPLACE(food, "1 ", "");');

    // database_exec('update biography_list set sys = 0 where sys is null or sys = "";');
    // database_exec('update biography_list set dia = 0 where dia is null or dia = "";');
    // database_exec('update biography_list set pulse = 0 where pulse is null or pulse = "";');
    // database_exec('update biography_list set pee = 0 where pee is null or pee = "";');
    // database_exec('update biography_list set poop = 0 where poop is null or poop = "";');

    database_update($database_t_core_config, 'value = :1', [3], 'key = :2', ['database_modul_version_biography']);
}

if ($DatabaseVersion <= 3) {
    // database_exec('CREATE TABLE IF NOT EXISTS biography_datalist_new (
    //     id INTEGER PRIMARY KEY AUTOINCREMENT,
    //     list TEXT NOT NULL,
    //     entry TEXT NOT NULL,
    //     count INT NOT NULL DEFAULT 0,
    //     lastdate DATETIME DEFAULT CURRENT_TIMESTAMP
    // );');

    // database_exec('INSERT INTO biography_datalist_new SELECT id, list, entry, count, CURRENT_TIMESTAMP FROM biography_datalist;');

    // database_exec('DROP TABLE biography_datalist;');

    // database_exec('ALTER TABLE biography_datalist_new RENAME TO biography_datalist;');

    database_update($database_t_core_config, 'value = :1', [4], 'key = :2', ['database_modul_version_biography']);
}


if ($DatabaseVersion <= 4) {
    database_update($database_t_core_config, 'value = :1', ['0'], 'key = :2', ['with_header']);
    database_update($database_t_core_config, 'value = :1', ['0'], 'key = :2', ['with_footer']);

    database_update($database_t_core_config, 'value = :1', [5], 'key = :2', ['database_modul_version_biography']);
}