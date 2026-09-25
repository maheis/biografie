<?php

include('auth/auth.php');

// [$id, $list, $entry, $count]
function database_i_biography_datalist($method, $id, $list, $entry, $count, $lastupdate)
{
    global $database_t_biography_datalist;

    if ($method == 'save') {
        database_update($database_t_biography_datalist, 'list = :1, entry = :2, count = :3, lastdate = :4', [$list, $entry, $count, $lastupdate], 'id = :5', [$id]);
    } elseif ($method == 'add') {
        database_insert($database_t_biography_datalist, [$list, $entry, $count, $lastupdate]);
    } elseif ($method == 'delete') {
        database_delete($database_t_biography_datalist, 'id = :1', [$id]);
    }
}

$ReURL = 'index.php?ReURL=500';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_or_die();
    $count = (isset($_POST['count']) ? intval(xss_filter($_POST['count'])) : -1);

    for ($cnt = 0; $cnt <= $count; $cnt++) {
        $id = intval(xss_filter($_POST['id' . $cnt]));
        $list = xss_filter($_POST['list' . $cnt]);
        $entry = xss_filter($_POST['entry' . $cnt]);
        $count_ = intval(xss_filter($_POST['count' . $cnt]));
        $lastupdate = xss_filter($_POST['lastdate' . $cnt]);
        $ReURL = str_replace('&amp;', '&', xss_filter($_POST['ReURL']));
        if ($ReURL == '')
            $ReURL = 'index.php?ReURL=settings&database';
        $delete = intval(xss_filter($_POST['delete' . $cnt]));

        $method = ($delete == 1 ? 'delete' : 'save');
        if ($cnt == $count && $list != '') {
            if (database_select_unique_value($database_t_biography_datalist, 'id', 'id = :1', [$id]) == '') {
                $method = 'add';
            } else {
                $method = 'error';
            }
        }

        database_i_biography_datalist($method, $id, $list, $entry, $count_, $lastupdate);
    }
}

redirect_to('/' . $ReURL);