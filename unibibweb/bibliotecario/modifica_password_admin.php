<?php

require_once("../scripts/utils.php");


if (isset($_POST["submit"])) {
  $qry = "CALL unibib.edit_password_as_admin($1, $2);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["id"], $_POST["password"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Password aggiornata con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-lock";
$title = "Modifica password";
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
    "type"=>"password",
    "label"=>"Nuova password",
    "name"=>"password",
    "value"=>"",
    "placeholder"=>"Nuova password",
    "required"=>"required",
    "readonly"=>"",
    "icon"=>"fa-lock",
    "help"=>""
  )
);

require("../components/form.php");

?>