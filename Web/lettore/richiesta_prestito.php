<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.add_rent($1, $2, $3, $4, $5);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["isbn"], $_POST["copia"], $_SESSION["cod_fisc"], $_POST["data_inizio"], $_POST["data_fine"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Prestito avvenuto con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@lettore.it";
  $fa_icon = "fa-book";
  $title = "Prestito Libro";
  $subtitle = "effettua il prestito di un libro";

$class = "is-link";

$inputs = array(
  array(
    "type"=>"text",
    "label"=>"Copia",
    "name"=>"copia",
    "value"=>$_POST["copia"],
    "placeholder"=>"",
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-book",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"ISBN",
    "name"=>"isbn",
    "value"=>$_POST["isbn"],
    "placeholder"=>"",
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-book",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Data inizio",
    "name"=>"data_inizio",
    "value"=>$_POST["data_inizio"],
    "placeholder"=>"",
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-calendar-days",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Data fine",
    "name"=>"data_fine",
    "value"=>$_POST["data_fine"],
    "placeholder"=>"",
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-calendar-days",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Codice fiscale",
    "name"=>"cod_fiscale",
    "value"=>$_SESSION["cod_fisc"],
    "placeholder"=>"",
    "required"=>"required",
    "readonly"=>"readonly",
    "icon"=>"fa-user",
    "help"=>""
  )
);

require("../components/form.php");

?>