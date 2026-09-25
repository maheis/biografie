<?php

include('auth/auth.php');

// function database_i_biography_history()
// -> database_t_biography_history.php

$ReURL = 'index.php?ReURL=500';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    verify_csrf_or_die();
    $count = (isset($_POST['count']) ? intval(xss_filter($_POST['count'])) : -1);

    for ($cnt = 0; $cnt <= $count; $cnt++) {
        $id = intval(xss_filter($_POST['id' . $cnt]));
        $listid = intval(xss_filter($_POST['listid' . $cnt]));
        $date = xss_filter($_POST['date' . $cnt]);
        $info = xss_filter($_POST['info' . $cnt]);
        $ReURL = str_replace('&amp;', '&', xss_filter($_POST['ReURL']));
        if ($ReURL == '')
            $ReURL = 'index.php?ReURL=settings&database';
        $delete = intval(xss_filter($_POST['delete' . $cnt]));

        $method = ($delete == 1 ? 'delete' : 'save');
        if ($cnt == $count && $info != '') {
            if (database_select_unique_value($database_t_biography_history, 'id', 'id = :1', [$id]) == '') {
                $method = 'add';
            } else {
                $method = 'error';
            }
        }

        database_i_biography_history($method, $id, $listid, $date, $info);
    }
}

redirect_to('/' . $ReURL);

?>