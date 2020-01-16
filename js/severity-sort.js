function sortByState() {
    var table = $("#pr-table");

    var greenRows = table.find('.block-green').parent().parent().get();
    var orangeRows = table.find('.block-orange').parent().parent().get();
    var redRows = table.find('.block-red').parent().parent().get();
    var yellowRows = table.find('.block-yellow').parent().parent().get();

    $.each(greenRows, function (index, row) {
        table.children("tbody").append(row);
    });
    $.each(orangeRows, function (index, row) {
        table.children("tbody").append(row);
    });
    $.each(redRows, function (index, row) {
        table.children("tbody").append(row);
    });
    $.each(yellowRows, function (index, row) {
        table.children("tbody").append(row);
    });
}

function sortUsingNestedText(parent, childSelector, keySelector) {
    var items = parent.children(childSelector).sort(function (a, b) {
        var vA = $(keySelector, a).text();
        var vB = $(keySelector, b).text();
        return (vA < vB) ? -1 : (vA > vB) ? 1 : 0;
    });
    parent.append(items);
}

var sortedBySeverity = false;
$("#severity-column").css("cursor", "pointer");

$('#severity-column').click(() => {
    var severitySorted = $('#severity-column').data("severity-sorted");
    if (severitySorted) {
        sortByState()
        $('#severity-column').data("severity-sorted", false);
    } else {
        var table = $('#pr-table');
        sortUsingNestedText(table.find('tbody'), 'tr', 'span.severity');
        $('#severity-column').data("severity-sorted", true);
    }
})