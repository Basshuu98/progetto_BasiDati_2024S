<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {

  $qry = "CALL unibib.delete_writer($1);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["id"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Autore Eliminato con successo.";
    Redirect("home.php");
  }

}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-is-danger";
$title = "Elimina Autore";
$subtitle = "";

$class = "is-link";
$help = "";

$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"id",
    "value"=>$_POST["id"]
  )
);

require("../components/form.php");

?>