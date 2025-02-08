<?php


require_once("../scripts/utils.php");

$CUR_PAGE="@lettore.it";
$fa_icon="fa-book";
$title = "Archivio Prestiti";
$subtitle = "";

$table_headers = array("ISBN", "Data Inizio", "Data Fine");

$qry = "SELECT * FROM unibib.get_active_rent_reader($1);";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array($_SESSION["cod_fisc"]));

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
       "separator"=>"",
       "separator_text"=>"",
       "class"=>"",
       "cols"=> array(
         array("type"=>"text", "val"=>$row["_isbn"]),
         array("type"=>"text", "val"=>$row["_d_inizio"]),
         array("type"=>"text", "val"=>$row["_d_fine"])
        )
    )
  );
}
require("../components/table.php");
?>