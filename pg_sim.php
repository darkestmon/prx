<?php
function my_pg_connect($params) {
  $details = array("user"=>"root", "password"=>"", "host"=>"localhost");
  $paramsEach = explode(" ", $params);
  foreach($paramsEach as $p) {
    $keyPair = explode("=", $p);
    $keyPair[1] = substr(trim($keyPair[1]), 1, -1);
    $details[$keyPair[0]] = $keyPair[1];
  }
  // print_r($details);
  // return mysqli_connect($details["host"], $details["user"], $details["password"], $details["dbname"]);

  $conn = new PDO("mysql:host={$details['host']};dbname={$details['dbname']}", $details["user"], $details["password"]);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $conn;
}

$queries = array();
function my_pg_prepare($conn, $name, $query) {
  global $queries;
  $queryMod = str_replace("$", ":VAR", $query);
  $varCount = substr_count($queryMod, ":VAR");
  $varTypes = str_repeat("s", $varCount);
  // echo $name."===========".$varTypes."---".$queryMod."<br><br>";
  $queries[$name] = array($varTypes, $queryMod);
}

function my_pg_execute($conn, $queryName, $args) {
  // echo $queryName;
  global $queries;
  $queryData = $queries[$queryName];
  // print_r($args);
  $statement = $conn->prepare($queryData[1]);
  // echo  $conn->error;
  // echo $queryData[1];
  // exit();
  for($i=0; $i<sizeOf($args); $i++) {
    $succ = $statement->bindParam(':VAR'.($i+1), $args[$i]);
    // echo $succ;
    // $params[] = & $args[$i];
    // print_r($args[$i]);
  }

  //print_r($query);
  // print_r($params);
  // if(sizeOf($params)>0)
  //   call_user_func_array(array($statement, "bind_param"), $params);

  $isQuerySuccess = $statement->execute()>0;
  return $statement;
}

function my_pg_fetch_assoc($result){
  return $result->fetch();
}
?>
