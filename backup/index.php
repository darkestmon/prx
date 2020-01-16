<html>
    <head>
        <title>PrX Backup</title>
    </head>
    <body align="center">
        <h3>Import/Export PrX DB configuration</h3>
        <textarea rows="40" cols="120" id="textarea"></textarea>
        <br>
        <input type="button" onClick="onExportClick()" value="Export and Copy"
title="This will generate a long string of data that you may copy
and paste to the other PrX's backup tool">
        <input type="button" onClick="onImportClick()" value="Import">
    </body>
    <script
      src="https://code.jquery.com/jquery-3.4.1.js"
      integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
      crossorigin="anonymous">
    </script>
    <script>
        function onExportClick() {
            $("#textarea").empty();
            $.getJSON( "./export.php", ( data ) => {
                // console.log(data);
                $("#textarea").val(JSON.stringify(data));
                $("#textarea").select();
                document.execCommand("copy");
            })
            .fail((jqXHR, textStatus, errorThrown) => {
                $("#textarea").val("Something's wrong!");
            })
        }
        function onImportClick() {
            var sure = confirm("This will overwrite the whole PrX database. Are you sure?");
            if (sure == true) {
                console.log("importing");
                importData = $("#textarea").val();
                // console.log(importData);
                $.post( "./import.php", {backup_data: importData} ,( data ) => {
                    console.log("finish");
                    $("#textarea").empty();
                    $("#textarea").val(data);
                })
            }
        }
    </script>
</html>
