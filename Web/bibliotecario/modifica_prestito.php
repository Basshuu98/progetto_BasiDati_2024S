<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_rent($1, $2, $3);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["id"], $_POST["d_fine"], NULL));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Prestito modificato con successo.";
    Redirect("gestione_lettori.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-book";
$title = "Modifica prestito";
$subtitle = "";

$class = "is-link";
$help = "";

$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"id",
    "value"=>$_POST["id"]
  ),
  array(
    "type"=>"date",
    "label"=>"Data di fine",
    "name"=>"d_fine",
    "value"=>$_POST["d_fine"],
    "required"=>"required",
    "icon"=>"fa-calendar",
    "help"=>""
  )
);

require("../components/form.php");

?>