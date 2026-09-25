<?php

include('auth/auth.php');

// [$id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop]
function database_i_biography_list($method, $id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop)
{
    global $database_t_biography_list;

    if ($method == 'save') {

        $history = '';
        $bio = database_select_unique_row($database_t_biography_list, 'id = :1', [$id]);
        $status = chk_val_history($bio['status'], $status, 'status', $history);
        $date = chk_val_history($bio['date'], $date, 'date', $history);
        $activity = chk_val_history($bio['activity'], $activity, 'activity', $history);
        $comment = chk_val_history($bio['comment'], $comment, 'comment', $history);
        $water = chk_val_history($bio['water'], $water, 'water', $history);
        $drinks = chk_val_history($bio['drinks'], $drinks, 'drinks', $history);
        $food = chk_val_history($bio['food'], $food, 'food', $history);
        $sys = chk_val_history($bio['sys'], $sys, 'sys', $history);
        $dia = chk_val_history($bio['dia'], $dia, 'dia', $history);
        $pulse = chk_val_history($bio['pulse'], $pulse, 'pulse', $history);
        $pee = chk_val_history($bio['pee'], $pee, 'pee', $history);
        $poop = chk_val_history($bio['poop'], $poop, 'poop', $history);
        database_i_biography_history('add', '', $id, date_format(date_create(), 'Y-m-d\TH:i'), $history);

        database_update($database_t_biography_list, 'status = :1, date = :2, activity = :3, comment = :4, water = :5, drinks = :6, food = :7, sys = :8, dia = :9, pulse = :10, pee = :11, poop = :12', [$status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop], 'id = :13', [$id]);
    } elseif ($method == 'add') {
        database_insert($database_t_biography_list, [$status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop]);

        $id = database_select_unique_value($database_t_biography_list, 'id', 'date = :1', [$date], '');
        $history = '';
        $status = chk_val_history(0, $status, 'status', $history);
        $date = chk_val_history('', $date, 'date', $history);
        $activity = chk_val_history('', $activity, 'activity', $history);
        $comment = chk_val_history('', $comment, 'comment', $history);
        $water = chk_val_history(0, $water, 'water', $history);
        $drinks = chk_val_history('', $drinks, 'drinks', $history);
        $food = chk_val_history('', $food, 'food', $history);
        $sys = chk_val_history(0, $sys, 'sys', $history);
        $dia = chk_val_history(0, $dia, 'dia', $history);
        $pulse = chk_val_history(0, $pulse, 'pulse', $history);
        $pee = chk_val_history(0, $pee, 'pee', $history);
        $poop = chk_val_history(0, $poop, 'poop', $history);
        database_i_biography_history('add', '', $id, date_format(date_create(), 'Y-m-d\TH:i'), 'add ! ' . $history);
    } elseif ($method == 'delete') {
        database_delete($database_t_biography_list, 'id = :1', [$id]);
        database_i_biography_history('add', '', $id, date_format(date_create(), 'Y-m-d\TH:i'), 'delete');
    }
}

$ReURL = 'index.php?ReURL=500';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_or_die();
    $form = (isset($_POST['form']) ? xss_filter($_POST['form']) : '');

    if ($form == 'add_biography') {
        $history = '';

        $cnt = 0;
        $id = intval(xss_filter($_POST['id' . $cnt]));
        $status = intval(xss_filter($_POST['status' . $cnt]));
        if ($status == '')
            $status = 0;
        $date = xss_filter($_POST['date' . $cnt]);
        $activity = xss_filter($_POST['activity' . $cnt]);
        $comment = xss_filter($_POST['comment' . $cnt]);
        $water = floatval(str_replace(',', '.', (isset($_POST['water']) ? xss_filter($_POST['water']) : xss_filter($_POST['water' . $cnt]))));
        $drinks = (isset($_POST['coffee']) ? 'Kaffee' : (isset($_POST['apple']) ? 'Apfelschorle' : (isset($_POST['beer']) ? 'Bier' : xss_filter($_POST['drinks' . $cnt]))));
        $food = xss_filter($_POST['food' . $cnt]);
        $sys = intval(xss_filter($_POST['sys' . $cnt]));
        $dia = intval(xss_filter($_POST['dia' . $cnt]));
        $pulse = intval(xss_filter($_POST['pulse' . $cnt]));
        $pee = intval(xss_filter($_POST['pee' . $cnt]));
        $poop = intval(xss_filter($_POST['poop' . $cnt]));
        $ReURL = str_replace('&amp;', '&', str_replace('&amp;', '&', xss_filter($_POST['ReURL'])));

        datalist_add('activity', $activity);
        datalist_add('drinks', $drinks);
        datalist_add('food', $food);

        $bio = database_select_unique_row($database_t_biography_list, 'id = :1', [$id]);
        if ($bio == null) {
            $cnt = database_select_unique_value($database_t_biography_list, 'count(*)', 'status = 0', [], 0);
            if ($cnt > 0) {
                $ReURL .= ($ReURL == '' ? '?' : '&') . 'err=2inWork';
            } else {
                database_i_biography_list('add', $id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop);
            }
        } else {
            $status = chk_val($bio['status'], $status);
            $date = chk_val($bio['date'], $date);
            $activity = add_text($bio['activity'], $activity);
            $comment = add_text($bio['comment'], $comment);
            $water = sum_int($bio['water'], $water);
            $drinks = sum_text($bio['drinks'], $drinks);
            $food = add_text($bio['food'], $food);
            $sys = chk_gt_0($bio['sys'], $sys);
            $dia = chk_gt_0($bio['dia'], $dia);
            $pulse = chk_gt_0($bio['pulse'], $pulse);
            $pee = sum_int($bio['pee'], $pee);
            $poop = sum_int($bio['poop'], $poop);

            database_i_biography_list('save', $id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop);
        }
    } else {
        $count = (isset($_POST['count']) ? intval(xss_filter($_POST['count'])) : -1);

        for ($cnt = 0; $cnt <= $count; $cnt++) {
            $id = intval(xss_filter($_POST['id' . $cnt]));
            $status = intval(xss_filter($_POST['status' . $cnt]));
            if ($status == '')
                $status = 0;
            $date = xss_filter($_POST['date' . $cnt]);
            $activity = xss_filter($_POST['activity' . $cnt]);
            $comment = xss_filter($_POST['comment' . $cnt]);
            $water = floatval(str_replace(',', '.', xss_filter($_POST['water' . $cnt])));
            $drinks = xss_filter($_POST['drinks' . $cnt]);
            $food = xss_filter($_POST['food' . $cnt]);
            $sys = intval(xss_filter($_POST['sys' . $cnt]));
            $dia = intval(xss_filter($_POST['dia' . $cnt]));
            $pulse = intval(xss_filter($_POST['pulse' . $cnt]));
            $pee = intval(xss_filter($_POST['pee' . $cnt]));
            $poop = intval(xss_filter($_POST['poop' . $cnt]));
            $ReURL = str_replace('&amp;', '&', xss_filter($_POST['ReURL']));
            if ($ReURL == '')
                $ReURL = 'index.php?ReURL=settings&database';
            $delete = intval(xss_filter($_POST['delete' . $cnt]));

            $method = ($delete == 1 ? 'delete' : 'save');
            if ($cnt == $count && $date != '') {
                if (database_select_unique_value($database_t_biography_list, 'id', 'id = :1', [$id]) == '') {
                    $method = 'add';
                } else {
                    $method = 'error';
                }
            }

            database_i_biography_list($method, $id, $status, $date, $activity, $comment, $water, $drinks, $food, $sys, $dia, $pulse, $pee, $poop);
        }
    }
}

redirect_to('/' . $ReURL);

function add_text($old, $new)
{
    $ret = '';
    if ($new == '')
        $ret = $old;
    else {
        if ($old == '')
            $ret = $new;
        else
            $ret = $old . ', ' . $new;
    }

    return $ret;
}

function sum_int($old, $new)
{
    $sum = $old + $new;

    return $sum;
}

function sum_text($old, $new)
{
    $ret = '';
    if (empty($old)) {
        $ret = $new;
    } elseif ($new == '') {
        $ret = $old;
    } else {
        // Regex: Erfasse Komma/Leerzeichen, Zahl, Leerzeichen und Getränk
        $pattern = '/((?:^|, ?))(\d*) ?' . preg_quote($new, '/') . '(?=,|$)/';
        if (preg_match($pattern, $old, $matches, PREG_OFFSET_CAPTURE)) {
            $sep = $matches[1][0];
            $number = $matches[2][0];
            $fullMatch = $matches[0][0];
            if ($number !== '') {
                $newNumber = ((int) $number) + 1;
                $replace = $sep . $newNumber . ' ' . $new;
            } else {
                $replace = $sep . '2 ' . $new;
            }
            $ret = substr_replace($old, $replace, $matches[0][1], strlen($fullMatch));
        } else {
            // $new existiert nicht, einfach anhängen
            $ret = $old . ', ' . $new;
        }
    }

    return $ret;
}

function chk_val($old, $new)
{
    if ($old != $new) {
        return $new;
    }

    return $old;
}

function chk_gt_0($old, $new)
{
    if ($new == 0) {
        return $old;
    }

    return chk_val($old, $new);
}
function chk_val_history($old, $new, $field, &$history)
{
    if ($old != $new) {
        if ($history != '') {
            $history .= ', ';
        }
        $history .= $field . ': ' . $old . ' ↦ ' . $new;

        return $new;
    }

    return $old;
}

?>