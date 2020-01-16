<?php
function generateAboutModal(){
?>
<div class="modal fade" id="about-modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title" style="font-family: 'Arial'">PR Xpress</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <br>
            <h5 class='text-center'>PR Xpress is a tool designed to easily keep track and manage PRs under XX Team.</h5>
            <br>
            <table class="table table-sm">
                <tr>
                    <th>Version</th>
                    <th>Decription</th>
                    <th>Date</th>
                <tr>
<?php
    $query = db_query("getChangeLog");
    while($row = db_fetch_assoc($query)){
        $desc = implode("<br>", explode("\\n", htmlspecialchars($row["description"])));
        echo    "<tr>";
        echo        "<td>{$row["version"]}</td>";
        echo        "<td>{$desc}</td>";
        echo        "<td>{$row["date"]}</td>";
        echo    "</tr>";
    }
?>
            </table>
            <br>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
