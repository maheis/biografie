<?php

switch ($ReURL) {
    case 'biography_history':
        $ReURL_ = 'modules/biography/biography_history.php';
        break;
    case 'biography_list':
        $ReURL_ = 'modules/biography/biography_list.php';
        break;
}

if (substr($ReURL, 0, 21) == 'database_i_biography_') {
    $ReURL_ = 'modules/biography/database/' . $ReURL . '.php';
}