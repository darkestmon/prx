<?php
function generatePrTable($prData) {
?>
    <div id="table-scroller">
        <table id='pr-table' class='table table-hover text-left table-sm table-striped'>
            <thead>
                <tr>
                    <th></th>
                    <!--th>Fault ID</th -->
                    <th>Problem ID</th>
                    <th>Age</th>
                    <th>Attached</th>
                    <th>Title</th>
                    <th>Release</th>
                    <th>Top Importance</th>
                    <th title="Severity" id="severity-column" data-severity-sorted=false>Sev <i class="fas fa-sort"></i></th>
                    <th>Group in Charge</th>
                    <th>Responsible Person</th>
                    <th>Subsystem</th>
                    <th>State</th>
                    <th>R&amp;D Information</th>
                    <!--th>Optional</th-->
                    <!--th>Additional</th-->
                </tr>
            </thead>
            <tbody>
<?php
    $trReleaseFlag = "";
    $trBinary = "";
    foreach($prData["prList"] as $prItem){
        if( $prItem["status"] == "rft") {
            $statusClass = "block-green";
        } else if( $prItem["status"] == "fcc") {
            $statusClass = "block-orange";
        } else if( $prItem["status"] == "rc_known") {
            $statusClass = "block-yellow";
        } else {
            $statusClass = "block-red";
        }

        $problemId = $prItem["problemId"];
        $title = $prItem["title"];

        $attached = "";
        foreach( $prItem["attached"] as $item) {
            $attached .= "<span class='badge badge-secondary'>{$item}</span>";
        }

        $release = "";
        foreach( $prItem["release"] as $item) {
            $release .= "<span class='badge badge-primary'>{$item}</span>";
        }

        $topImportance = "";
        $topItems = array();
        foreach( $prItem["topImportance"] as $item) {
            $topImportance .= "<span class='badge badge-warning'>{$item}</span>";
            $topItems[] = $item;
        }
        if($topImportance==="") {
            $topImportance = "<div class='empty'></div>";
        } else {
            $tooltip = join(",\n", $topItems);
            $topImportance = "<div title='{$tooltip}'>{$topImportance}</div>";
        }

        $severity = $prItem["severity"];
        if($severity === SEVERITY_A) {
            $severity = "<span class='severity badge badge-pill badge-danger' title='{$severity}'>A</span>";
        } else if($severity === SEVERITY_B) {
            $severity = "<span class='severity badge badge-pill badge-warning' title='{$severity}'>B</span>";
        } else {
            $severity = "<span class='severity badge badge-pill badge-primary' title='{$severity}'>C</span>";
        }

        $groupInCharge = "<span class='badge badge-secondary'>{$prItem["groupInCharge"]}</span>";

        $responsiblePerson = "";
        foreach($prItem["responsiblePerson"] as $item) {
            $responsiblePerson .= "<div title='".$item["fullName"]."'>";
            $responsiblePerson .= $item["shortName"];
            $responsiblePerson .= " <span class='badge badge-light'>".$item["country"]."</span>";
            $responsiblePerson .= "</div>";
        }
        if($responsiblePerson==="") {
            $responsiblePerson = "<div class='empty'></div>";
        }

        $subsystem = "";
        foreach($prItem["subsystem"] as $item) {
            $subsystem .= "<span class='badge badge-info'>{$item}</span>";
        }

        $state = "<span class='text-monospace' title='".$prItem["state"]."'><b>".$prItem["shortState"]."</b></span>";

        $rdInformationShort = $prItem["shortRdInformation"];
        $rdInformation = $prItem["rdInformation"];

        $optional = $prItem["optional"];
        $additional = $prItem["additional"];

        $trReleaseFlag = join(" ", $prItem["releaseFlags"]);
        $trBinary = join(" ", $prItem["binaryGroups"]);

        $age = $prItem['age'];

        echo "<tr class='' data-release-flag='{$trReleaseFlag}' data-binary='{$trBinary}'>";
            echo "<td class='td-status'><div class='{$statusClass}'></div></td>";
            //echo "<td class=''>{$faultId}</td>";
            echo "<td class='td-problem text-nowrap'><!--a href='https://xxxxxxxxxxxxxxxxxx?id={$problemId}' target='_blank'-->{$problemId}<!--/a--></td>";
            echo "<td class='td-age'>{$age}</td>";
            echo "<td class='td-attached' style='max-width: 100px;'>{$attached}</td>";
            echo "<td class='td-title text-truncate' title='{$title}'>{$title}</td>";
            echo "<td class='td-release' style='max-width: 70px;'>{$release}</td>";
            echo "<td class='td-top' style='max-width: 140px;>{$topImportance}</td>";
            echo "<td class='td-severity' style='max-width: 20px;'>{$severity}</td>";
            echo "<td class='td-group'>{$groupInCharge}</td>";
            echo "<td class='td-responsible text-nowrap'>{$responsiblePerson}</td>";
            echo "<td class='td-subsystem' style='max-width: 100px;'>{$subsystem}</td>";
            echo "<td class='td-state'>{$state}</td>";
            echo "<td class='td-info text-truncate' title='{$rdInformation}' style='width:250px;'>{$rdInformationShort}<span style='display:none'>{$rdInformation}</span></td>";
            //echo "<td class='text-truncate' title='{$optional}'>{$optional}</td>";
            //echo "<td class='text-truncate' title='{$additional}'>{$additional}</td>";
        echo "</tr>";
    }
?>
            </tbody>
        </table>
    </div>
<?php
}
?>
