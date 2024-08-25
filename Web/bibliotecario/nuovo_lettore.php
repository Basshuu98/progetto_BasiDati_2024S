<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $email = $_POST["nome"] . "." . $_POST["cognome"] . "@lettore.it";
  $qry = "CALL unibib.add_reader($1, $2, $3, $4, $5, $6);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["codice_fiscale"], $email, $_POST["password"], $_POST["nome"], $_POST["cognome"], $_POST["categoria"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Lettore creato con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Nuovo lettore";
$subtitle = "";

$class = "is-link";
$help = "Email verrà generata automaticamente.";

// options query
// $qry = "SELECT __codice, __nome FROM unimia.get_corsi_di_laurea()";
// $res = pg_prepare($con, "", $qry);
// $res = pg_execute($con, "", array());

$options = array(
   'Base' => 'Base',
   'Premium' => 'Premium'
);
 
$selected = array();

$inputs = array(
  array(
    "type"=>"text",
    "label"=>"Codice_fiscale",
    "name"=>"codice_fiscale",
    "value"=>$_POST["codice_fiscale"],
    "placeholder"=>"Cod.Fiscale",
    "required"=>"required",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Nome",
    "name"=>"nome",
    "value"=>$_POST["nome"],
    "placeholder"=>"Nome",
    "required"=>"required",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"text",
    "label"=>"Cognome",
    "name"=>"cognome",
    "value"=>$_POST["cognome"],
    "placeholder"=>"Cognome",
    "required"=>"required",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"password",
    "label"=>"Password",
    "name"=>"password",
    "value"=>$_POST["password"],
    "placeholder"=>"Password",
    "required"=>"required",
    "icon"=>"fa-lock",
    "help"=>""
  ),
  array(
    "type"=>"select",
    "label"=>"Categoria",
    "name"=>"categoria",
    "options"=>$options,
    "selected"=>$selected,
    "icon"=>"fa-graduation-cap"
  )
);

require("../components/form.php");

?>