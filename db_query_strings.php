<?php
    function prepareQueries($conn){
        $prTable = "prx";
        $fetchInfoTable = "prx_fetch_info";
        $changeLogTable = "prx_changelog";
        $releasesTable = "prx_releases";
        $binariesTable = "prx_binaries";
        $topPrTable = "prx_top_pr";
        my_pg_prepare($conn, "getAllPrItems", "SELECT fault_analysis_id AS fault_id,
                                                id AS problem_id,
                                                attached_prs AS attached,
                                                title,
                                                rel AS rel,
                                                top_importance AS top_importance,
                                                severity,
                                                group_in_charge,
                                                responsible_person AS responsible_person,
                                                subsystem AS subsystem,
                                                state,
                                                rd_information,
                                                optional,
                                                additional,
                                                release_flags AS release_flags,
                                                binary_groups AS binary_groups,
                                                reported_date
                                          FROM {$prTable}
                                          WHERE is_duplicate=false");
        my_pg_prepare($conn, "getFetchInfo", "SELECT *
                                          FROM {$fetchInfoTable}");
        my_pg_prepare($conn, "getChangeLog", "SELECT *
                                          FROM {$changeLogTable}
                                          ORDER BY version DESC");
        my_pg_prepare($conn, "getAllReleaseNames", "SELECT name
                                          FROM {$releasesTable}");
        my_pg_prepare($conn, "getAllReleases", "SELECT *
                                          FROM {$releasesTable}");
        my_pg_prepare($conn, "getAllBinaries", "SELECT *
                                          FROM {$binariesTable}");
        my_pg_prepare($conn, "getAllTopPrTags", "SELECT name, keywords
                                          FROM {$topPrTable}
                                          ORDER BY name ASC");
        my_pg_prepare($conn, "prExists", "SELECT 1 FROM {$prTable} WHERE problem_report_id = $1");
        my_pg_prepare($conn, "insertPr", "INSERT INTO {$prTable}(fault_analysis_id, problem_report_id, attached_prs,
            title, reported_date, release, top_importance, severity, group_in_charge, responsible_person,
            subsystem, state, rd_information, optional, additional, reason_for_transfer) VALUES (
            $1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)");
        my_pg_prepare($conn, "updatePr", "UPDATE {$prTable} SET fault_analysis_id = $1, problem_report_id = $2, attached_prs = $3,
            title = $4, reported_date = $5, release = $6, top_importance = $7, severity = $8, group_in_charge = $9,
            responsible_person = $10, subsystem = $11, state = $12, rd_information = $13, optional = $14,
            additional = $15, reason_for_transfer = $16
            WHERE problem_report_id = $2");
        my_pg_prepare($conn, "getNotesById", "SELECT * FROM prx_notes WHERE prid = $1;");
        my_pg_prepare($conn, "deleteNotesById", "DELETE FROM prx_notes WHERE prid = $1;");
        my_pg_prepare($conn, "addNotesById", "INSERT INTO prx_notes (prid, notes) VALUES ($1, $2);");
        my_pg_prepare($conn, "doTopPrUpdates", "UPDATE {$topPrTable} SET name=$2, keywords = $3 WHERE name=$1;");
        my_pg_prepare($conn, "doTopPrDeletes", "DELETE FROM {$topPrTable} WHERE name=$1;");
        my_pg_prepare($conn, "doTopPrInserts", "INSERT INTO {$topPrTable} (name, keywords) VALUES ($1, $2);");
        my_pg_prepare($conn, "doBinaryUpdates", "UPDATE {$binariesTable} SET name=$2, field = $3, val = $4 WHERE name=$1;");
        my_pg_prepare($conn, "doBinaryDeletes", "DELETE FROM {$binariesTable} WHERE name=$1;");
        my_pg_prepare($conn, "doBinaryInserts", "INSERT INTO {$binariesTable} (name, field, val) VALUES ($1, $2, $3);");
        my_pg_prepare($conn, "doChangeLogDeletes", "DELETE FROM {$changeLogTable} WHERE date=$1;");
        my_pg_prepare($conn, "doChangeLogInserts", "INSERT INTO {$changeLogTable} (date, description, version) VALUES ($1, $2, $3);");
        my_pg_prepare($conn, "doReleasesDeletes", "DELETE FROM {$releasesTable} WHERE name=$1;");
        my_pg_prepare($conn, "doReleasesInserts", "INSERT INTO {$releasesTable} (name, view_id) VALUES ($1, $2);");
    }
?>
