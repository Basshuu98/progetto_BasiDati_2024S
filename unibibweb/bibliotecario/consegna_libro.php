<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_rent($1, $2, $3);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["id"], $_POST["d_fine"], $_POST["d_consegna"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Prestito consegnato con successo.";
    Redirect("home.php");
  }
}


$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-book";
$title = "Consegna prestito";
$subtitle = "";

$class = "is-success";
$help = "";

$date = date('Y-m-d');

$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"id",
    "value"=>$_POST["id"]
  ),
  array(
    "type"=>"hidden",
    "name"=>"d_fine",
    "value"=>$_POST["d_fine"]
  ),
  array(
    "type"=>"date",
    "label"=>"Data consegna",
    "name"=>"d_consegna",
    "value"=>$date,
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-calendar",
    "help"=>""
  )
);

require("../components/form.php");

?>