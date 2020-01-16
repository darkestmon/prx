<?php
function generateConfigModal(){
?>
<div class="modal fade" id="config-modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title" style="font-family: 'Arial'">Configuration</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <br>
            <div class="top-filter-container">
                Top PR Filter<span class="top-filter-editted editted" style="display: none;"> (editted)</span>:<br>
                <table id="top-filter-config" class='table table-sm table-borderless'>
                    <tr>
                        <th>Name</th>
                        <th title="Note: separate each keyword by commas">Keywords</th>
                        <th></th>
                    </tr>
<?php
    $query = db_query("getAllTopPrTags");
    while($row = db_fetch_assoc($query)){
        echo        "<tr>";
        echo        "   <td><input type='text' class='form-control top-filter-name' data-orig-value='{$row["name"]}' value='{$row["name"]}'></input></td>";
        echo        "   <td><input type='text' class='form-control top-filter-keywords' data-orig-value='{$row["keywords"]}' value='{$row["keywords"]}'></input></td>";
        echo        "   <td><a class='delete-button' title='Delete'><i class='icon fas fa-trash-alt'></i></a></td>";
        echo        "</tr>";
    }
?>
                <tr>
                    <td colspan=3>
                        <div class="table-under-buttons">
                            <button class="btn btn-default float-sm-left add-filter-btn"><i class="icon fas fa-plus-square"></i>   Add Filter</button>
                        </div>
                    </td>
                </tr>
                </table>
            </div>
            <hr>
            <div class="binaries-container">
                Binaries<span class="binaries-editted editted" style="display: none;"> (editted)</span>:<br>
                <span class="binaries-editted editted">
                <i>* All changes will reflect on the next PR tool data fetch, refresh after approx. 5 minutes.</i><br>
                </span>
                <table id="binaries-config" class='table table-sm table-borderless'>
                    <tr>
                        <th>Name</th>
                        <th>Field</th>
                        <th title="Note: separate each keyword by commas">Keywords</th>
                        <th></th>
                    </tr>
<?php
    $query = db_query("getAllBinaries");
    while($row = db_fetch_assoc($query)){
        echo        "<tr>";
        echo        "   <td><input type='text' class='form-control binary-name' data-orig-value='{$row["name"]}' value='{$row["name"]}'></input></td>";
        echo        "   <td><select class='selectpicker binary-field' data-width='200px' data-style='btn-light table-btn' data-orig-value='{$row["field"]}'>";
        echo        "           <option value='subsystem'";
        echo        $row["field"]=="subsystem"? " selected": "";
        echo        "           >Subsystem</option>";
        echo        "           <option value='group_in_charge'";
        echo        $row["field"]=="group_in_charge"? " selected": "";
        echo        "           >Group in Charge</option>";
        echo        "       </select></td>";
        echo        "   <td><input type='text' class='form-control binary-keywords' data-orig-value='{$row["val"]}' value='{$row["val"]}'></input></td>";
        echo        "   <td><a class='delete-button' title='Delete'><i class='icon fas fa-trash-alt'></i></a></td>";
        echo        "</tr>";
    }
?>
                <tr>
                    <td colspan=3>
                        <div class="table-under-buttons">
                            <button class="btn btn-default float-sm-left add-binary-btn"><i class="icon fas fa-plus-square"></i>   Add Filter</button>
                        </div>
                    </td>
                </tr>
                </table>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="config-revert-btn btn btn-danger">Revert Changes</button>
                <button type="button" class="config-save-btn btn btn-primary">Save & Reload</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="config-save-modal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title" style="font-family: 'Arial'">Save Changes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <br>
                You made changes with the configuration. Do you want to save these and reload the site?
            <br>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="config-revert-btn btn btn-danger pull-left">Revert Changes</button>
        <button type="button" class="config-cancel-btn btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="config-save-btn btn btn-primary">Save & Reload</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="config-invalid-modal" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title" style="font-family: 'Arial'">Invalid input</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <br>
                You have entered an invalid content in one of the fields. Make sure the fields aren't blank or the names aren't duplicate. Either fix the problem and save it or revert it to the original values.
            <br>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="config-revert-btn btn btn-danger pull-left">Revert Changes</button>
        <button type="button" class="config-cancel-btn btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
