<?php

require_once("../scripts/utils.php");

if (isset($_POST["submit"])) {
  $qry = "CALL unibib.add_copy($1, $2);";
  $res = pg_prepare($con, "", $qry);
  $res = pg_execute($con, "", array($_POST["isbn"], $_POST["sede"]));

  if (!$res) {
    $error = ParseError(pg_last_error());
  }
  else {
    unset($error);
    $_SESSION["feedback"] = "Copia aggiunta con successo.";
    Redirect("home.php");
  }
}

$CUR_PAGE = "@bibliotecario.it";
$fa_icon = "fa-user-graduate";
$title = "Nuova copia";
$subtitle = "";

$class = "is-link";
$help = "";

// options query per selezione sede
$qry = "SELECT _isbn, __titolo FROM unibib.get_all_books()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$optionsBook = array();
while ($row = pg_fetch_array($res)) {
  $optionsBook[$row["_isbn"]] = $row["_isbn"] . "-" . $row["__titolo"];
}

$selectedBook = array();


// options query per selezione sede
$qry = "SELECT _id, _citta, _indirizzo FROM unibib.get_all_libraries()";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$optionsLib = array();
while ($row = pg_fetch_array($res)) {
  $optionsLib[$row["_id"]] = $row["_id"] . "-" . $row["_citta"] . " , " . $row["_indirizzo"];
}

$selectedLib = array();

$inputs = array(
  array(
    "type"=>"select",
    "label"=>"ISBN",
    "name"=>"isbn",
    "options"=>$optionsBook,
    "selected"=>$selectedBook,
    "icon"=>"fa-user-tag",
    "help"=>""
  ),
  array(
    "type"=>"select",
    "label"=>"Sede",
    "name"=>"sede",
    "options"=>$optionsLib,
    "selected"=>$selectedLib,
    "icon"=>"fa-user-tie",
    "help"=>""
  )
);

require("../components/form.php");

?>