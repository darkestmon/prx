<?php
function generateDetailsModal() {
?>
<div class="modal fade" id="details-modal" tabindex="-1">
  <div class="modal-dialog modal-lg  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title" style="font-family: 'Arial'"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <br>
      <div class="modal-body">
        <div class="container-fluid">
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Problem ID:</div>
                <div class='col-sm-4' id='problem-id'></div>
                <div class='col-sm-2 text-right field-name'>Fault ID:</div>
                <div class='col-sm-4' id='fault-id'></div>
            </div>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>State:</div>
                <div class='col-sm-4' id='state'></div>
                <div class='col-sm-2 text-right field-name'>Severity:</div>
                <div class='col-sm-4' id='severity'></div>
            </div>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Attached:</div>
                <div class='col-sm-4' id='attached'></div>
                <div class='col-sm-2 text-right field-name'>Release:</div>
                <div class='col-sm-4' id='release'></div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Top Importance:</div>
                <div class='col-sm-4' id='top-importance'></div>
                <div class='col-sm-2 text-right field-name'>Subsystem:</div>
                <div class='col-sm-4' id='subsystem'></div>
            </div>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Responsible Person:</div>
                <div class='col-sm-4' id='responsible-person'></div>
                <div class='col-sm-2 text-right field-name'>Group in Charge:</div>
                <div class='col-sm-4' id='group-in-charge'></div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Optional:</div>
                <div class='col-sm-10' id='optional'></div>
            </div>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Additional:</div>
                <div class='col-sm-10' id='additional'></div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>R&D Information:</div>
                <div class='col-sm-10' id='rd-info'></div>
            </div>
            <hr>
            <div class='row'>
                <div class='col-sm-2 text-right field-name'>Custom Notes:</div>
                <div class='col-sm-10' id='notes'></div>
            </div>
            <br>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
