var PrDetails = function(prData) {
    function getPrAnchor(prId) {
        return "<!--a href='xxxxx="+prId+"' target='_blank'-->" + prId + "<!--/a-->";
    }
    (function(){
        $("#pr-table td").on("click", function(e) {
            if(e.target.nodeName == 'A') return;
            var id = $(this).parent().find(".td-problem").text();
            var prid = prData["prList"][id].problemId;
            var attachedPrs=[];
            var fullNames=[];

            $.each(prData["prList"][id].attached, (index, attached) => {
                attachedPrs.push(getPrAnchor(attached));
            });

            $.each(prData["prList"][id].responsiblePerson, (index, person) => {
                fullNames.push(person.fullName);
            });

            if($(this).parent().hasClass("table-warning")) {
                $('#details-modal').find('#title').text(prData["prList"][id].title);
                $('#details-modal').find('#problem-id').html(getPrAnchor(prData["prList"][id].problemId));
                $('#details-modal').find('#fault-id').text(prData["prList"][id].faultId);
                $('#details-modal').find('#state').text(prData["prList"][id].state);
                $('#details-modal').find('#severity').text(prData["prList"][id].severity);
                $('#details-modal').find('#attached').html(attachedPrs.join(', '));
                $('#details-modal').find('#release').text(prData["prList"][id].release.join(', '));
                $('#details-modal').find('#top-importance').text(prData["prList"][id].topImportance.join(', '));
                $('#details-modal').find('#subsystem').text(prData["prList"][id].subsystem.join(', '));
                $('#details-modal').find('#responsible-person').text(fullNames.join("<br>"));
                $('#details-modal').find('#group-in-charge').text(prData["prList"][id].groupInCharge);
                $('#details-modal').find('#optional').html(prData["prList"][id].optional.replace(/(?:\r\n|\r|\n)/g, '<br>'));
                $('#details-modal').find('#additional').html(prData["prList"][id].additional.replace(/(?:\r\n|\r|\n)/g, '<br>'));
                $('#details-modal').find('#rd-info').html(prData["prList"][id].rdInformation.replace(/(?:\r\n|\r|\n)/g, '<br>'));

                renderCustomNotes(prid);

                $('#details-modal').on('hide.bs.modal', () => {$('#notes').off();});
                $('#details-modal').modal();
                editCustomNotesHandler(prid);

            } else {
                $("#pr-table tr").removeClass("table-warning");
                $(this).parent().addClass("table-warning")
            }
        } );
    })();

    function renderCustomNotes(prid) {
        fetch(`${window.location.origin + window.location.pathname}/api/notes/?prid=${prid}`)
            .then(data => data.json())
            .then(data => $('#details-modal').find('#notes').html(`${data["notes"]}`));
    }

    function postCustomNotes(prid, notes, next=function(){}) {
        var formdata = new URLSearchParams();
        formdata.set("prid", prid);
        formdata.set("notes", notes);

        fetch(`${window.location.origin + window.location.pathname}/api/notes/`, {
            method: "POST",
            headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'},
            body: formdata
        })
        .then(data => data.json())
        .then(() => next(prid));
    }

    function editCustomNotesHandler(prid) {
        function doubleClickHandler() {
            $('#notes').on('dblclick', (event) => {
                var content = $('#notes').text();
                $('#notes').html(`<textarea id="notes-text"class="form-control" rows="3">${content}</textarea>`);
                $('#notes-text').focus();
                $('#notes').off('dblclick');
            })
        }
        doubleClickHandler();
        $('#notes').on('focusout', () => {
            var content = $('#notes-text').val();
            postCustomNotes(prid, content, renderCustomNotes);
            doubleClickHandler();
        })
    }
}
