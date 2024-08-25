<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.add_library($1, $2);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["citta"], $_POST["indirizzo"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Sede creata con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-graduation-cap";
$title = "Nuova sede";
$subtitle = "";

$class = "is-link";
$help = "";

$inputs = array(
  array(
    "type"=>"text",
    "label"=>"Citta",
    "name"=>"citta",
    "value"=>$_POST["citta"],
    "placeholder"=>"Città",
    "required"=>"required",
    "icon"=>"fa-barcode",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Indirizzo",
    "name"=>"indirizzo",
    "value"=>$_POST["indirizzo"],
    "placeholder"=>"Es. Via, Piazza, ...",
    "required"=>"required",
    "icon"=>"fa-align-center",
    "help"=>""
  )
);

require("../components/form.php");

?>