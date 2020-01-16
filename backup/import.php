<?php
    include "../db_connect.php";
    // print_r($_POST);
    $backup = json_decode($_POST["backup_data"], true);
    // print_r( $backup);
    foreach( $backup["top_prs"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            db_query("doTopPrDeletes", $newValues[0]);
            db_query("doTopPrInserts", $newValues[0], $newValues[1]);
        }
    }

    foreach( $backup["binaries"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            db_query("doBinaryDeletes", $newValues[0]);
            db_query("doBinaryInserts", $newValues[0], $newValues[1], $newValues[2]);
        }
    }

    foreach( $backup["change_log"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            db_query("doChangeLogDeletes", $newValues[0]);
            db_query("doChangeLogInserts", $newValues[0], $newValues[1], $newValues[2]);
        }
    }

    foreach( $backup["releases"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            db_query("doReleasesDeletes", $newValues[0]);
            db_query("doReleasesInserts", $newValues[0], $newValues[1]);
        }
    }

    // header('Location: ./export.php');
    echo "Import done!";
 ?>
