<?php
/**
 * Inserts a new row into the GSR Salary calulator table. Only accessible through AJAX. Otherwise, redirects
 * back to the main calculator.
 *
 * @author Brandon Clement
 * @since 29 October 2015
 */

if (array_key_exists("gsr_session_id", $_COOKIE) && array_key_exists("method", $_POST) && array_key_exists("data", $_POST)) {
  include "/var/www/html/php-programs/globalLib.php";
  
  $conn = oracleConnect("");
  $calnet_id = "";
  
  // Gets the user's CalNet ID if logged in
  if (array_key_exists("BOALTAPPS", $_COOKIE)) {
    $q = "SELECT
            CALNET_ID
          FROM
            www_auth_session
          WHERE
            SESSION_ID = :boalt_session";
          
    $stid = oci_parse($conn, $q);
    oci_bind_by_name($stid, "boalt_session", $_COOKIE["BOALTAPPS"]);
    oci_execute($stid);
  
    $row = oci_fetch_assoc($stid);
    $calnet_id = $row["CALNET_ID"];
  }
  
  $q = "INSERT INTO law_projects.gsr_salary (
          CALNET_ID,
          SESSION_ID,
          METHOD,
          DATA
        )
        VALUES (
          :calnet_id,
          :gsr_session,
          :method,
          :data
        )";
  
  $stid = oci_parse($conn, $q);
  oci_bind_by_name($stid, ":calnet_id", $calnet_id);
  oci_bind_by_name($stid, ":gsr_session", $_COOKIE["gsr_session_id"]);
  oci_bind_by_name($stid, ":method", $_POST["method"]);
  oci_bind_by_name($stid, ":data", $_POST["data"]);
  oci_execute($stid);
}

header("Location: index.php");
die();
?>

