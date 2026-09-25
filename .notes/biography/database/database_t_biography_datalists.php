<?php

//CREATE-Statement
database_exec('CREATE TABLE IF NOT EXISTS biography_datalist (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    list TEXT NOT NULL,
    entry TEXT NOT NULL,
    count INT NOT NULL DEFAULT 0,
    lastdate DATETIME DEFAULT CURRENT_TIMESTAMP
);');

//INSERT
// [$id, $list, $entry, $count]

//[TABLE]TABLE => 'tablename'
//[TABLE]RW = 0-1 //0 = readonly ,1 = read/write (für select_table_edit())
//[TABLE]NAME => 'spokable tablename'
//[ORDER] => default order by, can be overriden in select-function, e.g. 'column desc, column desc' 
//[COLUMNS]NAME => 'spokable name'
//[COLUMNS]RW => 0-2 //0 = readonly, 1 = read/write, 2 = readonly/but in INSERT writeable!
//[COLUMNS]TYPE => TEXT, INT, BOOL, PASSWORD, FLOAT, DATETIME, DATE, TIME, TIMESTAMP(dt)
//[COLUMNS]SIZE => smallest, smaller, small, '', big, bigger, biggest
//[COLUMNS]OPTIONAL DEFAULT => Default-Value
//[COLUMNS]OPTIONAL TIP => TOOPTIP
//[COLUMNS]OPTIONAL LIST => SELECTION: ['VALUE' => 'SPOKEABLENAME', 'VALUE' => 'SPOKEABLENAME', '_SQL'...] ['_SQL' => 'SELECT <COLUMN_VALUE> as a [, <COLUMN_SPOKEABLENAME> as b] FROM <TABLE> ORDER BY <COL>']
//[COLUMNS]OPTIONAL REQUIRED => 1 = mandatory field
//[COLUMNS]OPTIONAL HIDDEN => 1 //hidden in 1Pager
//[VIRTUAL]NAME => 'spokable name'
//[VIRTUAL]CONTENT => 'content, will be replaced with the value, usefull for e.g. buttons...'
$database_t_biography_datalist = [
    'TABLE' => 'biography_datalist',
    'RW' => 1,
    'NAME' => 'Listen',
    'ORDER' => 'list ASC, count DESC, lastdate DESC, entry ASC',
    'COLUMNS' => [
        'id' => ['NAME' => 'ID', 'RW' => 0, 'TYPE' => 'INT', 'SIZE' => 'smallest', 'HIDDEN' => 1],
        'list' => ['NAME' => 'Liste', 'RW' => 2, 'TYPE' => 'TEXT', 'SIZE' => 'small'],
        'entry' => ['NAME' => 'Datum', 'RW' => 2, 'TYPE' => 'TEXT', 'SIZE' => 'big'],
        'count' => ['NAME' => 'Count', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'lastdate' => ['NAME' => 'Letzte Nutzung', 'RW' => 1, 'TYPE' => 'DATETIME', 'SIZE' => 'biggest']
    ]
];

global $database_t_array;
array_push($database_t_array, $database_t_biography_datalist);

function datalist_add($list, $entry)
{
    if ($entry == '')
        return;

    $entry = trim($entry);

    global $database_t_biography_datalist;
    $exists = database_select_unique_row($database_t_biography_datalist, 'list = :1 AND entry = :2', [$list, $entry]);
    if ($exists['id'] != '') {
        database_update($database_t_biography_datalist, 'count = :1, lastdate = CURRENT_TIMESTAMP', [$exists['count'] + 1], 'id = :2', [$exists['id']]);
    } else {
        database_insert($database_t_biography_datalist, [$list, $entry, 1, date('Y-m-d H:i:s')]);
    }
}

function datalist_list($list)
{
    global $database_t_biography_datalist;
    $entries = database_select($database_t_biography_datalist, '*', 'list = :1', [$list]);

    $cnt = 99;
    echo '<datalist id="' . $list . '">';
    foreach ($entries as $entry) {
        echo '<option value="' . $entry['entry'] . '">' . htmlspecialchars($entry['entry']) . '</option>';
        $cnt--;
        if ($cnt <= 0)
            break;
    }
    echo '</datalist>';
}

function datalist_decount($list, $days)
{
    global $database_t_biography_datalist;
    $results = database_select($database_t_biography_datalist, '*', 'list = :1 AND count > 0', [$list]);

    foreach ($results as $result) {
        $lastdate = date_create($result['lastdate']);
        $now = date_create();
        $interval = date_diff($lastdate, $now);
        if ($interval->days >= $days) {
            $newcount = intval(max(0, $result['count'] - $interval->days / $days));
            database_update($database_t_biography_datalist, 'count = :1', [$newcount], 'id = :2', [$result['id']]);
        }
    }
}
