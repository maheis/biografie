<?php

include('auth/auth.php');

include('core/head.php');


$cal = -1;
if (isset($_GET['cal'])) {
    $cal = intval(xss_filter($_GET['cal']));
}

if ($cal == 1 || $cal == 7 || $cal == 30 || $cal == 365) {
    $date = date_format(date_create(), 'Y-m-d');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        verify_csrf_or_die();
        $date = xss_filter($_POST['date']);
        if (isset($_POST['date-'])) {
            $date = date('Y-m-d', strtotime($date . ' -' . $cal . ' day'));
        }
        if (isset($_POST['date+'])) {
            $date = date('Y-m-d', strtotime($date . ' +' . $cal . ' day'));
        }
    }

    if (!$print) {
        echo '<form style="white-space: nowrap;" method="post" action="index.php?ReURL=biography_list&cal=' . $cal . '">';
        echo csrf_input();
        echo '<button class="rounded themecolor" name="date-" type="submit" value="0"><i class="fad fa-arrow-left fa-fw"></i></button>&nbsp;&nbsp;&nbsp;';
        echo '<font class="medium"><input style="width: 185px" name="date" type="date" value="' . $date . '"></font>';
        echo '&nbsp;&nbsp;&nbsp;<button class="rounded themecolor" name="date+" type="submit" value="0" ' . (date('Y-m-d', strtotime($date)) >= date('Y-m-d') ? 'disabled' : '') . '><i class="fad fa-arrow-right fa-fw"></i></button>';
        if ($graph)
            echo '&nbsp;&nbsp;&nbsp;<a class="themecolor button rounded" name="graph" href="index.php?ReURL=biography_list&cal=' . $cal . '"><i class="fad fa-calendar fa-fw"></i></a>';
        else
            echo '&nbsp;&nbsp;&nbsp;<a class="themecolor button rounded" name="graph" href="index.php?ReURL=biography_list&cal=' . $cal . '&graph"><i class="fad fa-chart-line fa-fw"></i></a>';
        echo '&nbsp;&nbsp;&nbsp;<a class="themecolor button rounded" name="cal" href="index.php?ReURL=biography_list&cal=' . $cal . '&print" target="_blank"><i class="fad fa-print fa-fw"></i></a>';
        echo '</form>';
    }

    switch ($cal) {
        case 1:
            if ($graph)
                biography_graph('heute', 'date >= :1 and date <= :2', [$date, date('Y-m-d', strtotime($date . ' +1 day'))]);
            else
                biography_list('heute', 'date >= :1 and date <= :2', [$date, date('Y-m-d', strtotime($date . ' +1 day'))], $cal);
            break;
        case 7:
            if ($graph)
                biography_graph('letzte 7 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -6 days')), date('Y-m-d', strtotime($date . ' +1 day'))]);
            else
                biography_list('letzte 7 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -6 days')), date('Y-m-d', strtotime($date . ' +1 day'))], $cal);
            break;
        case 30:
            if ($graph)
                biography_graph('letzte 30 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -29 days')), date('Y-m-d', strtotime($date . ' +1 day'))]);
            else
                biography_list('letzte 30 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -29 days')), date('Y-m-d', strtotime($date . ' +1 day'))], $cal);
            break;
        case 365:
            if ($graph)
                biography_graph('letzte 365 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -365 days')), date('Y-m-d', strtotime($date . ' +1 day'))]);
            else
                biography_list('letzte 365 Tage', 'date >= :1 and date <= :2', [date('Y-m-d', strtotime($date . ' -365 days')), date('Y-m-d', strtotime($date . ' +1 day'))], $cal);
            break;
    }
} else {
    redirect_to('/');
}

include('core/footer.php');
