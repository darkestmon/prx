<?php
    include "../../db_connect.php";

    if ($_SERVER["REQUEST_METHOD"] == "GET") { getPrNotesHandler(); }
    else if ($_SERVER["REQUEST_METHOD"] == "POST") { postPrNotesHandler(); }
    else { echo $_SERVER["REQUEST_METHOD"]; }

    // Implement getNotesByPrId by GET METHOD
    // Implement addOrUpdateByPrId by POST METHOD

    function getPrNotesHandler() {
        $prId = $_GET["prid"] ?? "";
        $query = db_query("getNotesById", $prId);
        $row = db_fetch_assoc($query);

        if ($row["prid"]) {
            echo formatResponse($row["prid"], $row["notes"]);
        } else {
            echo formatResponse($prId, "");
        }
    }

    function postPrNotesHandler() {
        $prId = $_POST["prid"] ?? "";
        $notes = $_POST["notes"] ?? "";
        $query = db_query("deleteNotesById", $prId);
        $query = db_query("addNotesById", $prId, $notes);
        $ret = $query ? formatResponse($prId, $notes) : "" ;
        echo $ret;
    }

    function formatResponse($prid, $notes) {
        return json_encode(array(
            "prid" => $prid,
            "notes" => $notes
        ));
    }

?>
