<?php

//CREATE-Statement
database_exec('CREATE TABLE IF NOT EXISTS biography_list (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    status INT DEFAULT 0 NOT NULL,
    date INTEGER DEFAULT CURRENT_TIMESTAMP NOT NULL,
    activity TEXT,
    comment TEXT,
    water FLOAT DEFAULT 0 NOT NULL,
    drinks TEXT,
    food TEXT,
    sys INTEGER,
    dia INTEGER,
    pulse INTEGER,
    pee INT,
    poop INT
);');

//INSERT
// [$id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop]

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
$database_t_biography_list = [
    'TABLE' => 'biography_list',
    'RW' => 1,
    'NAME' => 'Biografien',
    'ORDER' => 'date DESC',
    'COLUMNS' => [
        'id' => ['NAME' => 'ID', 'RW' => 0, 'TYPE' => 'INT', 'SIZE' => 'smallest', 'HIDDEN' => 1],
        'status' => ['NAME' => 'Status', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small', 'DEFAULT' => 0, 'LIST' => ['0' => 'in Bearbeitung', '1' => 'gespeichert', '-1' => 'gelöscht']],
        'date' => ['NAME' => 'Datum', 'RW' => 1, 'TYPE' => 'DATETIME', 'SIZE' => 'small'],
        'activity' => ['NAME' => 'Aktivität', 'RW' => 1, 'TYPE' => 'TEXT', 'SIZE' => 'big'],
        'comment' => ['NAME' => 'Kommentar', 'RW' => 1, 'TYPE' => 'TEXT', 'SIZE' => 'big'],
        'water' => ['NAME' => 'Wasser ml', 'RW' => 1, 'TYPE' => 'FLOAT', 'SIZE' => 'small', 'DEFAULT' => 0],
        'drinks' => ['NAME' => 'Getränke', 'RW' => 1, 'TYPE' => 'TEXT', 'SIZE' => 'big'],
        'food' => ['NAME' => 'Nahrung', 'RW' => 1, 'TYPE' => 'TEXT', 'SIZE' => 'big'],
        'sys' => ['NAME' => 'SYS mmHg', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'dia' => ['NAME' => 'DIA mmHg', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'pulse' => ['NAME' => 'Pulse /min', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'pee' => ['NAME' => 'Uriniert', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small'],
        'poop' => ['NAME' => 'Stuhlgang', 'RW' => 1, 'TYPE' => 'INT', 'SIZE' => 'small']
    ]
];

global $database_t_array;
array_push($database_t_array, $database_t_biography_list);


function biography_entry($bio, $title)
{
    biography_error();

    global $database_t_biography_list;

    if (!$bio) {
        $bio['id'] = 0;
        $bio['date'] = date('Y-m-d H:i:s');
        $bio['activity'] = '';
        $bio['comment'] = '';
        $bio['water'] = 0;
        $bio['drinks'] = '';
        $bio['food'] = '';
        $bio['sys'] = 0;
        $bio['dia'] = 0;
        $bio['pulse'] = 0;
        $bio['pee'] = 0;
        $bio['poop'] = 0;
    }

    echo '<table class="withBorder">';
    echo '<thead>';
    echo '<tr>';
    echo '<th colspan="2"> ' . $title . ' <button class="tiny orange rounded" onclick="dialogOpen(\'dialog_biography_' . $bio['id'] . '\')"><i class="fad fa-edit fa-fw"></i></button> <a class="button tiny blue rounded" href="index.php?ReURL=biography_history&listid=' . $bio['id'] . '"><i class="fad fa-history fa-fw"></i></a></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    echo '<tr>';
    echo '<td style="width: 0px;"> Datum: </td>';
    echo '<td> ' . date_format(date_create($bio['date']), 'd.m.Y H:i') . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Aktivität: </td>';
    echo '<td> ' . $bio['activity'] . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Kommentar: </td>';
    echo '<td> ' . $bio['comment'] . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Wasser: </td>';
    echo '<td> ' . str_replace('.', ',', $bio['water']) . ' ml </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Getränke: </td>';
    echo '<td> ' . $bio['drinks'] . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Nahrung: </td>';
    echo '<td> ' . $bio['food'] . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Blutdruck: </td>';
    echo '<td> ' . sys_dia($bio, true) . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Puls: </td>';
    echo '<td> ' . pulse($bio, true) . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Uriniert: </td>';
    echo '<td> ' . $bio['pee'] . ' </td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td> Stuhlgang: </td>';
    echo '<td> ' . $bio['poop'] . ' </td>';
    echo '</tbody>';
    echo '</table>';

    echo '<dialog id="dialog_biography_' . $bio['id'] . '">';
    echo database_select_1pager_edit($database_t_biography_list, 'id = :1', [$bio['id']], 'index.php');
    echo '</dialog>';
}

function biography_list($title, string $where = '', array $where_values = [], $cal = '')
{
    biography_error();

    global $database_t_biography_list, $print;
    $bios = database_select($database_t_biography_list, '*', $where, $where_values);

    $sum_water = 0;
    $sum_drinks = 0;
    $sum_pee = 0;
    $sum_poop = 0;

    echo '<table class="withBorder"' . ($print ? '' : ' style="white-space: nowrap;"') . '">';
    echo '<thead>';
    echo '<tr>';
    if (!$print)
        echo '<th style="min-width: 43px; max-width: 43px;"></th>';
    echo '<th colspan="11"> ' . $title . ' </th>';
    echo '</tr>';
    echo '<tr>';
    if (!$print)
        echo '<th></th>';
    echo '<th style="width: 1px;"> Datum </th>';
    echo '<th> Aktivität </th>';
    echo '<th style="width: 1px;"> Wasser <font class="micro">ml</font> </th>';
    echo '<th style="width: 1px;"> Getränke </th>';
    echo '<th> Nahrung </th>';
    echo '<th style="width: 1px;"> Blutdruck <font class="micro">mmHg</font> </th>';
    echo '<th style="width: 1px;"> Puls <font class="micro">/min</font> </th>';
    echo '<th style="width: 1px;"> Uriniert </th>';
    echo '<th style="width: 1px;"> Stuhlgang </th>';
    echo '<th> Kommentar  </th>';
    echo ($print ? '' : '<th></th>');
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($bios as $bio) {
        echo '<tr>';
        if (!$print)
            echo '<td></td>';
        echo '<td> ' . date_format(date_create($bio['date']), 'd.m.Y H:i') . ' </td>';
        echo '<td> ' . $bio['activity'] . ' </td>';
        echo '<td style="text-align: center;"> ' . ($bio['water'] > 0 ? str_replace('.', ',', $bio['water']) : '') . ' </td>';
        echo '<td> ' . $bio['drinks'] . ' </td>';
        echo '<td> ' . $bio['food'] . ' </td>';
        echo '<td style="text-align: center;"> ' . sys_dia($bio) . ' </td>';
        echo '<td style="text-align: center;"> ' . pulse($bio) . ' </td>';
        echo '<td style="text-align: center;"> ' . ($bio['pee'] > 0 ? $bio['pee'] : '') . ' </td>';
        echo '<td style="text-align: center;"> ' . ($bio['poop'] > 0 ? $bio['poop'] : '') . ' </td>';
        echo '<td> ' . $bio['comment'] . ' </td>';
        echo ($print ? '' : '<td> <a class="button tiny orange rounded" onclick="dialogOpen(\'dialog_biography_' . $bio['id'] . '\')"><i class="fad fa-edit fa-fw"></i></a> <a class="button tiny blue rounded" href="index.php?ReURL=biography_history&listid=' . $bio['id'] . '"><i class="fad fa-history fa-fw"></i></a></td>');
        echo '</tr>';

        $sum_water += $bio['water'];
        $exp = explode(',', $bio['drinks']);
        foreach ($exp as $drink) {
            $sum_drinks = sum_($sum_drinks, $drink);
        }
        $sum_pee += $bio['pee'];
        $sum_poop += $bio['poop'];
    }

    if (!$print) {
        echo '<tr class="Sum">';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td style="text-align: center;"> ' . ($sum_water > 0 ? str_replace('.', ',', $sum_water) : '') . ' </td>';
        echo '<td> ' . $sum_drinks . ' </td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '<td style="text-align: center;"> ' . ($sum_pee > 0 ? $sum_pee : '') . ' </td>';
        echo '<td style="text-align: center;"> ' . ($sum_poop > 0 ? $sum_poop : '') . ' </td>';
        echo '<td></td>';
        echo '<td></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';

    foreach ($bios as $bio) {
        echo '<dialog id="dialog_biography_' . $bio['id'] . '">';
        echo database_select_1pager_edit($database_t_biography_list, 'id = :1', [$bio['id']], 'index.php?ReURL=biography_list&cal=' . $cal);
        echo '</dialog>';
    }

    echo '<br>';
    echo '<br>';
}

function biography_graph($title, string $where = '', array $where_values = [])
{
    global $database_t_biography_list, $print;
    $bios = database_select($database_t_biography_list, '*', $where, $where_values, 'date asc');

    $xValues = '';
    $ySys = '';
    $yDia = '';
    $yPulse = '';

    foreach ($bios as $bio) {
        if ($bio['sys'] > 0 && $bio['dia'] > 0 && $bio['pulse'] > 0) {
            $xValues .= '"' . date_format(date_create($bio['date']), 'd.m.Y H:i') . '",';
            $ySys .= $bio['sys'] . ',';
            $yDia .= $bio['dia'] . ',';
            $yPulse .= $bio['pulse'] . ',';
        }
    }

    $canvaid = uniqid();
    echo '<br>';
    echo '<div>';
    echo $title;
    echo '<script type="text/javascript" src="./thirdparty/chart.js/dist/chart.umd.js"></script>';
    echo '<canvas id="' . $canvaid . '" style="margin-left: 40px; max-height: 666px; width: 100%"></canvas>';

    echo '<script>';
    echo 'new Chart("' . $canvaid . '", {';
    echo ' type: "line",';
    echo ' data: {';
    echo '   labels: [' . $xValues . '],';
    echo '   datasets: [{';
    echo '     data: [' . $ySys . '],';
    echo '     label: "Sys",';
    echo '     borderColor: "#E57373",';
    echo '     cubicInterpolationMode: "monotone",';
    echo '     fill: false';
    echo '   }, {';
    echo '     data: [' . $yDia . '],';
    echo '     label: "Dia",';
    echo '     borderColor: "#81C784",';
    echo '     cubicInterpolationMode: "monotone",';
    echo '     fill: false';
    echo '   }, {';
    echo '     data: [' . $yPulse . '],';
    echo '     label: "Pulse",';
    echo '     borderColor: "#FFF176",';
    echo '     cubicInterpolationMode: "monotone",';
    echo '     fill: false';
    echo '   }]';
    echo ' },';
    echo ' options: {';
    echo '   plugins: {';
    echo '     legend: {';
    echo '       position: "chartArea",';
    echo '       labels: {';
    echo '         color: "#999",';
    echo '         font: {';
    echo '           size: 17';
    echo '         }';
    echo '       }';
    echo '     }';
    echo '   }';
    echo ' }';
    echo '});';
    echo '</script>';
    echo '</div>';
}

function biography_error()
{
    if (isset($_GET['err'])) {
        $err = xss_filter($_GET['err']);

        switch ($err) {
            case '2inWork':
                echo '<dialog id="err">';
                echo '<div class="notice error">Es gibt bereits einen Eintrag der sich in Bearbeitung befindet!</div><br>';
                echo '    <div style="text-align: center">';
                echo '        <a class="rounded button" onclick="dialogClose(\'err\')"><i class="fad fa-check fa-fw"></i></a>';
                echo '    </div>';
                echo '</dialog>';
                echo '<img src="favicon.ico" onload="dialogOpen(\'err\')" width="0" height="0">';
                break;
        }
    }
}

function sum_($old, $new)
{
    $ret = '';
    if (empty($old)) {
        $ret = trim($new);
    } elseif (trim($new) == '') {
        $ret = $old;
    } else {
        $new = trim($new);
        // Extrahiere führende Zahl nur wenn mit Leerzeichen getrennt, sonst Count=1
        if (preg_match('/^(\d+)\s+(.*)$/u', $new, $nmatch)) {
            $newCount = (int) $nmatch[1];
            $newName = trim($nmatch[2]);
            if ($newName === '') {
                $ret = $old . ', ' . $new;
                return $ret;
            }
        } else {
            $newCount = 1;
            $newName = $new;
        }

        // Regex: Erfasse Sep, optional Zahl (mit mindestens einem Leerzeichen wenn vorhanden) und Getränk (case-insensitive)
        $pattern = '/((?:^|, ?))(?:([0-9]+) +)?' . preg_quote($newName, '/') . '(?=,|$)/i';
        if (preg_match($pattern, $old, $matches, PREG_OFFSET_CAPTURE)) {
            $sep = $matches[1][0];
            $number = isset($matches[2]) ? $matches[2][0] : '';
            $fullMatch = $matches[0][0];
            if ($number !== '') {
                $newNumber = ((int) $number) + $newCount;
            } else {
                // altes Element ohne Zahl = 1
                $newNumber = 1 + $newCount;
            }
            $replace = $sep . $newNumber . ' ' . $newName;
            $ret = substr_replace($old, $replace, $matches[0][1], strlen($fullMatch));
        } else {
            // $new existiert nicht, einfach anhängen
            $ret = $old . ', ' . $new;
        }
    }

    return $ret;
}

function sys_dia($bio, $ext = false)
{
    $ret = '';
    if ($bio['sys'] > 0) {
        $color = '';
        if ($bio['sys'] <= 120)
            $color = 'green';
        elseif ($bio['sys'] <= 129)
            $color = 'darkgreen';
        elseif ($bio['sys'] <= 139)
            $color = 'orange';
        else
            $color = 'red';

        $ret .= '<font class="bold ' . $color . '">';
        $ret .= $bio['sys'];
        $ret .= '</font>';

        $ret .= ' / ';
        $color = '';
        if ($bio['dia'] < 80)
            $color = 'green';
        elseif ($bio['dia'] <= 84)
            $color = 'darkgreen';
        elseif ($bio['dia'] <= 89)
            $color = 'orange';
        else
            $color = 'red';
        $ret .= '<font class="bold ' . $color . '">';
        $ret .= $bio['dia'];
        $ret .= '</font>';
        if ($ext)
            $ret .= ' mmHg';
    }

    return $ret;
}

function pulse($bio, $ext = false)
{
    $ret = '';

    if ($bio['pulse'] > 0) {
        $color = '';
        if ($bio['pulse'] < 60)
            $color = 'blue';
        elseif ($bio['pulse'] <= 100)
            $color = 'green';
        elseif ($bio['pulse'] <= 130)
            $color = 'darkgreen';
        elseif ($bio['pulse'] <= 160)
            $color = 'orange';
        else
            $color = 'red';

        $ret .= '<font class="bold ' . $color . '">';
        $ret .= $bio['pulse'];
        $ret .= '</font>';
        if ($ext)
            $ret .= ' /min';
    }

    return $ret;
}
