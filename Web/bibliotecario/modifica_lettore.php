<?php

require_once("../scripts/utils.php");



if (isset($_POST["submit"])) {
  if ($_POST["categoria"] == 0){
    $categoria = "Base";
  } else {
    $categoria = "Premium";
  }
  if ($_POST["ritardi"] == ""){
    $_POST["ritardi"] = 0;
  }
  $qry = "CALL unibib.edit_reader($1, $2, $3, $4, $5, $6);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["codice_fiscale"], $_POST["email"], $_POST["nome"], $_POST["cognome"], $categoria, $_POST["ritardi"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Studente modificato con successo.";
    Redirect("gestione_lettori.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user";
$title = "Modifica lettore";
$subtitle = "";

$class = "is-link";
$help = "";


$options = array( 'Base', 'Premium');
  
$selected = array();


$inputs = array(
  array(
    "type"=>"hidden",
    "name"=>"codice_fiscale",
    "value"=>$_POST["codice_fiscale"]
  ),
  array(
    "type"=>"text",
    "label"=>"Email",
    "name"=>"email",
    "value"=>$_POST["email"],
    "placeholder"=>"Email",
    "icon"=>"fa-envelope",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Nome",
    "name"=>"nome",
    "value"=>$_POST["nome"],
    "placeholder"=>"Nome",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Cognome",
    "name"=>"cognome",
    "value"=>$_POST["cognome"],
    "placeholder"=>"Cognome",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"select",
    "label"=>"Categoria",
    "name"=>"categoria",
    "options"=>$options,
    "selected"=>$selected,
    "icon"=>"fa-graduation-cap"
  ),
  array(
    "type"=>"text",
    "label"=>"Ritardi",
    "name"=>"ritardi",
    "value"=>$_POST["ritardi"],
    "placeholder"=>"0",
    "icon"=>"fa-hashtag",
    "help"=>""
  )
);

require("../components/form.php");

?>