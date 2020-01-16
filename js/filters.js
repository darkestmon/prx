var PrFilters = function(options){
    var filterOn = {};
    var filterValues = {};

    jQuery.expr[':'].casecontains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
    };

    (function (options) {
        checkFilter()
        $(".check-select").find(".selectpicker").on("changed.bs.select", function(e, clickedIndex, isSelected, previousValue){
            var id = $(this).closest(".check-select").attr("id");
            var $btn = $(this).parent().siblings(".filter-btn");
            if($(this).val().length === 0) {
                if($btn.hasClass("on")) {
                    $btn.removeClass("on");
                }
                filterOn[id] = false;
            } else {
                if(!$btn.hasClass("on")) {
                    $btn.addClass("on");
                }
                filterOn[id] = true;
                filterValues[id] = $(this).val();
            }

            applyFilters();
            var activeFilters = Object.assign({}, filterValues)
            for (var k in filterOn) {
                if(!filterOn[k]) {
                    delete activeFilters[k]
                }
            }
            updateURL(flattenFilters(activeFilters))
        });
        
        $(".filter-btn").on("click", function(e) {
            var id = $(this).parent().attr("id");
            var newValue = "";
            if($(this).hasClass("on")) {
                $(this).removeClass("on");
                filterOn[id] = false;
                newValue = "";
                applyFilters();
            } else {
                if(filterValues[id]) {
                    $(this).addClass("on");
                    filterOn[id] = true;
                    newValue = filterValues[id];
                }
            }
            if($(this).parent().hasClass("check-select")) {
                $(this).parent().find(".selectpicker").selectpicker("val", newValue);
            } else if($(this).parent().hasClass("check-text")) {
                if(newValue.length>0)
                    newValue = JSON.parse(newValue).keywords;
                $input = $(this).parent().find(".keyword-input")
                $input.val( newValue );
                if(newValue.length>0)
                    $input.trigger("input");
            }
        } );

        $(".check-text").find(".keyword-input").on("input", function(e){
            var id = $(this).closest(".check-text").attr("id");
            var $btn = $(this).siblings(".filter-btn");
            rawValue = $(this).val().replace(/\"/g,'\\"');
            if(rawValue.length === 0) {
                if($btn.hasClass("on")) {
                    $btn.removeClass("on");
                }
                filterOn[id] = false;
            } else {
                if(!$btn.hasClass("on")) {
                    $btn.addClass("on");
                }
                filterOn[id] = true;
            }
            filterValues[id] = ['{"keywords":"'+rawValue+'"}'];
            applyFilters();
        });

        $(".filters-panel").show();
    })();

    function applyFilters() {
        $("#pr-table tbody tr").show();
        $.each(filterValues, function(id, selection){
            if(filterOn[id]) {
                $.each(selection, function(id, val) {
                    var rules = JSON.parse(val);
                    if(rules.binary) {
                        rules.binary.forEach(function(binary){
                            $("#pr-table tr:visible[data-binary~='"+binary+"']").addClass("filtered-row");
                        });
                    }
                    if(rules.binary_others==="true") {
                        $("#pr-table tr:visible[data-binary='']").addClass("filtered-row");
                    }
                    if(rules.release_flag) {
                        rules.release_flag.forEach(function(release_flag){
                            $("#pr-table tr:visible[data-release-flag~='"+release_flag+"']").addClass("filtered-row");
                        });
                    }
                    if(rules.release_flag_none==="true") {
                        $("#pr-table tr:visible[data-release-flag='']").addClass("filtered-row");
                    }
                    if(rules.responsible) {
                        rules.responsible.forEach(function(assignee){
                            $("#pr-table tr:visible").find(".td-responsible:casecontains('"+assignee+"')").closest("tr:visible").addClass("filtered-row");
                        });
                    }
                    if(rules.responsible_empty==="true") {
                        $("#pr-table tr:visible").find(".td-responsible").find(".empty").closest("tr:visible").addClass("filtered-row");
                    }
                    if(rules.top) {
                        rules.top.forEach(function(topTag){
                            $("#pr-table tr:visible").find(".td-top:casecontains('"+topTag.trim()+"')").closest("tr:visible").addClass("filtered-row");
                        });
                    }
                    if(rules.keywords && rules.keywords.length>0) {
                        trContainsSelector = "";
                        rules.keywords.split(",")
                            .filter(e => e.length > 0)
                            .map(e => e.trim())
                            .forEach(keyword => {trContainsSelector += ":casecontains('"+keyword+"')"});
                        $("#pr-table tr"+trContainsSelector).closest("tr:visible").addClass("filtered-row");
                    }
                });
                $("#pr-table tbody tr:not(.filtered-row)").hide();
                $("#pr-table tbody .filtered-row").removeClass("filtered-row");
            }
        });

        $("tr:visible").each(function (index) {
            $(this).css("background-color", !!(index & 1)? "rgba(0,0,0,.05)" : "rgba(0,0,0,0)");
        });
    }

    function encodeFilterString(filter) {
        if(Object.keys(filter).length > 0) {
            return btoa(JSON.stringify(filter));
        } else {
            return "";
        }
    }

    function decodeFilterString(filterString) {
        return filterString.length > 0 ? JSON.parse(atob(filterString)) : null;
    }

    function applyURLfilter(options) {
        $(document).ready(() => {
            ['releaseFilter', 'binariesFilter', 'assigneeFilter', 'topPrFilter'].forEach((filter) => {
                $(`#${filter} .selectpicker`).selectpicker('toggle');
                $(`#${filter} .selectpicker`).selectpicker('toggle');
            });
            Object.keys(options).forEach((key) => {
                options[key].forEach((item)=> {
                    if(key == "binariesFilter") {
                        activateOption(key, mappingForBinaryAndTopPrFilter("binariesFilter", item))
                    } else if (key == "topPrFilter") {
                        activateOption(key, mappingForBinaryAndTopPrFilter("topPrFilter", item))
                    } else {
                        activateOption(key, item);
                    }
                })
            })
        });
    }

    function activateOption(filterclass, option) {
        $(`#${filterclass} ul.dropdown-menu li a`).filter(function() {return $(this).text().replace(/\(\d+\)$/,'') === `${option}`}).click();
    }

    function flattenFilters(filters) {
        filterObj = {};
            for (let filter in filters) {
                filters[filter].forEach(el => {
                    var filterpair = JSON.parse(el);

                    if (!filterObj[filter]) {
                        filterObj[filter] = []
                    }
                    if (Object.keys(filterpair)[0] === "responsible_empty" && Object.values(filterpair)[0] === "true") {
                        filterObj[filter].push(`~ UNASSIGNED ~`);
                    } else if (Object.values(filterpair)[0] === "true") {
                        filterObj[filter].push(`OTHERS`);
                    } else {
                        filterObj[filter].push(Object.values(JSON.parse(el))[0][0]);
                    }
                });
            }
        return filterObj;
    }

    function updateURL(filter) {
        const url = new URL(location.href);
        url.searchParams.set("filter", encodeFilterString(filter));
        history.replaceState("", "", url);
    }

    function checkFilter() {
        const url = new URL(location.href);
        filter = url.searchParams.get('filter')
        if (filter && filter.length > 0) {
            console.log('Applied filters')
            applyURLfilter(decodeFilterString(filter))
        }
    }
    
    function mappingForBinaryAndTopPrFilter(filterNode, option) {
        var mapping = {}
        var keytext = ''
        $(`#${filterNode} .selectpicker option`).each(function(el) {
            optionValue = JSON.parse($(this).val())
            optionText = $(this).text()
            mapping[optionText] = optionValue[Object.keys(optionValue)]
        });
        for (let key of Object.keys(mapping)) {
            if(mapping[key].includes(option)) {
                keytext = key;
            }
        }
        return keytext
    }
}