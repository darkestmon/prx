<?php
    include "../../db_connect.php";

    $changes = json_decode($_POST["changes"], true);
    foreach( $changes["topFilter"] AS $key => $newValues ) {
        if(implode("", $newValues) == "") {
            $query = db_query("doTopPrDeletes", $key);
        } else {
            $query = db_query("doTopPrUpdates", $key, $newValues[0], $newValues[1]);
        }
    }
    foreach( $changes["topFilterAdds"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            $query = db_query("doTopPrInserts", $newValues[0], $newValues[1]);
        }
    }
    foreach( $changes["binaries"] AS $key => $newValues ) {
        if(implode("", $newValues) == "") {
            $query = db_query("doBinaryDeletes", $key);
        } else {
            $query = db_query("doBinaryUpdates", $key, $newValues[0], $newValues[1], $newValues[2]);
        }
    }
    foreach( $changes["binariesAdds"] AS $newValues ) {
        if(implode("", $newValues) != "") {
            $query = db_query("doBinaryInserts", $newValues[0], $newValues[1], $newValues[2]);
        }
    }
    echo json_encode(array("result"=>($query==true)));

?>
