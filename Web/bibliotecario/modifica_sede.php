<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_library($1, $2, $3);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["id"], $_POST["citta"], $_POST["indirizzo"] ));
  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Sede modificata con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Modifica sede";
$subtitle = "";

$class = "is-link";
$help = "";
  
$selected = array();


$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"id",
    "value"=>$_POST["id"]
  ),
  array(
    "type"=>"text",
    "label"=>"Citta",
    "name"=>"citta",
    "value"=>$_POST["citta"],
    "placeholder"=>"Città",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Indirizzo",
    "name"=>"indirizzo",
    "value"=>$_POST["indirizzo"],
    "placeholder"=>"Indirizzo",
    "icon"=>"fa-user-tag",
    "help"=>""
  )
);

require("../components/form.php");

?>