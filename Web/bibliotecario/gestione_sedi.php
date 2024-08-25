<?php


require_once("../scripts/utils.php");

$CUR_PAGE="@bibliotecario.it";
$fa_icon="fa-graduation-cap";
$title = "Gestione Sedi";
$subtitle = "";


$create = array(
  "target"=>"nuova_sede.php",
  "text"=>"Crea nuova sede"
);

$table_headers = array("ID", "Città", "Via");

$qry = "SELECT * FROM unibib.get_all_libraries();";
$res = pg_prepare($con, "", $qry);
$res = pg_execute($con, "", array());

$rows = array();

while($row = pg_fetch_assoc($res)) {
  array_push($rows,
    array(
       "separator"=>"",
       "separator_text"=>"",
       "class"=>"",
       "cols"=> array(
         array("type"=>"text", "val"=>$row["_id"]),
         array("type"=>"text", "val"=>$row["_citta"]),
         array("type"=>"text", "val"=>$row["_indirizzo"]),
         array(
            "type"=>"button",
            "target"=>"statistica_sede.php",
            "submit"=>"Statistiche",
            "class"=>"is-link",
            "params"=>array(
               "id"=> $row["_id"]
            )
         ),
         array(
            "type"=>"button",
            "target"=>"report_sede.php",
            "submit"=>"Report ritardi",
            "class"=>"is-link",
            "params"=>array(
               "id"=> $row["_id"]
            )
         ),
         array(
            "type"=>"button",
            "target"=>"modifica_sede.php",
            "submit"=>"Modifica",
            "class"=>"is-link",
            "params"=>array(
               "id"=> $row["_id"],
               "citta"=>$row["_citta"],
               "indirizzo" => $row["_indirizzo"]
            )
         )
        )
    )
  );
}
require("../components/table.php");
?>