<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_book($1, $2, $3, $4);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["isbn"], $_POST["titolo"], $_POST["trama"], ToPostgresArray($_POST["autori"])));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Libro modificato con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Modifica Libro";
$subtitle = "";

$class = "is-link";
$help = "";

// options query
$qry = "SELECT _id, _nome, _cognome FROM unibib.get_all_writers()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$optionsAuth = array();
while ($row = pg_fetch_array($res)) {
  $optionsAuth[$row["_id"]] = $row["_id"] . "-" . $row["_nome"] . " " . $row["_cognome"];
}

$selectedAuth = array();


$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"isbn",
    "value"=>$_POST["isbn"]
  ),
  array(
    "type"=>"text",
    "label"=>"Titolo",
    "name"=>"titolo",
    "value"=>$_POST["titolo"],
    "placeholder"=>"Titolo",
    "required"=>"required",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Trama",
    "name"=>"trama",
    "value"=>$_POST["trama"],
    "placeholder"=>"Trama...",
    "required"=>"required",
    "icon"=>"fa-align-justify",
    "help"=>""
  ),
  array(
    "type"=>"select",
    "multiple"=>"multiple",
    "ismultiple"=>"is-multiple",
    "label"=>"Autori",
    "name"=>"autori[]",
    "options"=>$optionsAuth,
    "selected"=>$selectedAuth,
    "icon"=>"fa-user-tie"
  )
);

require("../components/form.php");

?>