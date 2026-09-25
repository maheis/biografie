<?php
echo '<div class="index">';
echo '  <a class="themecolor button rounded" name="cal" href="index.php"><i class="fad fa-book-user fa-fw"></i></a><br><br>';

if ($ReURL != 'settings') {

    $graph = '';
    if (isset($_GET['graph'])) {
        $graph = '&graph';
    }

    echo '  <a class="themecolor button rounded" name="cal" href="index.php?ReURL=biography_list&cal=1' . $graph . '"><i class="fad fa-calendar-day fa-fw"></i></a><br><br>';
    echo '  <a class="themecolor button rounded" name="cal" href="index.php?ReURL=biography_list&cal=7' . $graph . '"><i class="fad fa-calendar-week fa-fw"></i></a><br><br>';
    echo '  <a class="themecolor button rounded" name="cal" href="index.php?ReURL=biography_list&cal=30' . $graph . '"><i class="fad fa-calendar-alt fa-fw"></i></a><br><br>';
    echo '  <a class="themecolor button rounded" name="cal" href="index.php?ReURL=biography_list&cal=365' . $graph . '"><i class="fad fa-calendar-plus fa-fw"></i></a>';
}
echo '</div>';

echo '<a class="themecolor button rounded settings" name="cal" href="index.php?ReURL=settings"><i class="fad fa-cogs fa-fw"></i></a>';