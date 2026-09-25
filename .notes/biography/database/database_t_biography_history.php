<?php

//CREATE-Statement
database_exec('CREATE TABLE IF NOT EXISTS biography_history (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    listid INT NOT NULL,
    date INTEGER DEFAULT CURRENT_TIMESTAMP NOT NULL,
    info TEXT
);');

//INSERT
// [$id, $listid, $date, $info]

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
$database_t_biography_history = [
    'TABLE' => 'biography_history',
    'RW' => 1,
    'NAME' => 'Historie Biografie',
    'ORDER' => 'listid DESC, date DESC, id DESC',
    'COLUMNS' => [
        'id' => ['NAME' => 'ID', 'RW' => 0, 'TYPE' => 'INT', 'SIZE' => 'smallest', 'HIDDEN' => 1],
        'listid' => ['NAME' => 'Biografie', 'RW' => 2, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'date' => ['NAME' => 'Datum', 'RW' => 2, 'TYPE' => 'DATETIME', 'SIZE' => 'small'],
        'info' => ['NAME' => 'Info', 'RW' => 1, 'TYPE' => 'TEXT', 'SIZE' => 'big']
    ]
];

global $database_t_array;
array_push($database_t_array, $database_t_biography_history);

function database_i_biography_history($method, $id, $listid, $date, $info)
{
    global $database_t_biography_history;

    if ($method == 'save') {
        database_update($database_t_biography_history, 'listid = :1, date = :2, info = :3', [$listid, $date, $info], 'id = :4', [$id]);
    } elseif ($method == 'add') {
        database_insert($database_t_biography_history, [$listid, $date, $info]);
    } elseif ($method == 'delete') {
        database_delete($database_t_biography_history, 'id = :1', [$id]);
    }
}

function biography_history($listid)
{
    global $database_t_biography_history, $database_t_biography_list;

    $results = database_select($database_t_biography_history, '*', 'listid = :1', [$listid]);

    if ($results === false || count($results) == 0) {
        echo 'Keine Einträge gefunden';
        return;
    }

    database_select_1pager_edit($database_t_biography_list, 'id = :1', [$listid], 'index.php?ReURL=biography_history&listid=' . $listid);
    echo '<table class="withBorder" style="white-space: nowrap;">';
    echo '<thead>';
    echo '<tr>';
    echo '<th style="width: 1px;"> Datum </th>';
    echo '<th> Info </th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($results as $row) {
        echo '<tr>';
        echo '<td> ' . date_format(date_create($row['date']), 'd.m.Y H:i') . ' </td>';
        echo '<td> ' . $row['info'] . ' </td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
