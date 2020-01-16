<?php // PR Xpress
    include "db_connect.php";
    include "constants.php";
    include "pr_data.php";
    include "php/filters.php";
    include "php/pr-table.php";
    include "php/summary.php";
    include "php/pr-details.php";
    include "php/about.php";
    include "php/config.php";

    $prData = getPrData();
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/filters.css">
    <link rel="stylesheet" href="css/summary.css">
    <link rel="stylesheet" href="css/pr-details.css">
    <link rel="stylesheet" href="css/config.css">
    <link rel="shortcut icon" type="image/x-icon" href="img/prx-logo-32.png" />
    <title>PR Xpress</title>
</head>
<body>
    <div id="page-title" class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <img id="title-logo" src="img/logo-line.png"><span id="title-caption"><h4><br>for Super Team</h4></span>
            </div>
            <div class="col-sm-4">
                <div id="navigation">
                    <ul class="nav justify-content-center">
                      <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#PRs-content"><div>PRs</div></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#summary-content"><div>Release Summary</div></a>
                      </li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="header-right">
                    <div><a class="header-button" id="config-button" title="Configure PR Xpress..." data-toggle="modal" data-target="#config-modal"><i class="icon fas fa-cog"></i></a></div>
                    <div><a class="header-button" id="info-button" title="About PR Xpress..." data-toggle="modal" data-target="#about-modal"><i class="icon fas fa-question-circle"></i></a></div>
<?php
    $lastFetch = new DateTime($prData["fetchInfo"]["lastFetch"]);
    echo            '<div id="refresh-status" title="PRXpress updates its server data every 5 minutes. You have to manually hit refresh after that time.&#013;If there is no update on the server, the time will remain the same."';
    echo                'class="text-right">Data as of <span>'.$lastFetch->format('Y-m-d, g:i A').'<br><time class="timeago" datetime="'.$lastFetch->format('Y-m-d H:i:s').'"></time></span></div>';
?>
                </div>
            </div>
        </div>
    </div>

    <div class='container-fluid'>
        <div class="tab-content" id="prxTabContent">
            <div class="tab-pane fade show active" id="PRs-content">
                <div style="height: 50px;">
                <?php generateFilters($prData);?>
                </div>
                <?php generatePrTable($prData);?>
            </div>
            <div class="tab-pane fade" id="summary-content">
                <?php generateSummary($prData);?>
            </div>
            <div class="tab-pane fade" id="config-content">
                hello
            </div>
        </div>
    </div>
    <?php generateDetailsModal();?>
    <?php generateAboutModal();?>
    <?php generateConfigModal();?>
    <script src="https://code.jquery.com/jquery-3.1.0.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filters.js"></script>
    <script src="js/sort.js"></script>
    <script src="js/summary.js"></script>
    <script src="js/pr-details.js"></script>
    <script src="js/config.js"></script>
    <script src="js/3rd-party/jquery.timeago.js"></script>
    <script src="js/severity-sort.js"></script>

    <script>
    $( document ).ready(function() {
        var prFilters = new PrFilters();
        var prSort = new PrSort();
        var prData = <?php echo json_encode($prData); ?>;
        var summary = new ReleaseSummary(prData);
        var prDetails = new PrDetails(prData);
        var config = new Config();
        jQuery("time.timeago").timeago();
        prSort.sortByState();

    });
    </script>

</body>
</html>
