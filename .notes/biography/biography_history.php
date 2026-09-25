<?php

include('auth/auth.php');

include('core/head.php');


$listid = -1;
if (isset($_GET['listid'])) {
    $listid = intval(xss_filter($_GET['listid']));
}

if ($listid > 0) {
    biography_history($listid);
} else {
    redirect_to('/');
}

include('core/footer.php');