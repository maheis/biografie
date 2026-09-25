<?php

echo '<h1>Biografie</h1>';

echo '<a class="button bigger themecolor" href="index.php?ReURL=settings&clean"><i class="fad fa-broom fa-fw"></i></a> &nbsp;';

if (isset($_GET['clean'])) {
    datalist_decount('food', 90);
    datalist_decount('drinks', 90);
    datalist_decount('activity', 7);

    echo '<div class="notice success">Datenbereinigung durchgeführt.</div>';
}