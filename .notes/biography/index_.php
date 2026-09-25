<?php
$id = database_select_unique_value($database_t_biography_list, 'id', 'status = :1', [0], '');
?>

<form method="post" action="index.php?ReURL=database_i_biography_list" name="booking">
    <?php echo csrf_input(); ?>
    <button class="hide" name="status0" type="submit" value="0"></button> <!-- Button für Enter -->
    <input class="hide" name="id0" type="text" value="<?php echo $id; ?>">

    <font class="medium">
        <table style="margin: auto; width:0; white-space: nowrap;">
            <tbody>
                <tr>
                    <td rowspan="9" style="min-width: 40px; max-width: 40px;"></td>
                    <td style="text-align: right;"> Datum: </td>
                    <td> <input class="big" name="date0" type="datetime-local"
                            value="<?php echo date_format(date_create(), 'Y-m-d\TH:i'); ?>"
                            onchange="onInputChange(this)"> </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Aktivität: </td>
                    <td>
                        <input class="big" name="activity0" type="text" list="activity" value=""
                            onchange="onInputChange(this)">
                        <?php datalist_list('activity'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Wasser: </td>
                    <td>
                        <input class="small" name="water0" type="number" value="" onchange="onInputChange(this)">
                        ml&nbsp;
                        <button class="blue rounded" name="water" type="submit" value="250">
                            <font class="tiny"><i class="fad fa-tint fa-fw"></i> 250ml&nbsp;</font>
                        </button>&nbsp;
                        <button class="blue rounded" name="water" type="submit" value="500">
                            <font class="tiny"><i class="fad fa-tint fa-fw"></i> 500ml&nbsp;</font>
                        </button>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Getränke: </td>
                    <td>
                        <input class="small" name="drinks0" type="text" list="drinks" value=""
                            onchange="onInputChange(this)">&nbsp;
                        <?php datalist_list('drinks'); ?>
                        <button class="brown rounded" name="coffee" type="submit" value="1"><i
                                class="fad fa-coffee fa-fw"></i></button>&nbsp;
                        <button class="green rounded" name="apple" type="submit" value="1"><i
                                class="fad fa-apple-alt fa-fw"></i></button>&nbsp;
                        <button class="yellow rounded" name="beer" type="submit" value="1"><i
                                class="fad fa-beer fa-fw"></i></button>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Nahrung: </td>
                    <td>
                        <input class="big" name="food0" type="text" list="food" value="" onchange="onInputChange(this)">
                        <?php datalist_list('food'); ?>
                    </td>
                </tr>

                <tr>
                    <td style="text-align: right;"> Kommentar: </td>
                    <td> <input class="big" name="comment0" type="text" value="" onchange="onInputChange(this)"> </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Toilette: </td>
                    <td>
                        <button class="yellow rounded" name="pee0" type="submit" value="1"><i
                                class="fad fa-raindrops fa-fw"></i></button>&nbsp;
                        <button class="brown rounded" name="poop0" type="submit" value="1">
                            <i class="fad fa-poop fa-fw"></i></button>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Blutdruck: <br>
                        <font class="micro"> SYS mmHg / DIA mmHg </font>
                    </td>
                    <td>
                        <input class="smaller" name="sys0" type="number" value="" min="0" max="300"
                            onchange="onInputChange(this)"> / <input class="smaller" name="dia0" type="number" value=""
                            min="0" max="200" onchange="onInputChange(this)">
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right;"> Puls: <br>
                    </td>
                    <td> <input class="smaller" name="pulse0" type="number" value="" min="0" max="200"
                            onchange="onInputChange(this)"> /min
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right">
                        <button class="rounded green index-add" name="status0" type="submit" value="0"><i
                                class="fad fa-plus-circle fa-fw"></i></button>
                    </td>
                    <td style="text-align: center">
                        <a class="themecolor button rounded index-save" onclick="dialogOpen('save')">
                            <i class="fad fa-save fa-fw"></i>
                        </a>
                        <dialog id="save">Soll der Eintrag wirklich gespeichert werden?<br><br>
                            <div style="text-align: center">
                                <a class="rounded button red" onclick="dialogClose('save')"><i
                                        class="fad fa-times fa-fw"></i></a>
                                <button class="rounded green" name="status0" type="submit" value="1"><i
                                        class="fad fa-check fa-fw"></i></button>
                            </div>
                        </dialog>
                    </td>
                    <input class="hide" name="form" type="text" value="add_biography">
                </tr>
            </tbody>
        </table>
    </font>
</form>
<?php

$bio = database_select_unique_row($database_t_biography_list, 'id = :1', [$id]);
biography_entry($bio, 'in Bearbeitung');

echo '<br>';

$bio = database_select_unique_row($database_t_biography_list, 'date = (select max(date) from biography_list where status = 1)', []);
biography_entry($bio, 'Letzter Eintrag');
?>