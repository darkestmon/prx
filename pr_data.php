<?php

    function getPrData() {
        $now = time();
        $prData = array();
        $prData["prList"] = array();
        $prData["releases"] = array();
        $prData["binaries"] = array();
        $prData["assignees"] = array();
        $prData["topPrs"] = array();
        $prData["summary"] = array();
        $prData["fetchInfo"] = array();
        $query = db_query("getAllPrItems");
        while($row = db_fetch_assoc($query)){
            $prItem = array();
            $prItem["faultId"] = $row["problem_id"]*3;//json_decode($row["fault_id"]);//[0];
            $prItem["problemId"] = $row["problem_id"];
            $prItem["attached"] = json_decode($row["attached"]);
            $prItem["release"] = json_decode($row["rel"]);
            $prItem["severity"] = $row["severity"];
            $prItem["groupInCharge"] = $row["group_in_charge"];
            $prItem["subsystem"] = json_decode($row["subsystem"]);
            $prItem["title"] = $row["title"];
            $prItem["optional"] = $row["optional"];
            $prItem["additional"] = $row["additional"];

            $prdate = strtotime($row['reported_date']);
            $prItem["age"] = floor(($now - $prdate)/86400);

            $prItem["topImportance"] = array();
            foreach(json_decode($row["top_importance"]) as $item) {
                $prItem["topImportance"][] = $item;
                if(!array_key_exists($item, $prData["topPrs"])) {
                    $prData["topPrs"][$item] = 0;
                }
                $prData["topPrs"][$item]++;
            }
            if(count($prItem["topImportance"])==0) {
                $prData["topPrs"]["others"]++;
            }


            if( in_array($row["state"], array(STATE_RFT))) {
                $prItem["status"] = "rft";
            } else if( in_array($row["state"], array(STATE_FCC, STATE_FRS))) {
                $prItem["status"] = "fcc";
            } else {
                if(stripos(str_replace(":","",str_replace(" ", "", $row["rd_information"])), RC_KNOWN) || in_array($row["state"], array(STATE_CPR, STATE_CPS)))
                    $prItem["status"] = "rc_known";
                else
                    if( in_array($row["state"], array(STATE_NEW))) {
                        $prItem["status"] = "unassigned";
                    } else {
                        $prItem["status"] = "rc_unknown";
                    }
            }

            $prItem["responsiblePerson"] = array();
            foreach(json_decode($row["responsible_person"]) as $item) {
                $fullName = $item;
                $shortName = $fullName;
                $country = "PH"; //substr($item, strpos($item, '/')-2 , 2);
                $prItem["responsiblePerson"][] = array("fullName" => $fullName, "shortName" => $shortName, "country" => $country);
                if(!array_key_exists($shortName, $prData["assignees"])) {
                    $prData["assignees"][$shortName] = 0;
                }
                $prData["assignees"][$shortName]++;
            }
            if(count($prItem["responsiblePerson"])==0) {
                $prData["assignees"]["unassigned"]++;
            }

            $state = $row["state"];
            $prItem["state"] = $state;
            if($state === STATE_RFT) {
                $state = "RFT";
            } else if($state === STATE_FCC) {
                $state = "FCC";
            } else if($state === STATE_FRS) {
                $state = "FRS";
            } else if($state === STATE_INV) {
                $state = "INV";
            } else if($state === STATE_NEW) {
                $state = "NEW";
            } else if($state === STATE_CPR) {
                $state = "CPR";
            } else if($state === STATE_CPS) {
                $state = "CPS";
            }
            $prItem["shortState"] = $state;

            $prItem["rdInformation"] =  $row["rd_information"];
            $rdInfoLines = explode("\n", $row["rd_information"]);
            $prItem["shortRdInformation"] = $rdInfoLines[0];

            $prItem["releaseFlags"] =  array();
            foreach(json_decode($row["release_flags"]) as $item) {
                $prItem["releaseFlags"][] =  $item;
                if(!array_key_exists($item, $prData["releases"])) {
                    $prData["releases"][$item] = 0;
                }
                $prData["releases"][$item]++;
            }
            if(count($prItem["releaseFlags"])==0) {
                $prData["releases"]["others"]++;
            }

            $prItem["binaryGroups"] =  array();
            foreach(json_decode($row["binary_groups"]) as $item) {
                $prItem["binaryGroups"][] =  $item;
                if(!array_key_exists($item, $prData["binaries"])) {
                    $prData["binaries"][$item] = 0;
                }
                $prData["binaries"][$item]++;
            }
            if(count($prItem["binaryGroups"])==0) {
                $prData["releases"]["others"]++;
            }

            foreach($prItem["releaseFlags"] as $releaseFlag) {
                if(!array_key_exists($releaseFlag, $prData["summary"])){
                    $prData["summary"][$releaseFlag] = array( "overall" => array(
                        "total" => 0,
                        "rft" => 0,
                        "fcc" => 0,
                        "unassigned" => 0,
                        "rc_unknown" => 0,
                        "rc_known" => 0
                    ));
                }
                $prData["summary"][$releaseFlag]["overall"]["total"]++;
                $prData["summary"][$releaseFlag]["overall"][$prItem["status"]]++;

                foreach($prItem["binaryGroups"] as $binaryGroup) {
                    if(!array_key_exists($binaryGroup, $prData["summary"][$releaseFlag])){
                        $prData["summary"][$releaseFlag][$binaryGroup] = array( "total" => 1);
                        $prData["summary"][$releaseFlag][$binaryGroup][$prItem["status"]] = 1;
                    } else {
                        $prData["summary"][$releaseFlag][$binaryGroup]["total"]++;
                        if(!array_key_exists($prItem["status"], $prData["summary"][$releaseFlag][$binaryGroup])){
                          $prData["summary"][$releaseFlag][$binaryGroup][$prItem["status"]] = 0;
                        }
                        $prData["summary"][$releaseFlag][$binaryGroup][$prItem["status"]]++;
                    }
                }
            }

            $prData["prList"][$prItem["problemId"]] = $prItem;
        }
        // uksort($prData["summary"], function($a, $b) {
        //     return strcasecmp($a, $b);
        // });
        $query = db_query("getFetchInfo");
        while($row = db_fetch_assoc($query)){
            if($row["info_key"]=="last_fetch_date") {
                $fetchDate = $row["value"];
                $prData["fetchInfo"]["lastFetch"] = $fetchDate;
            }
        }
        return $prData;
    }
?>
