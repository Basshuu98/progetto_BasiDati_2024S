<?php


require_once("../scripts/utils.php");

$CUR_PAGE="@bibliotecario.it";
$fa_icon="fa-graduation-cap";
$title = "Report sede";
$subtitle = "";

$class ="is-link";
$help ="";

$table_headers = array("Città", "Via", "ISBN", "Titolo", "Nome", "Cognome");

$qry = "SELECT * FROM unibib.get_report($1);";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($_POST["id"]));

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
       "separator"=>"",
       "separator_text"=>"",
       "class"=>"",
       "cols"=> array(
         array("type"=>"text", "val"=>$row["_citta"]),
         array("type"=>"text", "val"=>$row["_indirizzo"]),
         array("type"=>"text", "val"=>$row["_isbn"]),
         array("type"=>"text", "val"=>$row["_titolo"]),
         array("type"=>"text", "val"=>$row["_nome"]),
         array("type"=>"text", "val"=>$row["_cognome"])
        )
    )
  );
}

$inputs = array(
    array(
        "type"=>"hidden",
        "name"=>"id",
        "value"=>$_POST["id"]
      )
);

require("../components/table.php");

?>