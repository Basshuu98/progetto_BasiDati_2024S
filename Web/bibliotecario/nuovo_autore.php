<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.add_writer($1, $2, $3, $4, $5, $6);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["nome"], $_POST["cognome"], $_POST["d_nascita"], $_POST["d_morte"], $_POST["bio"], ToPostgresArray($_POST["libri"])));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Autore aggiunto con successo.";
    Redirect("gestione_autori.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Nuovo Autore";
$subtitle = "";

$class = "is-link";
$help = "";

// options query
$qry = "SELECT _isbn, __titolo FROM unibib.get_all_books()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$optionsLib = array();
while ($row = pg_fetch_array($res)) {
  $optionsLib[$row["_isbn"]] = $row["_isbn"]."-".$row["__titolo"];
}

$selectedLib = array();

$inputs = array(
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
    "type"=>"date",
    "label"=>"Data Nascita",
    "name"=>"d_nascita",
    "value"=>$_POST["d_nascita"],
    "placeholder"=>"",
    "icon"=>"fa-calendar-days"
  ),
  array(
    "type"=>"text",
    "label"=>"Data Morte",
    "name"=>"d_morte",
    "value"=>$_POST["d_morte"],
    "placeholder"=>"",
    "icon"=>"fa-calendar-days"
  ),
  array(
    "type"=>"text",
    "label"=>"Bio",
    "name"=>"bio",
    "value"=>$_POST["bio"],
    "placeholder"=>"Biografia...",
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"select",
    "multiple"=>"multiple",
    "ismultiple"=>"is-multiple",
    "label"=>"Libri",
    "name"=>"libri[]",
    "options"=>$optionsLib,
    "selected"=>$selectedLib,
    "icon"=>"fa-user-tie"
  )
);

require("../components/form.php");

?>