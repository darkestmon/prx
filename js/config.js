var Config = function() {
    var haveChanges = false;
    var changes = {};
    (function(){
        $('#config-modal').on('show.bs.modal', function (e) {
            changes.topFilter = {};
            changes.topFilterAdds = {};
            changes.binaries = {};
            changes.binariesAdds = {};
            $("#config-modal .modal-footer button").attr("disabled", "disabled");
        });
        $('#config-modal').on('hide.bs.modal', function (e) {
            if(haveChanges) {
                e.preventDefault();
                if(areInputsValid()) {
                    $('#config-save-modal').modal("show");
                } else {
                    $('#config-invalid-modal').modal("show");
                }
            } else {
                $("#config-modal .delete-button").removeClass("active");
                $(".top-filter-name").removeAttr("disabled");
                $(".top-filter-keywords").removeAttr("disabled");
                $("#config-modal .added-row").remove();
            }
        });
        $(".config-revert-btn").on('click', revertQuit);
        $(".config-save-btn").on('click', function (e) {
            if(areInputsValid()) {
                //console.log(JSON.stringify(changes));
                pushChanges(JSON.stringify(changes));
            } else {
                $('#config-invalid-modal').modal("show");
            }
        });
        ///////////////// top pr
        $("#config-modal #top-filter-config").on("input", "input", function (e){
            var origName = $(this).closest("tr").find(".top-filter-name").data("orig-value");
            var origKeywords = $(this).closest("tr").find(".top-filter-keywords").data("orig-value");
            var itemName = $(this).closest("tr").find(".top-filter-name").val();
            var itemValue = $(this).closest("tr").find(".top-filter-keywords").val();
            if(origName=="") {
                if(itemName+itemValue!="")
                    changes.topFilterAdds[$(this).closest("tr").index()] = [itemName, itemValue];
                else
                    delete changes.topFilterAdds[$(this).closest("tr").index()];
            } else {
                if(origName!=itemName || origKeywords!=itemValue)
                    changes.topFilter[origName] = [itemName, itemValue];
                else
                    delete changes.topFilter[origName];
            }
            markAsEditted();
        });
        $("#config-modal #top-filter-config").on('click', ".delete-button", function (e) {
            var origName = $(this).closest("tr").find(".top-filter-name").data("orig-value");
            if($(this).hasClass("active")) {
                $(this).closest("tr").find(".top-filter-name").removeAttr("disabled");
                $(this).closest("tr").find(".top-filter-keywords").removeAttr("disabled");
                $(this).removeClass("active");
                var origKeywords = $(this).closest("tr").find(".top-filter-keywords").data("orig-value");
                var itemName = $(this).closest("tr").find(".top-filter-name").val();
                var itemValue = $(this).closest("tr").find(".top-filter-keywords").val();
                if(origName=="") {
                    if(itemName+itemValue!="")
                        changes.topFilterAdds[$(this).closest("tr").index()] = [itemName, itemValue];
                } else {
                    if(origName!=itemName || origKeywords!=itemValue)
                        changes.topFilter[origName] = [itemName, itemValue];
                    else
                        delete changes.topFilter[origName];
                }
            } else {
                $(this).closest("tr").find(".top-filter-name").attr("disabled", "disabled");
                $(this).closest("tr").find(".top-filter-keywords").attr("disabled", "disabled");
                $(this).addClass("active");
                if(origName=="") {
                    delete changes.topFilterAdds[$(this).closest("tr").index()];
                } else {
                    changes.topFilter[origName] = ["", ""];
                }
            }
            markAsEditted();
        });
        $("#config-modal .top-filter-container .add-filter-btn").on('click', function (e) {
            var newRow = "";
            newRow += "<tr class='added-row'>";
            newRow += "   <td><input type='text' class='form-control top-filter-name' data-orig-value='' value=''></input></td>";
            newRow += "   <td><input type='text' class='form-control top-filter-keywords' data-orig-value='' value=''></input></td>";
            newRow += "   <td><a class='delete-button' title='Delete'><i class='icon fas fa-trash-alt'></i></a></td>";
            newRow += "</tr>";
            $("#config-modal .top-filter-container table tr:last").before(newRow);
            $("#config-modal .top-filter-container table tr.added-row:last .top-filter-name").focus();
        });

        ///////////////// binaries
        $("#config-modal #binaries-config").on("input", "input", function (e){
            onBinariesInputChanged( $(this).closest("tr") );
        });
        $("#config-modal #binaries-config").on("changed.bs.select", "select", function(e, clickedIndex, isSelected, previousValue){
            onBinariesInputChanged( $(this).closest("tr") );
        });
        $("#config-modal #binaries-config").on('click', ".delete-button", function (e) {
            var origName = $(this).closest("tr").find(".binary-name").data("orig-value");
            if($(this).hasClass("active")) {
                $(this).closest("tr").find(".binary-name").removeAttr("disabled");
                $(this).closest("tr").find(".binary-field button").removeAttr("disabled");
                $(this).closest("tr").find(".binary-keywords").removeAttr("disabled");
                $(this).removeClass("active");
                var origKeywords = $(this).closest("tr").find(".binary-keywords").data("orig-value");
                var itemName = $(this).closest("tr").find(".binary-name").val();
                var itemField = $(this).closest("tr").find(".binary-field .selectpicker").val();
                var itemValue = $(this).closest("tr").find(".binary-keywords").val();
                if(origName=="") {
                    if(itemName+itemValue!="")
                        changes.binariesAdds[$(this).closest("tr").index()] = [itemName, itemField, itemValue];
                } else {
                    if(origName!=itemName || origKeywords!=itemValue)
                        changes.binaries[origName] = [itemName, itemField, itemValue];
                    else
                        delete changes.binaries[origName];
                }
            } else {
                $(this).closest("tr").find(".binary-name").attr("disabled", "disabled");
                $(this).closest("tr").find(".binary-field button").attr("disabled", "disabled");
                $(this).closest("tr").find(".binary-keywords").attr("disabled", "disabled");
                $(this).addClass("active");
                if(origName=="") {
                    delete changes.binariesAdds[$(this).closest("tr").index()];
                } else {
                    changes.binaries[origName] = ["", "", ""];
                }
            }
            markAsEditted();
        });
        $("#config-modal .binaries-container .add-binary-btn").on('click', function (e) {
            var newRow = "";
            newRow += "<tr class='added-row'>";
            newRow += "   <td><input type='text' class='form-control binary-name' data-orig-value='' value=''></input></td>";
            newRow += "   <td><select class='selectpicker binary-field' data-width='200px' data-style='btn-light table-btn' data-orig-value=''>";
            newRow += "           <option value='subsystem' selected>Subsystem</option>";
            newRow += "           <option value='group_in_charge'>Group in Charge</option>";
            newRow += "       </select></td>";
            newRow += "   <td><input type='text' class='form-control binary-keywords' data-orig-value='' value=''></input></td>";
            newRow += "   <td><a class='delete-button' title='Delete'><i class='icon fas fa-trash-alt'></i></a></td>";
            newRow += "</tr>";
            $("#config-modal .binaries-container table tr:last").before(newRow);
            $("#config-modal .binaries-container table tr.added-row:last .binary-name").focus();
            $("#config-modal .binaries-container table tr.added-row:last .selectpicker").selectpicker();
        });
    })();

    function onBinariesInputChanged( $item ) {
            var origName = $item.find(".binary-name").data("orig-value");
            var origField = $item.find(".binary-field").data("orig-value");
            var origKeywords = $item.find(".binary-keywords").data("orig-value");
            var itemName = $item.find(".binary-name").val();
            var itemField = $item.find(".binary-field").selectpicker("val");
            var itemKeywords = $item.find(".binary-keywords").val();
            if(origName=="") {
                if(itemName+itemKeywords!="")
                    changes.binariesAdds[$item.index()] = [itemName, itemField, itemKeywords];
                else
                    delete changes.binariesAdds[$item.index()];
            } else {
                if(origName!=itemName || origField!=itemField || origKeywords!=itemKeywords)
                    changes.binaries[origName] = [itemName, itemField, itemKeywords];
                else
                    delete changes.binaries[origName];
            }
            markAsEditted();
    }

    function markAsEditted() {
        haveChanges = false;
        if(Object.keys(changes.topFilter).length + Object.keys(changes.topFilterAdds).length>0){
            haveChanges = true;
            $(".top-filter-editted").show();
        } else {
            $(".top-filter-editted").hide();
        }
        if(Object.keys(changes.binaries).length + Object.keys(changes.binariesAdds).length>0){
            haveChanges = true;
            $(".binaries-editted").show();
        } else {
            $(".binaries-editted").hide();
        }
        if( haveChanges ) {
            $("#config-modal .modal-footer button").removeAttr("disabled");
        } else {
            $("#config-modal .modal-footer button").attr("disabled", "disabled");
        }
    }

    function areInputsValid() {
        valid = true;
        var topPrNameList = [];
        var binariesList = [];
        $("#top-filter-config .top-filter-name:enabled").each( function( index, item ) {
            var itemKeywords = $(this).closest("tr").find(".top-filter-keywords").val();
            if((item.value=="" && itemKeywords!="") || item.value!="" && itemKeywords==""){
                valid = false;
            } else if(item.value!==""){
                if($.inArray(item.value ,topPrNameList) > -1) {
                    valid = false;
                }
                topPrNameList.push(item.value);
            }
        });
        $("#binaries-config .binary-name:enabled").each( function( index, item ) {
            var itemKeywords = $(this).closest("tr").find(".binary-keywords").val();
            if((item.value=="" && itemKeywords!="") || item.value!="" && itemKeywords==""){
                valid = false;
            } else if(item.value!==""){
                if($.inArray(item.value ,binariesList) > -1) {
                    valid = false;
                }
                binariesList.push(item.value);
            }
        });
        return valid;
    }

    function onPushChangesComplete(result) {
        location.reload();
    }

    function pushChanges( changes ) {
        var formdata = new URLSearchParams();
        var result;
        formdata.set("changes", changes);

        fetch(`${window.location.origin + window.location.pathname}/api/config/config_api.php`, {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'},
            body: formdata
        })
        .then(data => data.json())
        .then(result => onPushChangesComplete(result));
    }

    function revertQuit(){
        $("#config-modal input").each( function( index, item ) {
            item.value = $(item).data("orig-value");
        });
        $("#config-modal .selectpicker").each( function( index, item ) {
            $(item).selectpicker( "val",  $(item).data("orig-value") );
        });
        haveChanges = false;
        $("#config-modal .editted").hide();
        $('#config-save-modal').modal("hide");
        $('#config-invalid-modal').modal("hide");
        $('#config-modal').modal("hide");
    }
}
