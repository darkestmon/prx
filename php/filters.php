<?php

function generateFilters($prData) {
    $assignees = array();
    foreach($prData["prList"] as $prItem){
        foreach($prItem["responsiblePerson"] as $names){
            if(!in_array($names["shortName"], $assignees))
                $assignees[] = $names["shortName"];
        }
    }
    sort($assignees);

    $topPrs = array();
    $query = db_query("getAllTopPrTags");
    while($row = db_fetch_assoc($query)){
        $topPrs[$row["name"]] = $row["keywords"];
    }

    $releases = array();
    $query = db_query("getAllReleaseNames");
    while($row = db_fetch_assoc($query)){
        $releases[] = $row["name"];
    }

    $binaries = array();
    $query = db_query("getAllBinaries");
    while($row = db_fetch_assoc($query)){
        $binaries[] = $row["name"];
    }
?>
<div class="filters-panel container-fluid">
    <div class="row">
        <!--div class='col-md-1'><h6>Filters:</h6></div-->
        <div class='col-md-2'>
            By Main Release:<br>
            <div id="releaseFilter" class="check-select" style="display: flex">
                <button type="button" class="filter-btn btn btn-xs"><i class="icon fas fa-check"></i></button>
                <select class="selectpicker" style='padding:0' data-style="btn-light" data-live-search="true" data-actions-box="true" multiple data-size="15">
<?php
    foreach($releases as $release) {
        if(!array_key_exists($release, $prData["releases"])) {
          $prData["releases"][$release] = 0;
        }
        $count = $prData["releases"][$release] +0;
        echo        "<option data-subtext='({$count})' value='{\"release_flag\":[\"{$release}\"]}'>{$release}</option>";
    }
    if(!array_key_exists("others", $prData["releases"])) {
      $prData["releases"]["others"] = 0;
    }
    $count = $prData["releases"]["others"] +0;
    echo            "<option data-subtext='({$count})' value='{\"release_flag_none\":\"true\"}'>OTHERS</option>";
?>
                </select>
            </div>
        </div>
        <div class='col-md-2'>
            By Binaries:<br>
            <div id="binariesFilter" class="check-select" style="display: flex">
                <button type="button" class="filter-btn btn btn-xs"><i class="icon fas fa-check"></i></button>
                <select class="selectpicker" style='padding:0' data-style="btn-light" data-live-search="true" data-actions-box="true" multiple data-size="15">
<?php
    foreach($binaries as $binary) {
        $count = $prData["binaries"][$binary] +0;
        echo        "<option data-subtext='({$count})' value='{\"binary\":[\"{$binary}\"]}'>{$binary}</option>";
    }
    if(!array_key_exists("others", $prData["binaries"])) {
      $prData["binaries"]["others"] = 0;
    }
    $count = $prData["binaries"]["others"] +0;
    echo            "<option data-subtext='({$count})' value='{\"binary_others\":\"true\"}'>OTHERS</option>";
?>
                </select>
            </div>
        </div>
        <div class='col-md-2'>
            By Top PR's:<br>
            <div id="topPrFilter" class="check-select" style="display: flex">
                <button type="button" class="filter-btn btn btn-xs"><i class="icon fas fa-check"></i></button>
                <select class="selectpicker" style='padding:0' data-style="btn-light" data-live-search="true" data-actions-box="true" multiple data-size="15">
<?php
    foreach($topPrs as $topName=>$topTag) {
        //$count = $prData["topPrs"][$topTag] +0;
        $count=0;
        foreach($prData["prList"] as $prItem){
            $matched = false;
            foreach(explode(",", $topTag) as $topTagValue){
                if(strpos(implode(",", $prItem["topImportance"]), trim($topTagValue)) !== false) {
                    $count++;
                    $matched = true;
                    break;
                }
            }
        }
        $filterValues=implode("\",\"",explode(",", $topTag));
        echo        "<option data-subtext='({$count})' value='{\"top\":[\"{$filterValues}\"]}'>{$topName}</option>";
    }
?>
                </select>
            </div>
        </div>
        <div class='col-md-2'>
            By Assignee:<br>
            <div id="assigneeFilter" class="check-select" style="display: flex">
                <button type="button" class="filter-btn btn btn-xs"><i class="icon fas fa-check"></i></button>
                <select class="selectpicker" style='padding:0' data-style="btn-light" data-live-search="true" data-actions-box="true" multiple data-size="15">
<?php
    if(!array_key_exists("unassigned", $prData["assignees"])) {
      $prData["assignees"]["unassigned"] = 0;
    }
    $count = $prData["assignees"]["unassigned"] +0;
    echo            "<option data-subtext='({$count})' value='{\"responsible_empty\":\"true\"}'>~ UNASSIGNED ~</option>";
    foreach($assignees as $name) {
        $count = $prData["assignees"][$name] +0;
        echo        "<option data-subtext='({$count})' value='{\"responsible\":[\"{$name}\"]}'>{$name}</option>";
    }
?>
                </select>
            </div>
        </div>
        <div class='col-md-2'>
            By Keyword:<br>
            <div id="keywordFilter" class="check-text" style="display: flex">
                <button type="button" class="filter-btn btn btn-xs"><i class="icon fas fa-check"></i></button>
                <input type="text" class="keyword-input" placeholder="Enter words here" title="Enter keywords separated by comma">
            </div>
        </div>
    </div>
</div>
<?php
}

?>
