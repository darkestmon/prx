<?php
function tdWrap($value, $class="") {
    if($value=="0")
        return "<td class='zero {$class}'>{$value}</td>";
    else
        return "<td class='{$class}'>{$value}</td>";
}

function generateSummary($prData) {
?>
<div class="container summary">
    <div class="row">
        <div class="col-md-6">
            <div class="container text-center">
                <span class='badge badge-warning'>Releases</span>
            </div>
            <table id="summary-releases-table" class='table table-hover text-left table-sm'>
                <thead>
                    <tr>
                        <th></th>
                        <th class="block-gray td-total">Total</th>
                        <th class="block-red">Unassigned</th>
                        <th class="block-pink">RC Unknown</th>
                        <th class="block-yellow">RC Known</th>
                        <th class="block-orange">FCC</th>
                        <th class="block-green">RFT</th>
                    </tr>
                </thead>
                <tbody>
<?php
    foreach($prData["summary"] as $release => $releaseValues) {
        echo        "<tr class='release-row' data-release='{$release}'>";
        echo            tdWrap($release);
        echo            tdWrap($releaseValues["overall"]["total"], "td-total");
        echo            tdWrap($releaseValues["overall"]["unassigned"]);
        echo            tdWrap($releaseValues["overall"]["rc_unknown"]);
        echo            tdWrap($releaseValues["overall"]["rc_known"]);
        echo            tdWrap($releaseValues["overall"]["fcc"]);
        echo            tdWrap($releaseValues["overall"]["rft"]);
        echo        "</tr>";
    }
?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div class="container text-center">
                <span class='release-title badge badge-warning'></span>
            </div>
            <table id="summary-binaries-table" class='table table-hover text-left table-sm'>
                <thead>
                    <tr>
                        <th class="td-binaries"></th>
                        <th class="block-gray td-total">Total</th>
                        <th class="block-red">Unassigned</th>
                        <th class="block-pink">RC Unknown</th>
                        <th class="block-yellow">RC Known</th>
                        <th class="block-orange">FCC</th>
                        <th class="block-green">RFT</th>
                    </tr>
                </thead>
                <tbody class="release-binaries">
                </tbody>
            </table>
        </div>
    </div>
    <div class="disclaimer"><span>NOTE: Some PRs are applicable to multiple binaries, adding up the binary PRs may not match the release's total PR count.</span></div>
</div>
<?php
}
?>