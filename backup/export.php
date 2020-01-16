<?php
    include "../db_connect.php";
    $binaries = array();
    $change_log = array();
    $releases = array();
    $top_prs = array();


    $query = db_query("getAllBinaries");
    while($row = db_fetch_assoc($query)){
        $row_values = array($row["name"], $row["field"], $row["values"]);
        $binaries[] = $row_values;
    }

    $query = db_query("getChangeLog");
    while($row = db_fetch_assoc($query)){
        $row_values = array($row["date"], $row["description"], $row["version"]);
        $change_log[] = $row_values;
    }

    $query = db_query("getAllReleases");
    while($row = db_fetch_assoc($query)){
        $row_values = array($row["name"], $row["view_id"]);
        $releases[] = $row_values;
    }

    $query = db_query("getAllTopPrTags");
    while($row = db_fetch_assoc($query)){
        $row_values = array($row["name"], $row["keywords"]);
        $top_prs[] = $row_values;
        // echo json_encode($row_values)."<br>";
    }

    $backup = array("binaries" => $binaries, "change_log" => $change_log, "releases" => $releases, "top_prs" => $top_prs);
    echo json_encode($backup);
?>
