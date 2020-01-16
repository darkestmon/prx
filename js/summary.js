var ReleaseSummary = function(prData){
    (function(){
        $(".container.summary").find(".release-row").on("click", function(e) {
            var id = $(this).find("td:first").text();
            updateReleaseBinariesTable(id);
        } );
        updateReleaseBinariesTable($(".container.summary").find(".release-row").first().find("td:first").text());
    })();
    function wrapTd(val, addedClass='') {
        if(val)
            return "<td class='"+addedClass+"'>"+val+"</td>";
        else
            return "<td class='zero "+addedClass+"'>0</td>";
    }
    function updateReleaseBinariesTable(id) {
        var content = "";
        $.each(prData["summary"][id], function(binary, binaryStatus){
            if(binary!="overall") {
                content += "<tr>";
                content +=      wrapTd(binary);
                content +=      wrapTd(binaryStatus["total"], "td-total");
                content +=      wrapTd(binaryStatus["unassigned"]);
                content +=      wrapTd(binaryStatus["rc_unknown"]);
                content +=      wrapTd(binaryStatus["rc_known"]);
                content +=      wrapTd(binaryStatus["fcc"]);
                content +=      wrapTd(binaryStatus["rft"]);
                content += "</tr>";
            }
        });
        $(".container.summary").find(".release-row").removeClass("table-warning");
        $(".container.summary").find(".release-row[data-release='"+id+"']").addClass("table-warning");
        $(".container.summary").find(".release-title").html(id);
        $(".container.summary").find(".release-binaries").html(content);
    }
}
