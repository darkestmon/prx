<?php
  include "pg_sim.php";
  include "db_query_strings.php";
  $conn = my_pg_connect("dbname='prx' user='root' host='127.0.0.1' password=''");
  prepareQueries($conn);

  function db_query($queryName /* , $args... */){
    global $conn;

    $varArgs = array_slice(func_get_args(), 1);
    $params = array();

    for($i=0; $i<sizeOf($varArgs); $i++) {
        $params[] = & $varArgs[$i];
    }

    return my_pg_execute($conn, $queryName, $params);
  }

  function db_fetch($queryName /* , $args... */){
      //db_query()
  }

  function db_fetch_assoc($result) {
      return my_pg_fetch_assoc($result);
  }
?>
