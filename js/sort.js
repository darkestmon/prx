var PrSort = function (options) {
    this.sortByState = function () {
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
}