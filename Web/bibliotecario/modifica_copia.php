<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_copy($1, $2, $3);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["isbn"], $_POST["id"], $_POST["sede"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Sede modificata con successo.";
    Redirect("gestione_libri.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Modifica Sede";
$subtitle = "";

$class = "is-link";
$help = "";


// options query per selezione sede
$qry = "SELECT _id, _citta, _indirizzo FROM unibib.get_all_libraries()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$optionsLib = array();
while ($row = pg_fetch_array($res)) {
  $optionsLib[$row["_id"]] = $row["_id"] . "-" . $row["_citta"] . " , " . $row["_indirizzo"];
}

$selectedLib = array();


$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"isbn",
    "value"=>$_POST["isbn"]
  ),
  array(
    "type"=>"hidden",
    "name"=>"id",
    "value"=>$_POST["id"]
  ),
  array(
    "type"=>"select",
    "label"=>"Sede",
    "name"=>"sede",
    "options"=>$optionsLib,
    "selected"=>$selectedLib,
    "icon"=>"fa-graduation-cap"
  ),
);

require("../components/form.php");

?>